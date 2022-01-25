<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\User;
use App\Models\OrdersDate;
use Carbon\Carbon;
use DB;

use App\Mail\OrderFailedMail;
use Mail;

use App\Models\Notification;
use App\Models\CronLog;
use App\Models\Channel;
use OneSignal;

use App\Models\Devices;
use NotificationHelper;

use App\Models\StoresProduct;
use PaymentHelper;

class CronController extends Controller
{

    public function auto_reject_order()
    {
        $orders_ids = Orders::where('status', 1)->where('created_at', '<', Carbon::now()->subHours(48))->pluck('id');
        $orders_48_hours = Orders::where('status', 1)->where('created_at', '<', Carbon::now()->subHours(48))->with(['order_products'])->get();
        foreach ($orders_48_hours as $key => $value) {
            $responce = Self::refund($value);
            CronLog::insert(array('ids' => $value->id, 'type' => $responce['message']));
        }
        // Orders::whereIn('id', $orders_ids)->update(array('status' => 3));
        echo "Cron run success";
        exit;
    }

    public function both_reject()
    {
        $cron_log = CronLog::where('created_at', '<', Carbon::now()->subHours(72))->delete();
        //=========================================================
        // 48 hours order reject
        $orders_ids = Orders::where('status', 1)->where('created_at', '<', Carbon::now()->subHours(48))->pluck('id');
        $orders_48_hours = Orders::where('status', 1)->where('created_at', '<', Carbon::now()->subHours(48))->with(['order_products'])->get();
        CronLog::insert(array('ids' => $orders_ids, 'type' => 'orders_48_hours'));

        foreach ($orders_48_hours as $key => $value) {
            $responce = Self::refund($value);
            CronLog::insert(array('ids' => $value->id, 'type' => $responce['message']));
        }

        Orders::whereIn('id', $orders_ids)->update(array('status' => 3));
        //=========================================================

        //=========================================================
        // 24 hours order failed
        $failed_orders_ids = Orders::where('store_accepted', '!=', NULL)->whereNotIn('status', [6, 7])->where('store_accepted', '<', Carbon::now()->subHours(24))->pluck('id');
        CronLog::insert(array('ids' => $failed_orders_ids, 'type' => 'failed_orders_ids_24_hours'));

        $failed_orders_data = Orders::where('store_accepted', '!=', NULL)->whereNotIn('status', [6, 7])->where('store_accepted', '<', Carbon::now()->subHours(24))->with(['order_products'])->get();
        foreach ($failed_orders_data as $row) {
            $order_id = $row->id;
            // send mail
            $order_details = Orders::where('id', $order_id)->first();
            if ($order_details) {
                if ($order_details->user_id) {
                    $user = User::where('id', $order_details->user_id)->first();
                    $title = "Order Failed";
                    $message = 'Order #' . $order_id . ' has been failed. You will get the refund in 6-7 working days. Contact admin for any query';
                    $responce = Self::refund($row, $title, $message);
                    CronLog::insert(array('ids' => $row->id, 'type' => $responce['message']));

                    // send mail User
                    Mail::to($user)->send(new OrderFailedMail('Order Failed', $message, config('app.address1'), config('app.address2')));

                    $data = Devices::where('user_id', $order_details->user_id)->first();
                    if (isset($data->token) && isset($data->type)) {
                        $type = 'order';
                        $notification_arr['type'] = $type;
                        $notification_arr['order_id'] = $order_id;
                        NotificationHelper::send($data->token, $title, $message, $data->type, $notification_arr);

                        $user_web_push = Channel::whereUserId($order_details->user_id)->pluck('channel_id');
                        if (count($user_web_push) > 0) {
                            $params['include_player_ids'] = $user_web_push;
                            $contents = [
                                "en" => $message,
                            ];
                            $params['contents'] = $contents;
                            OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
                        }
                    }
                }

                if ($order_details->store_id) {
                    $user = User::where('id', $order_details->store_id)->first();
                    $message = 'Order #' . $order_id . ' has been failed. Contact admin for any query';

                    // send mail store
                    Mail::to($user)->send(new OrderFailedMail('Order Failed', $message, config('app.address1'), config('app.address2')));

                    $data = Channel::whereUserId($order_details->store_id)->pluck('channel_id');
                    if (count($data) > 0) {
                        $params['include_player_ids'] = $data;
                        $contents = [
                            "en" => $message,
                        ];
                        $params['contents'] = $contents;
                        // OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
                    }
                }

                if ($order_details->store_id) {
                    $board_id = User::where('id', $order_details->store_id)->value('parent_id');
                    if ($board_id) {
                        $user = User::where('id', $board_id)->first();
                        $message = 'Order #' . $order_id . ' has been failed. Contact admin for any query';

                        // send mail board
                        Mail::to($user)->send(new OrderFailedMail('Order Failed', $message, config('app.address1'), config('app.address2')));

                        $data = Channel::whereUserId($board_id)->pluck('channel_id');
                        if (count($data) > 0) {
                            $params['include_player_ids'] = $data;
                            $contents = [
                                "en" => $message,
                            ];
                            $params['contents'] = $contents;
                            // OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
                        }
                    }
                }

                $admin_data = User::select('users.*', 'users_group.group_id')->join('users_group', 'users.id', '=', 'users_group.user_id')->where('users_group.group_id', '=', '1')->get();
                if (count($admin_data) > 0) {
                    $message = 'Order #' . $order_id . ' has been failed. Contact admin for any query';

                    // send mail admin
                    Mail::to($admin_data)->send(new OrderFailedMail('Order Failed', $message, config('app.address1'), config('app.address2')));

                    foreach ($admin_data as $key => $row) {
                        $data = Channel::whereUserId($row->id)->pluck('channel_id');
                        if (count($data) > 0) {
                            $params['include_player_ids'] = $data;
                            $contents = [
                                "en" => $message,
                            ];
                            $params['contents'] = $contents;
                            // OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
                        }
                    }
                }
            }
        }
        Orders::whereIn('id', $failed_orders_ids)->update(array('status' => 7));
        //=========================================================
        echo "Cron run success";
        exit;
    }

