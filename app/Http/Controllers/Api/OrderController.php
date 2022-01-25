<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cart;
use App\Models\CartProducts;
use App\Models\StoresProduct;
use App\Models\Orders;
use App\Models\OrdersProduct;
use App\Models\Notification;
use App\Models\Card;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Validator;
use CommonHelper;

use App\Mail\OrderPlaceMail;
use App\Mail\OrderComplateMail;
use Mail;
use App\Models\Devices;
use NotificationHelper;
use PaymentHelper;

use App\Models\Channel;
use OneSignal;

class OrderController extends Controller

{

    use ApiResponser;

    public function index(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'cart_id' => 'required|numeric|not_in:0',
            'name' => 'required',
            'number' => 'required',
            'pickup_notes' => '',
            'pickup_method' => 'required|numeric',
            'vehicle_description' => '',
            'transaction_id' => 'required|numeric|not_in:0',
            'gateway_trans_id' => 'required|numeric|not_in:0'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => $validator->errors()->all(),
            ], 200);
        }

        $vehicle_description = '';
        if ($request->pickup_method == '2') {
            if (!isset($request->vehicle_description) || $request->vehicle_description == '') {
                return response([
                    'status' => false,
                    'message' => 'Vehicle description is required if pickup method is curbside.',
                ]);
            } else {
                $vehicle_description = $request->vehicle_description;
            }
        }

        $user_id = request()->user()->id;
        $cart = Cart::whereId($request->cart_id)->whereUserId($user_id)->where('order_id', '0')->first();
        if ($cart) {

            $cart_products = CartProducts::whereCartId($request->cart_id)->with(['product'])->get();
            $sub_total = 0;
            $order_products = [];
            if (auth()->user()->user_type == '1') {

                foreach ($cart_products as $key => $value) {
                    if (StoresProduct::where('user_id', $cart->store_id)->where('product_id', $value->product->id)->where('stock', '>=', $value->qty)->first()) {

                        $temp['product_id'] = $value->product->id;
                        $temp['qty'] = $value->qty;

                        $sub_total += $value->qty * $value->product->current_price_business;
                        $temp['price'] = $value->product->current_price_business;

                        array_push($order_products, $temp);
                    } else {
                        return response([
                            'status' => false,
                            'message' => $value->product->name . ' Out of stock'
                        ]);
                    }
                }
            } else {
                foreach ($cart_products as $key => $value) {
                    if (StoresProduct::where('user_id', $cart->store_id)->where('product_id', $value->product->id)->where('stock', '>=', $value->qty)->first()) {
                        $temp['product_id'] = $value->product->id;
                        $temp['qty'] = $value->qty;

                        $sub_total += $value->qty * $value->product->current_price_retail;
                        $temp['price'] = $value->product->current_price_retail;

                        array_push($order_products, $temp);
                    } else {
                        return response([
                            'status' => false,
                            'message' => $value->product->name . ' Out of stock'
                        ]);
                    }
                }
            }


            $sub_total = number_format((float) $sub_total, 2, '.', '');
            $tax = CommonHelper::ConfigGet('tax');
            if ($tax > 0) {
                $tax_amount = ($tax / 100) * $sub_total;
                $tax_amount = number_format((float) $tax_amount, 2, '.', '');
                $total = $sub_total + $tax_amount;
                $total = number_format((float) $total, 2, '.', '');
            } else {
                $total = $sub_total;
                $tax_amount = '0';
            }

            $order_data = array(
                'user_id' => $user_id,
                'store_id' => $cart->store_id,
                'cart_id' => $request->cart_id,
                'sub_total' => $sub_total,
                'total' => $total,
                'tax' => $tax_amount,
                'pickup_method' => $request->pickup_method,
                'name' => $request->name,
                'number' => $request->number,
                'pickup_notes' => (isset($request->pickup_notes)) ? $request->pickup_notes : '',
                'vehicle_description' => $vehicle_description,
                'status' => 1,
                'reached' => NULL,
                'transaction_id' => $request->transaction_id,
                'gateway_trans_id' => $request->gateway_trans_id
            );

            //return $order_data;

            $order = Orders::Create($order_data);

            foreach ($order_products as $key => $value) {
                $order_products[$key]['order_id'] = $order->id;
            }
            $order_product = OrdersProduct::insert($order_products);

            $cart->order_id = $order->id;
            $cart->save();

            // send mail
            $order_id = $order->id;

            // stock update
            $order = Orders::where('id', $order_id)->with(['order_products'])->first();
            foreach ($order->order_products as $key => $value) {
                $store_product = StoresProduct::where('user_id', $order->store_id)->where('product_id', $value['product_id'])->first();
                if ($store_product) {
                    $store_product->stock = $store_product->stock - $value['qty'];
                    $store_product->save();
                }
            }



            $user = User::where('id', $order->user_id)->first();
            $message = 'Hello ' . $user->first_name . ' ' . $user->last_name . ', Your order #' . $order_id . ' has been successfully placed';
            // send mail User
            Mail::to($user->email)->send(new OrderPlaceMail('Order Placed', $message, config('app.address1'), config('app.address2')));

            $title = 'Order Placed';
            $type = 'order';
            $data = Devices::where('user_id', $user_id)->first();
            if ($data) {
                $notification_arr['type'] = $type;
                $notification_arr['order_id'] = $order_id;
                NotificationHelper::send($data->token, $title, $message, $data->type, $notification_arr);
            }
            $user_web_push = Channel::whereUserId($user_id)->pluck('channel_id');
            if (count($user_web_push) > 0) {
                $params['include_player_ids'] = $user_web_push;
                $contents = [
                    "en" => $message,
                ];
                $params['contents'] = $contents;
                OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
            }

            $notification_data = array(
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'title' => $title,
                'message' => $message,
                'type' => $type
            );
            Notification::Create($notification_data);

            $store = User::where('id', $order->store_id)->first();
            $board_id = $store->parent_id;
            $message = 'New order #' . $order_id . ' received.';
            // send mail store
            Mail::to($store->email)->send(new OrderPlaceMail('Order Placed', $message, config('app.address1'), config('app.address2')));

            $store = Channel::whereUserId($order->store_id)->pluck('channel_id');
            if (count($store) > 0) {
                $params['include_player_ids'] = $store;
                $contents = [
                    "en" => $message,
                ];
                $params['contents'] = $contents;
                OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
            }


            $board = User::where('id', $board_id)->first();
            $message = 'New order #' . $order_id . ' received.';
            // send mail board
            Mail::to($board->email)->send(new OrderPlaceMail('Order Placed', $message, config('app.address1'), config('app.address2')));

            $board = Channel::whereUserId($board_id)->pluck('channel_id');
            if (count($board) > 0) {
                $params['include_player_ids'] = $board;
                $contents = [
                    "en" => $message,
                ];
                $params['contents'] = $contents;
                OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
            }

            $admin_data = User::select('users.*', 'users_group.group_id')->join('users_group', 'users.id', '=', 'users_group.user_id')->where('users_group.group_id', '=', '1')->get();

            if ($admin_data) {
                $message = 'New order #' . $order_id . ' received.';
                // send mail admin
                Mail::to($admin_data)->send(new OrderPlaceMail('Order Placed', $message, config('app.address1'), config('app.address2')));
            }

            $data['order_id'] = $order->id;
            $data['order_product'] = $order_products;

            return response([
                'status' => true,
                'data' => $data,
                'message' => 'Order placed successfully',
            ]);
        } else {
            return response([
                'status' => false,
                'message' => 'Cart data not found.'
            ]);
        }
    }

    public function reached(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'order_id' => 'required|numeric|not_in:0'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => $validator->errors()->all(),
            ], 200);
        }

        $user_id = request()->user()->id;
        $orderItem = [];
        $order = Orders::whereStatus('4')->whereUserId($user_id)->whereId($request->order_id)->first();
        if ($order) {

            if ($order->reached == NULL) {

                // send mail for all
                $order_id = $order->id;

                $order_data = Orders::where('id', $order_id)->with(['order_products.product', 'store'])->first();

                $address = $order_data->store->address;
                $order_info = '<p></p>';
                foreach ($order_data->order_products as $key => $row) {
                    $total_prodcut_price = $row['price'] * $row['qty'];
                    $order_info = '<table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">';
                    $order_info .= '<tbody>';
                    $order_info .= '<tr>';
                    $order_info .= '<td class="o_bg-light o_px-xs" align="center" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">';
                    $order_info .= '<table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">';
                    $order_info .= '<tbody>';
                    $order_info .= '<tr>';
                    $order_info .= '<td class="o_re o_bg-white o_px o_pt" align="center" style="font-size: 0;vertical-align: top;background-color: #ffffff;padding-left: 16px;padding-right: 16px;padding-top: 16px;">';
                    $order_info .= '<div class="o_col o_col-1 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 100px;">
                            <div class="o_px-xs o_sans o_text o_center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;text-align: center;padding-left: 8px;padding-right: 8px;">
                                <p style="margin-top: 0px;margin-bottom: 0px;"><a class="o_text-primary" href="#" style="text-decoration: none;outline: none;color: #126de5;"><img src="' . $row['image'] . '" width="84" height="84" alt="" style="max-width: 84px;-ms-interpolation-mode: bicubic;vertical-align: middle;border: 0;line-height: 100%;height: auto;outline: none;text-decoration: none;border-radius: 12%;"></a></p>
                            </div>
                            </div>';
                    $order_info .= '<div class="o_col o_col-3 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 300px;">
                            <div style="font-size: 16px; line-height: 16px; height: 16px;">&nbsp; </div>
                            <div class="o_px-xs o_sans o_text o_text-light o_left o_xs-center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #82899a;text-align: left;padding-left: 8px;padding-right: 8px;">
                                <h4 class="o_heading o_text-dark o_mb-xxs" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 4px;color: #242b3d;font-size: 18px;line-height: 23px;">' . $row['product_name'] . '</h4>
                                <p class="o_text-secondary o_mb-xs" style="color: #424651;margin-top: 0px;margin-bottom: 8px;">Size : ' . $row['product']['quantity'] . ' ' . $row['product']['measurement_name'] . '</p>
                            </div>
                            </div>';
                    $order_info .= '<div class="o_col o_col-1 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 100px;">
                            <div class="o_hide-xs" style="font-size: 16px; line-height: 16px; height: 16px;">&nbsp; </div>
                            <div class="o_px-xs o_sans o_text o_text-secondary o_center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #424651;text-align: center;padding-left: 8px;padding-right: 8px;">
                                <p class="o_mb-xxs" style="margin-top: 0px;margin-bottom: 4px;"><span class="o_hide-lg" style="display: none;font-size: 0;max-height: 0;width: 0;line-height: 0;overflow: hidden;mso-hide: all;visibility: hidden;">Quantity:&nbsp; </span>' . $row['qty'] . '</p>
                            </div>
                            </div>';
                    $order_info .= '<div class="o_col o_col-1 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 100px;">
                            <div class="o_hide-xs" style="font-size: 16px; line-height: 16px; height: 16px;">&nbsp; </div>
                            <div class="o_px-xs o_sans o_text o_text-secondary o_right o_xs-center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #424651;text-align: right;padding-left: 8px;padding-right: 8px;">
                                <p class="o_mb-xxs" style="margin-top: 0px;margin-bottom: 4px;"><span class="o_hide-lg" style="display: none;font-size: 0;max-height: 0;width: 0;line-height: 0;overflow: hidden;mso-hide: all;visibility: hidden;">Price:&nbsp; </span>'
                        . config('app.currency') . ' ' . $total_prodcut_price .
                        ' </p>
                            </div>
                            </div>';
                    $order_info .= '<div class="o_px-xs" style="padding-left: 8px;padding-right: 8px;">
                            <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                <tbody>
                                <tr>
                                    <td class="o_re o_bb-light" style="font-size: 16px;line-height: 16px;height: 16px;vertical-align: top;border-bottom: 1px solid #d3dce0;">&nbsp; </td>
                                </tr>
                                </tbody>
                            </table>
                            </div>';
                    $order_info .= '</td>';
                    $order_info .= '</tr>';
                    $order_info .= '</tbody>';
                    $order_info .= '</table>';
                    $order_info .= '</td>';
                    $order_info .= '</tr>';
                    $order_info .= '</tbody>';
                    $order_info .= '</table>';
                }


                $pickup_method = $order_data->pickup_method == 1 ? 'InStore' : 'CurbSide';
                $pickup_notes = isset($orderItem['pickup_notes']) ? $orderItem['pickup_notes'] : '-';
                $reached = now();

                $user = User::where('id', $order_data->user_id)->first();
                $message = 'Order #' . $order_id . ' has been successfully completed';
                $orderItem = $order_data->toArray();
                // send mail for users
                Mail::to($user->email)->send(new OrderComplateMail('Order completed', $message, config('app.address1'), config('app.address2'), $orderItem, $orderItem['store_name'], $address, $reached, $orderItem['sub_total'], $orderItem['total'], $orderItem['tax'], $pickup_method, config('app.currency'), $pickup_notes, $orderItem['vehicle_description']));

                $params = [];

                $user = User::where('id', $order_data->store_id)->first();
                $message = 'Customer arrived for Order #' . $order_id;
                // send mail for store
                Mail::to($user->email)->send(new OrderComplateMail('Order completed', $message, config('app.address1'), config('app.address2'), $orderItem, $orderItem['store_name'], $address, $reached, $orderItem['sub_total'], $orderItem['total'], $orderItem['tax'], $pickup_method, config('app.currency'), $pickup_notes, $orderItem['vehicle_description']));

                $data = Channel::whereUserId($order_data->store_id)->pluck('channel_id');
                if (count($data) > 0) {
                    $params['include_player_ids'] = $data;
                    $contents = [
                        "en" => $message,
                    ];
                    $params['contents'] = $contents;
                    OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
                }

                $board_id = User::where('id', $order_data->store_id)->value('parent_id');
                $user = User::where('id', $board_id)->first();
                $message = 'Customer arrived for Order #' . $order_id;

                // send mail for Store
                Mail::to($user->email)->send(new OrderComplateMail('Order completed', $message, config('app.address1'), config('app.address2'), $orderItem, $orderItem['store_name'], $address, $reached, $orderItem['sub_total'], $orderItem['total'], $orderItem['tax'], $pickup_method, config('app.currency'), $pickup_notes, $orderItem['vehicle_description']));

                $data = Channel::whereUserId($board_id)->pluck('channel_id');
                if (count($data) > 0) {
                    $params['include_player_ids'] = $data;
                    $contents = [
                        "en" => $message,
                    ];
                    $params['contents'] = $contents;
                    OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
                }

                $admin_data = User::select('users.*', 'users_group.group_id')->join('users_group', 'users.id', '=', 'users_group.user_id')->where('users_group.group_id', '=', '1')->first();
                $admin_id = User::where('id', 1)->value('id');
                if ($admin_data) {
                    $user = User::where('id', $admin_id)->first();
                    $message = 'Customer arrived for Order #' . $order_id;

                    // send mail for admin
                    Mail::to($admin_data)->send(new OrderComplateMail('Order completed', $message, config('app.address1'), config('app.address2'), $orderItem, $orderItem['store_name'], $address, $reached, $orderItem['sub_total'], $orderItem['total'], $orderItem['tax'], $pickup_method, config('app.currency'), $pickup_notes, $orderItem['vehicle_description']));

                    $data = Channel::whereUserId($admin_data->id)->pluck('channel_id');
                    if (count($data) > 0) {
                        $params['include_player_ids'] = $data;
                        $contents = [
                            "en" => $message,
                        ];
                        $params['contents'] = $contents;
                        OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
                    }
                    // Mail::to($user->email)->send(new OrderComplateMail('Order completed', $message, config('app.address1'), config('app.address2'), $orderItem, $orderItem['store_name'], $orderItem['address'], $reached, $orderItem['sub_total'], $orderItem['total'], $orderItem['tax'],$pickup_method, config('app.currency'), $orderItem['pickup_notes'], $orderItem['vehicle_description']));
                }


                $order->reached = now();
                $order->status = 5;
                $order->save();
                return response([
                    'status' => true,
                    'data' => [],
                    'message' => 'Store manager notified.',
                ]);
            } else {
                return response([
                    'status' => false,
                    'message' => 'Status already updated as reached'
                ]);
            }
        } else {
            return response([
                'status' => false,
                'message' => 'Order not found.'
            ]);
        }
    }

    protected function history(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'status' => 'required|numeric|in:1,2',
            'order_id' => ''

        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => $validator->errors()->all(),
            ], 200);
        }

        $status_array = $request->status == 1 ? [1, 2, 4, 5] : [3, 6, 7];
        $user_id = request()->user()->id;
        $orders = Orders::where('user_id', $user_id)->whereIn('status', $status_array)->with(['order_products.product', 'store'])->orderBy('id', 'DESC')->paginate(10);

        foreach ($orders as $key => $value) {
            foreach ($value->order_products as $p_key => $p_value) {
                $product_total = $p_value->price * $p_value->qty;
                $orders[$key]['order_products'][$p_key]['product_total'] = number_format((float) $product_total, 2, '.', '');
            }
        }

        if (isset($request->order_id) && $request->order_id != '') {
            $order = Orders::where('id', $request->order_id)->whereIn('status', $status_array)->with(['order_products.product', 'store'])->first();
            foreach ($order->order_products as $p_key => $p_value) {
                $product_total = $p_value->price * $p_value->qty;
                $order->order_products[$p_key]['product_total'] = number_format((float) $product_total, 2, '.', '');
            }
        }

        if ($orders) {
            $response = array(
                'status' => true,
                'message' => '',
                'data' => $orders
            );

            if (isset($request->order_id) && $request->order_id != '') {
                $response['order'] = $order;
            }

            return response()->json($response);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'No order found'
            ]);
        }
    }

    public function detail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|numeric|not_in:0'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => $validator->errors()->all(),
            ], 200);
        }

        $order = Orders::where('id', $request->order_id)->with(['order_products.product', 'store'])->first();
        if ($order) {
            $order->show_imhere = ($order->status == 4) ? true : false;
            foreach ($order->order_products as $p_key => $p_value) {
                $product_total = $p_value->price * $p_value->qty;
                $order->order_products[$p_key]['product_total'] = number_format((float) $product_total, 2, '.', '');
            }
            return response()->json([
                'status' => true,
                'message' => '',
                'data' => $order
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'order not found',
            ]);
        }
    }

    public function update_order_status(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'order_id' => 'required|numeric|not_in:0',
            'status' => 'required|in:1,2'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => $validator->errors()->all(),
            ], 200);
        }

        $order = Orders::where('id', $request->order_id)->with(['order_products'])->first();
        if ($order) {
            if ($order->status == '0') {

                $order->status = $request->status;
                $order->save();

                $user_id = $order->user_id;
                $data = Devices::where('user_id', $user_id)->first();
                $type = 'order';

                if ($request->status == 1) {
                    $title = 'Payment Success';
                    $message = 'Payment Success for Order ID' . $request->order_id;

                    foreach ($order->order_products as $key => $value) {
                        $store_product = StoresProduct::where('user_id', $order->store_id)->where('product_id', $value['product_id'])->first();
                        if ($store_product) {
                            $store_product->stock = $store_product->stock - $value['qty'];
                            $store_product->save();
                        }
                    }
                    // $params = [];
                    $store = Channel::whereUserId($order->store_id)->pluck('channel_id');
                    if (count($store) > 0) {
                        $params['include_player_ids'] = $store;
                        $contents = [
                            "en" => $message,
                        ];
                        $params['contents'] = $contents;
                        OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
                    }

                    $board_id = User::where('id', $order->store_id)->value('parent_id');
                    $board = Channel::whereUserId($board_id)->pluck('channel_id');
                    if (count($board) > 0) {
                        $params['include_player_ids'] = $board;
                        $contents = [
                            "en" => $message,
                        ];
                        $params['contents'] = $contents;
                        OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
                    }

                    if ($data) {
                        $notification_arr['type'] = $type;
                        $notification_arr['order_id'] = $request->order_id;
                        NotificationHelper::send($data->token, $title, $message, $data->type, $notification_arr);
                    }
                    $user_web_push = Channel::whereUserId($user_id)->pluck('channel_id');
                    if (count($user_web_push) > 0) {
                        $params['include_player_ids'] = $user_web_push;
                        $contents = [
                            "en" => $message,
                        ];
                        $params['contents'] = $contents;
                        OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
                    }


                    $notification_data = array(
                        'user_id' => $order->user_id,
                        'order_id ' => $order->id,
                        'title' => $title,
                        'message' => $message,
                        'type' => $type
                    );
                    Notification::Create($notification_data);
                } else {
                    $title = 'Payment Failed';
                    $message = 'Payment Failed for Order ID' . $request->order_id;
                    // $params = [];
                    $store = Channel::whereUserId($order->store_id)->pluck('channel_id');
                    if (count($store) > 0) {
                        $params['include_player_ids'] = $store;
                        $contents = [
                            "en" => $message,
                        ];
                        $params['contents'] = $contents;
                        OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
                    }

                    $board_id = User::where('id', $order->store_id)->value('parent_id');
                    $board = Channel::whereUserId($board_id)->pluck('channel_id');
                    if (count($board) > 0) {
                        $params['include_player_ids'] = $board;
                        $contents = [
                            "en" => $message,
                        ];
                        $params['contents'] = $contents;
                        OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
                    }

                    if ($data) {
                        $notification_arr['type'] = $type;
                        $notification_arr['order_id'] = $request->order_id;
                        NotificationHelper::send($data->token, $title, $message, $data->type, $notification_arr);
                    }
                    $user_web_push = Channel::whereUserId($user_id)->pluck('channel_id');
                    if (count($user_web_push) > 0) {
                        $params['include_player_ids'] = $user_web_push;
                        $contents = [
                            "en" => $message,
                        ];
                        $params['contents'] = $contents;
                        OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
                    }


                    $notification_data = array(
                        'user_id' => $order->user_id,
                        'order_id ' => $order->id,
                        'title' => $title,
                        'message' => $message,
                        'type' => $type
                    );
                    Notification::Create($notification_data);
                }

                return response()->json([
                    'status' => true,
                    'message' => $message,
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'order status already updated',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Order data not found',
            ]);
        }
    }

    public function pay_by_card($id)
    {

        $user_id = request()->user()->id;

        $cart = Cart::whereUserId($user_id)->whereOrderId('0')->first();

        if ($cart) {

            $cart_products = CartProducts::whereCartId($cart->id)->with(['product'])->get();

            $sub_total = 0;
            if (auth()->user()->user_type == '1') {
                foreach ($cart_products as $key => $value) {
                    if ($value->stock > 0) {
                        $product_total = $value->qty * $value->product->current_price_business;
                        $sub_total += $product_total;
                    }
                }
            } else {
                foreach ($cart_products as $key => $value) {
                    if ($value->stock > 0) {
                        $product_total = $value->qty * $value->product->current_price_retail;
                        $sub_total += $product_total;
                    }
                }
            }

            if ($sub_total <= 0) {
                return ([
                    'status' => false,
                    'message' => 'Product out of stock'
                ]);
            }


            $data['sub_total'] = number_format((float) $sub_total, 2, '.', '');
            $tax = CommonHelper::ConfigGet('tax');
            if ($tax > 0) {
                $tax_amount = ($tax / 100) * $data['sub_total'];
                $data['tax'] = number_format((float) $tax_amount, 2, '.', '');
                $total = $data['sub_total'] + $tax_amount;
                $total = number_format((float) $total, 2, '.', '');
            } else {
                $total = $data['sub_total'];
            }

            $card = Card::where('user_id', $user_id)->where('id', $id)->first();
            if ($card) {

                $response = PaymentHelper::makeRequest($card->token, (float) $total);
                if ($response->ResponseCode == "00000") {
                    $data = array();
                    $data['GatewayTransID'] = $response->responseMessage->GatewayTransID;
                    $data['TransactionID'] = $response->TransactionID;
                    $data['response'] = $response;
                    return ([
                        'status' => true,
                        'data' => $data,
                        'message' => 'Payment completed successfully for $' . $total
                    ]);
                } else {
                    return ([
                        'status' => false,
                        'message' => $response->ResponseDescription
                    ]);
                }
            } else {
                return ([
                    'status' => false,
                    'message' => 'Card not found'
                ]);
            }


            return response([
                'status' => true,
                'message' => 'successfully',
                'data' => $data
            ]);
        } else {
            return response([
                'status' => false,
                'message' => 'No any product in cart',
            ]);
        }
    }
}