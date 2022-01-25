<?php

namespace App\Http\Controllers\store;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\OrdersProduct;
use App\Models\User;
use App\Models\StoresProduct;
use App\Models\Orders;
use App\Models\Devices;
use App\Models\Notification;
use NotificationHelper;
use DataTables;
use CommonHelper;
use App\Mail\OrderRejectMail;
use App\Mail\OrderAcceptMail;
use App\Mail\OrderDeliveredMail;
use App\Mail\OrderReadyToPickUpMail;
// use App\Mail\OrderRejectedMail;
use Mail;
use PaymentHelper;

use App\Models\Channel;
use OneSignal;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $login_id =  Auth::user()->id;

            // dd($login_id);
            $data = Orders::select('*')->orderBy('id', 'desc')->where('store_id', $login_id)->get();
            // dd($data);
            $datat = Datatables::of($data);
            if ($request->pickup_method || $request->status) {
                $datat->filter(function ($instance) use ($request) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        if ($request->get('pickup_method') == "All") {
                            return true;
                        }
                        return $row['pickup_method'] == $request->get('pickup_method') ? true : false;
                    });

                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        if ($request->get('status') == "All") {
                            return true;
                        }
                        return $row['status'] == $request->get('status') ? true : false;
                    });
                });
            }
            return $datat->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $order_status = config('app.order_status');
                    return $order_status[$row['status']];
                })
                ->editColumn('total', function ($row) {
                    return '$ ' . $row->total;
                })
                ->editColumn('pickup_method', function ($row) {
                    if ($row['pickup_method'] == 1) {
                        return 'InStore';
                    } elseif ($row['pickup_method'] == 2) {
                        return 'CurbSide';
                    } else {
                        return '-';
                    }
                })


                ->editColumn('action', function ($row) {
                    $btn = '<a href="' . route('store.orders.show', $row['id']) . '" class="mr-2"><i class="fa fa-eye"></i></a>';

                    $btn .= '<span class="status-btns">';
                    if ($row['status'] == '1') {
                        $btn .= '<a href="javascript:void(0);" data-id="' . $row["id"] . '" data-status="accept" data-popup="tooltip" onclick="accept_reject(this);return false;"><label for="" class="badge badge-success p-1 mr-1 cursor-pointer order-status">Accept</label></a>';
                        $btn .= '<a href="javascript:void(0);" data-id="' . $row["id"] . '" data-status="reject" data-popup="tooltip" onclick="accept_reject(this);return false;"><label for="" class="badge badge-danger p-1 mr-1 cursor-pointer order-status">Reject</label></a>';
                    } else if ($row['status'] == '2') {
                        $btn .= '<a href="javascript:void(0);" data-id="' . $row["id"] . '" data-status="ready-to-pickup" data-popup="tooltip" onclick="accept_reject(this);return false;"><label for="" class="badge badge-success p-1 mr-1 cursor-pointer order-status">Ready To Pickup</label></a>';
                    } else if ($row['status'] == '4' || $row['status'] == '5') {
                        $btn .= '<a href="javascript:void(0);" data-id="' . $row["id"] . '" data-status="delivered" data-popup="tooltip" onclick="accept_reject(this);return false;"><label for="" class="badge badge-success p-1 mr-1 cursor-pointer order-status">Delivered</label></a>';
                    } else { }

                    $btn .= '</span>';

                    $btn .= '<a href="javascript:void(0);" name="send_push" data-order-id="' . $row->id . '" data-user-id="' . $row->user_id . '" class="send_push" data-toggle="modal" data-target="#exampleModal"><label for="" class="badge badge-warning p-1 mr-1 cursor-pointer">Send Push</label></a>';


                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        } else {
            $columns = [
                //    ['data' => 'sequence', 'name' => 'sequence','title' => __("Sequence")],
                ['data' => 'id', 'name' => 'id', 'title' => "OrderId", 'searchable' => false],

                ['data' => 'customer_name', 'name' => 'customer_name', 'title' => "Customer Name", 'searchable' => true],
                ['data' => 'order_on_formatted', 'name' => 'order_on_formatted', 'title' => "Order Placed On", 'searchable' => false],
                ['data' => 'pickup_method', 'name' => 'pickup_method', 'title' => "Pickup Method", 'searchable' => true],
                ['data' => 'current_status', 'name' => 'current_status', 'title' => __("Order Status"), 'searchable' => false],
                ['data' => 'total', 'name' => 'total', 'title' => __("Total"), 'searchable' => false],
                ['data' => 'action', 'name' => 'action', 'title' => "Action", 'searchable' => false, 'orderable' => false]
            ];
            $params['dateTableFields'] = $columns;
            $params['dateTableUrl'] = route('store.orders.index');
            $params['dateTableTitle'] = "Order Management";
            $params['dataTableId'] = time();

            return view('store.pages.order.index', $params);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $params['pageTittle'] = "View Order";
        // $user = User::where('id',$id)->first();
        // $params['banner'] = Banner::where('user_id',$id)->get();
        $params['order'] = Orders::find($id);
        $params['products'] = OrdersProduct::where('order_id', $id)->with(['product'])->get();
        $params['backUrl'] = route('admin.orders.index');
        return view('store.pages.order.view', $params);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function order_notification_customer()
    {

        $request = $_POST;
        $device = Devices::where('user_id', $request['user_id'])->first();
        // dd($device)
        $type = 'order';

        $orders_arr = [];
        $orders_arr = Orders::where('id', $request['order_id'])->with(['order_products.product', 'store'])->first();
        foreach ($orders_arr->order_products as $p_key => $p_value) {
            $product_total = $p_value->price * $p_value->qty;
            $orders_arr['order_products'][$p_key]['product_total'] = number_format((float) $product_total, 2, '.', '');
        }

        if ($orders_arr['status'] == '1') {
            $type = 'order';
        } else if ($orders_arr['status'] == '2') {
            $type = 'order_accepted';
        } else if ($orders_arr['status'] == '3') {
            $type = 'order_rejected';
        } else if ($orders_arr['status'] == '4') {
            $type = 'order_prepared';
        } else if ($orders_arr['status'] == '5') {
            $type = 'order_prepared';
        } else if ($orders_arr['status'] == '6') {
            $type = 'order_delivered';
        } else {
            $type = 'order_delivered';
        }

        if ($device) {
            $notification_arr['type'] = $type;
            $notification_arr['order_id'] = $request['order_id'];
            // $notification_arr['order'] = $orders_arr->toArray();            
            $notification = NotificationHelper::send($device->token, $request['title'], $request['message'], $device->type, $notification_arr);


            $data['notification'] = $notification;
        }
        $user_web_push = Channel::whereUserId($request['user_id'])->pluck('channel_id');
        if (count($user_web_push) > 0) {
            $params['include_player_ids'] = $user_web_push;
            $contents = [
                "en" => $request['message'],
            ];
            $params['contents'] = $contents;
            OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
        }

        $notification_data = array(
            'user_id' => $request['user_id'],
            'order_id' => $request['order_id'],
            'title' => $request['title'],
            'message' => $request['message'],
            'type' => $type
        );
        Notification::Create($notification_data);

        $data['device'] = $device;
        $data['status'] = TRUE;
        $data['message'] = 'Notification sent successfully';

        return json_encode($data);
    }

    public function accept()
    {
        $request = $_POST;
        $order = Orders::whereId($request['id'])->first();
        if ($order->status == '1') {

            $order->status = '2';
            $order->store_accepted = date('Y-m-d H:i:s');
            $order->save();

            $device = Devices::where('user_id', $order->user_id)->first();
            $user = User::find($order->user_id);
            $title = "Order Accepted";
            $message = 'Order #' . $request['id'] . ' has been accepted by store';
            $type = 'order_accepted';
            if ($device) {
                $notification_arr['type'] = $type;
                $notification_arr['order_id'] = $request['id'];
                $notification = NotificationHelper::send($device->token, $title, $message, $device->type, $notification_arr);
                $data['notification'] = $notification;
            }

            $user_web_push = Channel::whereUserId($order->user_id)->pluck('channel_id');
            if (count($user_web_push) > 0) {
                $params['include_player_ids'] = $user_web_push;
                $contents = [
                    "en" => $message,
                ];
                $params['contents'] = $contents;
                OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
            }

            NotificationHelper::send_sms($message, $user->phone);

            $notification_data = array(
                'user_id' => $order->user_id,
                'order_id' => $request['id'],
                'title' => $title,
                'message' => $message,
                'type' => $type
            );
            Notification::Create($notification_data);

            $login_id =  Auth::user()->id;
            $store = User::find($login_id);
            $user = User::find($order->user_id);
            $board_id = $store->parent_id;
            $board = User::find($board_id);

            $admin_email = CommonHelper::ConfigGet('from_email');

            // send mail
            $order_id = $request['id'];

            $message = 'Hello ' . $user->first_name . ' ' . $user->last_name . ', Your order #' . $order_id . ' has been accepted by ' . $store->first_name . ' ' . $store->last_name;

            // send mail Customer
            Mail::to($user->email)->send(new OrderAcceptMail('Order Accepted', $message, config('app.address1'), config('app.address2')));

            $message = 'Hello, Order #' . $order_id . ' has been accepted by ' . $store->first_name . ' ' . $store->last_name;

            // send mail board
            Mail::to($board->email)->send(new OrderAcceptMail('Order Accepted', $message, config('app.address1'), config('app.address2')));

            $data = Channel::whereUserId($board_id)->pluck('channel_id');
            if (count($data) > 0) {
                $params['include_player_ids'] = $data;
                $contents = [
                    "en" => $message,
                ];
                $params['contents'] = $contents;
                OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
            }


            $message = 'Hello, Order #' . $order_id . ' has been accepted by ' . $store->first_name . ' ' . $store->last_name;

            // send mail admin
            Mail::to($admin_email)->send(new OrderAcceptMail('Order Accepted', $message, config('app.address1'), config('app.address2')));


            // Send web push to admin & board code pending

            $data['admins'] = $admin_email;
            $data['order'] = $order;
            $data['message'] = 'Accepted successfully';
            $data['status'] = TRUE;
        } else {
            $data['message'] = 'Something went wrong with status of order';
            $data['status'] = FALSE;
        }

        echo json_encode($data);
    }

    public function reject()
    {
        $request = $_POST;
        $order = Orders::whereId($request['id'])->with(['order_products'])->first();
        if ($order->status == '1') {

            if ($order->transaction_id != '' && $order->gateway_trans_id != '') {
                $response = PaymentHelper::makeRefund($order->transaction_id, $order->gateway_trans_id, $order->total);
                if ($response->ResponseCode == "00000") {
                    $GatewayTransID = $response->responseMessage->GatewayTransID;
                    $TransactionID = $response->TransactionID;

                    // Code

                    foreach ($order->order_products as $key => $value) {
                        // $store_variant = StoresProduct::where('variant_id', $value['variant_id'])->where('user_id', $order->store_id)->where('product_id', $value['product_id'])->first();
                        $store_variant = StoresProduct::where('user_id', $order->store_id)->where('product_id', $value['product_id'])->first();
                        if ($store_variant) {
                            $store_variant->stock = $store_variant->stock + $value['qty'];
                            $store_variant->save();
                        }
                    }

                    $order->status = '3';
                    $order->save();

                    // $orders_arr = Orders::where('id', $request['id'])->with(['order_products.product', 'store'])->orderBy('id', 'DESC')->first();
                    // foreach ($orders_arr->order_products as $p_key => $p_value) {
                    //     $product_total = $p_value->price * $p_value->qty;
                    //     $orders_arr['order_products'][$p_key]['product_total'] = number_format((float)$product_total, 2, '.', '');
                    // }

                    $device = Devices::where('user_id', $order->user_id)->first();
                    $user = User::find($order->user_id);
                    $title = "Order Rejected";
                    $message = 'Order #' . $request['id'] . ' has been rejected by store';
                    $type = 'order_rejected';
                    if ($device) {
                        $notification_arr['type'] = $type;
                        $notification_arr['order_id'] = $request['id'];
                        $notification = NotificationHelper::send($device->token, $title, $message, $device->type, $notification_arr);

                        $data['notification'] = $notification;
                    }
                    $user_web_push = Channel::whereUserId($order->user_id)->pluck('channel_id');
                    if (count($user_web_push) > 0) {
                        $params['include_player_ids'] = $user_web_push;
                        $contents = [
                            "en" => $message,
                        ];
                        $params['contents'] = $contents;
                        OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
                    }

                    NotificationHelper::send_sms($message, $user->phone);

                    $notification_data = array(
                        'user_id' => $order->user_id,
                        'order_id' => $request['id'],
                        'title' => $title,
                        'message' => $message,
                        'type' => $type
                    );
                    Notification::Create($notification_data);

                    $login_id =  Auth::user()->id;
                    $store = User::find($login_id);
                    $user = User::find($order->user_id);
                    $board_id = $store->parent_id;
                    $board = User::find($board_id);

                    $admin_email = CommonHelper::ConfigGet('from_email');

                    // send mail
                    $order_id = $request['id'];

                    $message = 'Hello ' . $user->first_name . ' ' . $user->last_name . ', Your order #' . $order_id . ' has been rejected by ' . $store->first_name . ' ' . $store->last_name;

                    // send mail Customer
                    Mail::to($user->email)->send(new OrderRejectMail('Order Rejected', $message, config('app.address1'), config('app.address2')));

                    $message = 'Hello, Order #' . $order_id . ' has been rejected by ' . $store->first_name . ' ' . $store->last_name;

                    // send mail board
                    Mail::to($board->email)->send(new OrderRejectMail('Order Rejected', $message, config('app.address1'), config('app.address2')));

                    $message = 'Hello, Order #' . $order_id . ' has been rejected by ' . $store->first_name . ' ' . $store->last_name;

                    $data = Channel::whereUserId($board_id)->pluck('channel_id');
                    if (count($data) > 0) {
                        $params['include_player_ids'] = $data;
                        $contents = [
                            "en" => $message,
                        ];
                        $params['contents'] = $contents;
                        OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
                    }

                    // send mail admin
                    Mail::to($admin_email)->send(new OrderRejectMail('Order Rejected', $message, config('app.address1'), config('app.address2')));

                    // Send web push to admin & board code pending

                    $data['admins'] = $admin_email;
                    $data['order'] = $order;
                    $data['message'] = 'Rejected successfully and customer got refund.';
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
        } else {
            $data['message'] = 'Something went wrong with status of order';
            $data['status'] = FALSE;
        }

        echo json_encode($data);
    }

    public function readytopickup()
    {
        $request = $_POST;
        $order = Orders::whereId($request['id'])->with(['order_products'])->first();
        if ($order->status == '2') {

            //Refund to customer code write here..

            $order->status = '4';
            $order->save();

            $device = Devices::where('user_id', $order->user_id)->first();
            $title = "Order Prepared";
            $message = 'Order #' . $request['id'] . ' is ready for pickup';
            $type = 'order_prepared';
            if ($device) {
                $notification_arr['type'] = $type;
                $notification_arr['order_id'] = $request['id'];
                $notification = NotificationHelper::send($device->token, $title, $message, $device->type, $notification_arr);


                $data['notification'] = $notification;
            }
            $user_web_push = Channel::whereUserId($order->user_id)->pluck('channel_id');
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
                'order_id' => $request['id'],
                'title' => $title,
                'message' => $message,
                'type' => $type
            );
            Notification::Create($notification_data);

            // send mail
            $order_id = $request['id'];


            // Send web push to admin & board code pending

            // send mail Customer
            $user = User::find($order->user_id);
            Mail::to($user->email)->send(new OrderReadyToPickUpMail('Order ready for pickup', $message, config('app.address1'), config('app.address2')));

            $data['order'] = $order;
            $data['message'] = 'Order status updated as ready for pickup successfully';
            $data['status'] = TRUE;
        } else {
            $data['message'] = 'Something went wrong with status of order';
            $data['status'] = FALSE;
        }

        echo json_encode($data);
    }

    public function delivered()
    {
        $request = $_POST;
        $order = Orders::whereId($request['id'])->first();
        if ($order->status == '4' || $order->status == '5') {

            $order->status = '6';
            $order->save();

            $device = Devices::where('user_id', $order->user_id)->first();
            $user = User::find($order->user_id);
            $title = "Order Delivered";
            $message = 'Order #' . $request['id'] . ' has been delivered';
            $type = 'order_delivered';
            if ($device) {
                $notification_arr['type'] = $type;
                $notification_arr['order_id'] = $request['id'];
                $notification = NotificationHelper::send($device->token, $title, $message, $device->type, $notification_arr);


                $data['notification'] = $notification;
            }
            $user_web_push = Channel::whereUserId($order->user_id)->pluck('channel_id');
            if (count($user_web_push) > 0) {
                $params['include_player_ids'] = $user_web_push;
                $contents = [
                    "en" => $message,
                ];
                $params['contents'] = $contents;
                OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
            }
            NotificationHelper::send_sms($message, $user->phone);

            $notification_data = array(
                'user_id' => $order->user_id,
                'order_id' => $request['id'],
                'title' => $title,
                'message' => $message,
                'type' => $type
            );
            Notification::Create($notification_data);

            $login_id =  Auth::user()->id;
            $store = User::find($login_id);
            $user = User::find($order->user_id);
            $board_id = $store->parent_id;
            $board = User::find($board_id);

            $admin_email = CommonHelper::ConfigGet('from_email');

            // send mail
            $order_id = $request['id'];

            $message = 'Hello ' . $user->first_name . ' ' . $user->last_name . ', Your order #' . $order_id . ' has been delivered by ' . $store->first_name . ' ' . $store->last_name;

            // send mail Customer
            Mail::to($user->email)->send(new OrderDeliveredMail('Order Delivered', $message, config('app.address1'), config('app.address2')));

            $message = 'Hello, Order #' . $order_id . ' has been delivered by ' . $store->first_name . ' ' . $store->last_name;

            // send mail board
            Mail::to($board->email)->send(new OrderDeliveredMail('Order Delivered', $message, config('app.address1'), config('app.address2')));

            $message = 'Hello, Order #' . $order_id . ' has been delivered by ' . $store->first_name . ' ' . $store->last_name;

            // send mail admin
            Mail::to($admin_email)->send(new OrderDeliveredMail('Order Delivered', $message, config('app.address1'), config('app.address2')));


            // Send web push to admin & board code pending

            $data['admins'] = $admin_email;
            $data['order'] = $order;
            $data['message'] = 'Delivered successfully';
            $data['status'] = TRUE;
        } else {
            $data['message'] = 'Something went wrong with status of order';
            $data['status'] = FALSE;
        }

        echo json_encode($data);
    }
}