    public function order_failed()
    {

        $failed_orders_ids = Orders::where('store_accepted', '!=', NULL)->whereNotIn('status', [6, 7])->where('store_accepted', '<', Carbon::now()->subHours(24))->pluck('id');
        Orders::whereIn('id', $failed_orders_ids)->update(array('status' => 7));
        dd($failed_orders_ids);
        $failed_orders_data = Orders::where('store_accepted', '!=', NULL)->with(['order_products'])->whereNotIn('status', [6, 7])->where('store_accepted', '<', Carbon::now()->subHours(24))->get();
        foreach ($failed_orders_data as $row) {
            $order_id = $row->id;
            // send mail            
            $order_details = Orders::where('id', $order_id)->first();
            if ($order_details) {
                if ($order_details->user_id) {
                    $user = User::where('id', $order_details->user_id)->first();
                    $title = "Order Failed";
                    $message = 'Order #' . $order_id . ' has been failed. You will get the refund in 6-7 working days. Contact admin for any query';
                    Self::refund($row, $title, $message);

                    // send mail User
                    Mail::to($user)->send(new OrderFailedMail('Order Failed', $message, config('app.address1'), config('app.address2')));

                    $data = Devices::where('user_id', $order_details->user_id)->first();
                    if (isset($data->token) && isset($data->type)) {
                        $type = 'order';
                        $notification_arr['type'] = $type;
                        $notification_arr['order_id'] = $order_id;
                        NotificationHelper::send($data->token, $title, $message, $data->type, $notification_arr);

                        $user_web_push = Channel::whereUserId($order_details->user_id)->pluck('channel_id');
                        if (count($user_web_push) > 0) {
                            $params['include_player_ids'] = $user_web_push;
                            $contents = [
                                "en" => $message,
                            ];
                            $params['contents'] = $contents;
                            OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
                        }
                    }
                }

                if ($order_details->store_id) {
                    $user = User::where('id', $order_details->store_id)->first();
                    $message = 'Order #' . $order_id . ' has been failed. Contact admin for any query';

                    // send mail store
                    Mail::to($user->email)->send(new OrderFailedMail('Order Failed', $message, config('app.address1'), config('app.address2')));

                    $data = Channel::whereUserId($order_details->store_id)->pluck('channel_id');
                    if ($data) {
                        $params['include_player_ids'] = $data;
                        $contents = [
                            "en" => $message,
                        ];
                        $params['contents'] = $contents;
                        // OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
                    }
                }

                if ($order_details->store_id) {
                    $board_id = User::where('id', $order_details->store_id)->value('parent_id');
                    if ($board_id) {
                        $user = User::where('id', $board_id)->first();
                        $message = 'Order #' . $order_id . ' has been failed. Contact admin for any query';

                        // send mail board
                        Mail::to($user)->send(new OrderFailedMail('Order Failed', $message, config('app.address1'), config('app.address2')));

                        $data = Channel::whereUserId($board_id)->pluck('channel_id');
                        if ($data) {
                            $params['include_player_ids'] = $data;
                            $contents = [
                                "en" => $message,
                            ];
                            $params['contents'] = $contents;
                            // OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
                        }
                    }
                }

                $admin_data = User::select('users.*', 'users_group.group_id')->join('users_group', 'users.id', '=', 'users_group.user_id')->where('users_group.group_id', '=', '1')->get();
                if ($admin_data) {
                    $message = 'Order #' . $order_id . ' has been failed. Contact admin for any query';

                    // send mail admin
                    Mail::to($admin_data)->send(new OrderFailedMail('Order Failed', $message, config('app.address1'), config('app.address2')));

                    foreach ($admin_data as $key => $row) {
                        $data = Channel::whereUserId($row->id)->pluck('channel_id');
                        if ($data) {
                            $params['include_player_ids'] = $data;
                            $contents = [
                                "en" => $message,
                            ];
                            $params['contents'] = $contents;
                            // OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
                        }
                    }
                }
            }
        }


        // Orders::whereIn('id', $failed_orders_ids)->update(array('status' => 7));
        echo "Cron run success";
        exit;
    }

