<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrdersProduct;
use App\Models\Devices;
use App\Models\Notification;
use App\Models\Orders;
use App\Models\User;
use NotificationHelper;
use DataTables;
use CommonHelper;
use Str;

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
            $data = Orders::select('*')->orderBy('id', 'desc')->get();
            $datat = Datatables::of($data);
            /* admin*/
            if ($request->has('pickup_method')) {
                $datat->filter(function ($instance) use ($request) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        if ($request->get('pickup_method') == "All") {
                            return true;
                        }
                        return $row['pickup_method'] == $request->get('pickup_method') ? true : false;
                    });

                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        if ($request->get('store_id') == "All") {
                            return true;
                        }
                        return $row['store_id'] == $request->get('store_id') ? true : false;
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
                ->editColumn('total', function ($row) {
                    return '$ ' . $row->total;
                })

                ->editColumn('customer_name', function ($row) {
                    $btn = '<a href="' . route('admin.user.show', $row['user_id']) . '" target="_blank" class="mr-2">' . $row['customer_name'] . '</a>';
                    return $btn;
                })
                ->editColumn('store_name', function ($row) {

                    $btn = '<a  href="' . route('admin.store.show', $row['store_id']) . '" target="_blank" class="mr-2">' . $row['store_name'] . '</a>';
                    return $btn;
                })
                ->editColumn('board_name', function ($row) {
                    $btn = '<a  href="' . route('admin.board.show', $row['board_id']) . '" target="_blank" class="mr-2">' . $row['board_name'] . '</a>';
                    return $btn;
                })
                ->addColumn('status', function ($row) {
                    $order_status = config('app.order_status');
                    return $order_status[$row['status']];
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
                    $btn = '<a href="' . route('admin.orders.show', $row['id']) . '" class="mr-2"><i class="fa fa-eye"></i></a>';
                    $btn .= '<button type="button" name="send_push" data-order-id="' . $row->id . '" data-user-id="' . $row->user_id . '" class="btn btn-primary btn-sm send_push" data-toggle="modal" data-target="#exampleModal">push</button>';

                    return $btn;
                })
                ->rawColumns(['status', 'store_name', 'board_name', 'customer_name', 'action'])
                ->make(true);
        } else {
            $columns = [
                //    ['data' => 'sequence', 'name' => 'sequence','title' => __("Sequence")],
                ['data' => 'id', 'name' => 'id', 'title' => "OrderId", 'searchable' => false],
                ['data' => 'store_name', 'name' => 'store_name', 'title' => "Store Name", 'searchable' => true],
                ['data' => 'customer_name', 'name' => 'customer_name', 'title' => "Customer Name", 'searchable' => true],
                ['data' => 'board_name', 'name' => 'board_name', 'title' => "Board Name", 'searchable' => true],
                ['data' => 'order_on_formatted', 'name' => 'order_on_formatted', 'title' => "Order Placed On", 'searchable' => false],
                ['data' => 'pickup_method', 'name' => 'pickup_method', 'title' => "Pickup Method", 'searchable' => true],
                ['data' => 'current_status', 'name' => 'current_status', 'title' => __("Order Status"), 'searchable' => true],
                ['data' => 'total', 'name' => 'total', 'title' => __("Total"), 'searchable' => false],
                ['data' => 'action', 'name' => 'action', 'title' => "Action", 'searchable' => false, 'orderable' => false]
            ];
            $params['dateTableFields'] = $columns;
            $params['dateTableUrl'] = route('admin.orders.index');
            $params['dateTableTitle'] = "Order Management";
            $params['dataTableId'] = time();
            $store_data = User::select('users.*')->join('users_group', 'users.id', '=', 'users_group.user_id')->orderBy('id', 'DESC')->where('users_group.group_id', 3)->get();
            return view('admin.pages.order.index', compact('store_data'), $params);
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
        $params['order'] = Orders::with(['store'])->find($id);
        // dd($params['order']);
        $params['products'] = OrdersProduct::where('order_id', $id)->with(['product'])->get();

        //dd($params['products']);
        $params['backUrl'] = route('admin.orders.index');
        return view('admin.pages.order.view', $params);
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
        // $data['title']=$request->title;
        // $data['message']=$request->message;
        $request = $_POST;
        $device = Devices::where('user_id', $request['user_id'])->first();
        $order = Orders::find($request['order_id']);
        if ($order['status'] == '1') {
            $type = 'order';
        } else if ($order['status'] == '2') {
            $type = 'order_accepted';
        } else if ($order['status'] == '3') {
            $type = 'order_rejected';
        } else if ($order['status'] == '4') {
            $type = 'order_prepared';
        } else if ($order['status'] == '5') {
            $type = 'order_prepared';
        } else if ($order['status'] == '6') {
            $type = 'order_delivered';
        } else {
            $type = 'order_delivered';
        }


        if ($device) {
            $notification_arr['order_id'] = $request['order_id'];
            $notification_arr['type'] = $type;
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
        $data['notification_data'] = Notification::Create($notification_data);

        $data['status'] = TRUE;
        $data['message'] = 'Notification sent successfully';

        return json_encode($data);
    }
}