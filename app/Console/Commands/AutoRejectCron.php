<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Orders;
use App\Models\User;
use Carbon\Carbon;

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

class AutoRejectCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rejectcron:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // 72 hours ago record deleted
        //=========================================================
        $cron_log = CronLog::where('created_at', '<', Carbon::now()->subHours(72))->delete();
        //=========================================================

        //=========================================================
        // 48 hours order reject
        $responce['message'] = '';
        $orders_ids = Orders::where('status', 1)->where('created_at', '<', Carbon::now()->subHours(48))->pluck('id');
        $orders_48_hours = Orders::where('status', 1)->where('created_at', '<', Carbon::now()->subHours(48))->with(['order_products'])->get();
        CronLog::insert(array('ids' => $orders_ids, 'type' => 'orders_48_hours'));
        var_dump($orders_48_hours->pluck('id')->toArray());
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
        var_dump($failed_orders_ids->pluck('id')->toArray());

        $failed_orders_data = Orders::where('store_accepted', '!=', NULL)->whereNotIn('status', [6, 7])->where('store_accepted', '<', Carbon::now()->subHours(24))->with(['order_products'])->get();
        foreach ($failed_orders_data as $row) {
            $order_id = $row->id;
            Orders::where('id', $order_id)->update(array('status' => 7));
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
                    }
                    // $user_web_push = Channel::whereUserId($order_details->user_id)->pluck('channel_id');
                    // if (count($user_web_push) > 0) {
                    //     $params['include_player_ids'] = $user_web_push;
                    //     $contents = [
                    //         "en" => $message,
                    //     ];
                    //     $params['contents'] = $contents;
                    //     OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
                    // }
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
                        OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
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
                            OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
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
                            OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
                        }
                    }
                }
            }
        }
        Orders::whereIn('id', $failed_orders_ids)->update(array('status' => 7));
        //=========================================================

        \Log::info("Cron is working fine!");
        return Command::SUCCESS;
    }

    public function refund($order = array(), $title = null, $message = null)
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
                if (isset($title) && isset($message)) {
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

                    $data['notification'] = $notification;
                }
                // $user_web_push = Channel::whereUserId($order->user_id)->pluck('channel_id');
                // if (count($user_web_push) > 0) {
                //     $params['include_player_ids'] = $user_web_push;
                //     $contents = [
                //         "en" => $message,
                //     ];
                //     $params['contents'] = $contents;
                //     OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
                // }

                NotificationHelper::send_sms($message, $user->phone);

                $notification_data = array(
                    'user_id' => $order->user_id,
                    'order_id ' => $order->id,
                    'title' => $title,
                    'message' => $message,
                    'type' => $type
                );
                Notification::Create($notification_data);
                $data['message'] = $message;
                $data['status'] = TRUE;

                // end Code
            } else {
                $data['message'] = 'Refund transaction fail. Contact admin.';
                $data['status'] = FALSE;
            }
            $user_web_push = Channel::whereUserId($order->user_id)->pluck('channel_id');
            if (count($user_web_push) > 0) {
                $params['include_player_ids'] = $user_web_push;
                $contents = [
                    "en" => $data['message'],
                ];
                $params['contents'] = $contents;
                OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
            }
        } else {
            $data['message'] = 'Transaction data not found. Contact admin.';
            $data['status'] = FALSE;
        }
        return $data;
    }
}