    public function refund($order = array(), $title = '', $message = '')
    {
        if ($order->transaction_id != '' && $order->gateway_trans_id != '') {
            $response = PaymentHelper::makeRefund($order->transaction_id, $order->gateway_trans_id, $order->total);
            if ($response->ResponseCode == "00000") {
                $GatewayTransID = $response->responseMessage->GatewayTransID;
                $TransactionID = $response->TransactionID;

                // Code

                foreach ($order->order_products as $key => $value) {
                    $store_variant = StoresProduct::where('user_id', $order->store_id)->where('product_id', $value['product_id'])->first();
                    if ($store_variant) {
                        $store_variant->stock = $store_variant->stock + $value['qty'];
                        $store_variant->save();
                    }
                }

                $order->status = '3';
                $order->save();

                $device = Devices::where('user_id', $order->user_id)->first();
                $user = User::find($order->user_id);
                if ($title != '' && $message != '') {
                    $title = $title;
                    $message = $message;
                } else {
                    $title = "Order Rejected";
                    $message = 'Order #' . $order->id . ' has been rejected by store';
                }
                $type = 'order_rejected';
                if ($device) {
                    $notification_arr['type'] = $type;
                    $notification_arr['order_id'] = $order->id;
                    $notification = NotificationHelper::send($device->token, $title, $message, $device->type, $notification_arr);

                    $user_web_push = Channel::whereUserId($order->user_id)->pluck('channel_id');
                    if (count($user_web_push) > 0) {
                        $params['include_player_ids'] = $user_web_push;
                        $contents = [
                            "en" => $message,
                        ];
                        $params['contents'] = $contents;
                        OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
                    }
                    $data['notification'] = $notification;
                }

                NotificationHelper::send_sms($message, $user->phone);

                $notification_data = array(
                    'user_id' => $order->user_id,
                    'order_id ' => $order->id,
                    'title' => $title,
                    'message' => $message,
                    'type' => $type
                );
                Notification::Create($notification_data);
                $data['message'] = 'Rejected successfully';
                $data['status'] = TRUE;

                // end Code
            } else {
                $data['message'] = 'Refund transaction fail. Contact admin.';
                $data['status'] = FALSE;
            }
        } else {
            $data['message'] = 'Transaction data not found. Contact admin.';
            $data['status'] = FALSE;
        }
        return $data;
    }

    public function order_expire()
    {
        $order = Orders::where('status', 1)->get();
        if (count($order) > 0) {
            $order = $order->toArray();
            $order_ids = array_column($order, 'id');
            $order_dates = OrdersDate::whereIn('order_id', $order_ids)->where('status', 0)->whereDate('date', '<=', Carbon::now()->format('Y-m-d'))->get();
            if (count($order_dates) > 0) {
                $order_dates = $order_dates->toArray();
                $order_dates_ids = array_column($order_dates, 'id');
                OrdersDate::whereIn('id', $order_dates_ids)->update(array('status' => 1));
            }
        }
    }

    public function order_3_days_notification()
    {
        $order = Orders::where('status', 1)->get();
        if (count($order) > 0) {
            $add_3_days = Carbon::now()->addDays(3);
            $order = $order->toArray();
            $order_ids = array_column($order, 'id');
            $order_ids = array_unique($order_ids);
            $order_ids = array_values($order_ids);
            $order_dates = OrdersDate::whereIn('order_id', $order_ids)->where('date', '<', $add_3_days)->get();
            if (count($order_dates) > 0) {
                dd($order_dates);
                $order_dates = $order_dates->toArray();
            }
        }
    }

    public function reminder()
    {
        $order = Orders::where('status', 1)->get()->pluck('id');
        if (count($order) > 0 && !empty($order)) {
            // $order_ids = array_column($order, 'id');
            // $order_ids = array_unique($order_ids);
            // $order_ids = array_values($order_ids);
            // print_r($order);exit;
            $date = date('Y-m-d');
            $diff = OrdersDate::select(DB::raw('DATEDIFF(date,"' . $date . '") as days'), 'order_id', 'date')
                ->whereIn('id', function ($q) {
                    $q->select(DB::raw('MAX(id) FROM order_dates GROUP BY order_id'));
                })
                ->whereIn('order_id', $order)
                ->having('days', '<=', 3)
                ->having('days', '>', 0)
                ->get();
            // echo "<pre>";   
            // print_r($diff->toArray());exit;   

            if (count($diff) > 0) {
                foreach ($diff as $value) {

                    $get_customer = Orders::select('devices.token', 'users.parent_id', 'orders.*')
                        ->join('devices', 'devices.user_id', 'orders.customer_id')
                        ->join('users', 'users.id', 'orders.customer_id')
                        ->where('orders.id', $value->order_id)
                        ->first();

                    if (!empty($get_customer)) {
                        $token = $get_customer->token;
                        if ($get_customer->parent_id != 0) {
                            $device = Devices::whereUserId($get_customer->parent_id)->first();
                            $token = $device->token;
                            $get_customer->customer_id = $get_customer->parent_id;
                        }
                        switch ($value->days) {
                            case 1:
                                if ($token != "") {
                                    $push_title = __('Meal Reminder');
                                    $push_data = array();
                                    $push_data['order_id'] = $value->order_id;
                                    $push_data['message'] = __('Your ' . $value->meal_name . ' plan is expiring in today. Please book new meal plan to enjoy your meal.');
                                    $push_type = 'order_reminder';
                                    $notification = NotificationHelper::send($token, $push_title, $push_data, $push_type);
                                    NotificationHelper::add($get_customer->customer_id, $push_data['message'], $push_type, $value->order_id);
                                }
                                break;
                            case 2:
                                if ($token != "") {
                                    $push_title = __('Meal Reminder');
                                    $push_data = array();
                                    $push_data['order_id'] = $value->order_id;
                                    $push_data['message'] = __('Your ' . $value->meal_name . ' plan is expiring in 2 days, Please book new meal plan to enjoy your meal.');
                                    $push_type = 'order_reminder';
                                    $notification = NotificationHelper::send($token, $push_title, $push_data, $push_type);
                                    NotificationHelper::add($get_customer->customer_id, $push_data['message'], $push_type, $value->order_id);
                                }
                                break;
                            case 3:
                                if ($token != "") {
                                    $push_title = __('Meal Reminder');
                                    $push_data = array();
                                    $push_data['order_id'] = $value->order_id;
                                    $push_data['message'] = __('Your ' . $value->meal_name . ' plan is expiring in 3 days, Please book new meal plan to enjoy your meal.');
                                    $push_type = 'order_reminder';
                                    $notification = NotificationHelper::send($token, $push_title, $push_data, $push_type);
                                    NotificationHelper::add($get_customer->customer_id, $push_data['message'], $push_type, $value->order_id);
                                }
                                break;
                            default:
                                break;
                        }
                    }
                }
                echo "Cron run success";
            }
        } else {
            echo "no order found";
        }
    }


    public function diet_station()
    {
        $orders = Orders::select('orders.*')->where('to_date', '>=', date('Y-m-d'))->join('order_dates', 'order_dates.order_id', 'orders.id')
            ->where('order_dates.diet_station', 0)
            ->with(['meal', 'customer', 'order_dates', 'meal.category', 'order_dates.order_products'])
            ->whereHas('customer', function ($query) {
                $query->where('is_diet_station', '!=', '0');
            })
            ->where('orders.id', 492)
            ->groupBy('order_dates.order_id')
            ->get();
        echo "<pre>";
        $post = [];
        $i = 0;
        foreach ($orders->toArray() as $order) {
            foreach ($order['order_dates'] as $row) {
                $post['meals'][$i]['date'] = $row['date'];
                // $post['meals'][$i]['order_id'] = $order['id'];

                $j = 0;
                foreach ($order['meal']['category'] as $val) {
                    $products = array_filter($row['order_products'], function ($var) use ($val) {
                        return ($var['category_id'] == $val['category_id']);
                    });
                    $post['meals'][$i]['items'][$j]['mealCategory'] = $val['category_name'];
                    $post['meals'][$i]['items'][$j]['meals'] = array_column($products, 'product_name');
                    $j++;
                }
                $i++;
            }
            echo json_encode($post);
            exit;
            print_r($post);
        }
    }

    public static function postCurl($data, $order_id)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('DIET_STATION_URL') . 'canteeny/update-meals',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer 123',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $result = json_decode($response);
        if (!empty($result)) {
            if ($result->result->status == "success") {
                $order_id = $result->result->order->number;
                OrdersDate::whereOrderId($order_id)->update(['diet_station' => 1]);
            }
        }
    }
}