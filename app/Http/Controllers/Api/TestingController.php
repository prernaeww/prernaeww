<?php



namespace App\Http\Controllers\Api;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\ContactUs;

use App\Models\Devices;
use App\Models\Orders;
use App\Models\User;

use CommonHelper;

use Validator;

use NotificationHelper;

use App\Models\Channel;
use OneSignal;


class TestingController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index(Request $request)

    {

        return response([

            'status' => true,

            'message' => 'Contact Successfully Save Data',

        ], 200);
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



        $validator = Validator::make($request->all(), [

            // 'user_id' => 'required',

            'email' => 'required',

            'message' => 'required',

            'image' => 'required',



        ]);



        if ($validator->fails()) {

            return response([

                'status' => false,

                'message' => $validator->errors()->all(),

            ], 200);
        }







        $contact = new ContactUs;

        $contact->user_id = $request->user()->id;

        $contact->email = $request->email;

        $contact->message = $request->message;

        if ($request->file('image')) {

            $imagename = rand() . '.' . $request->image->extension();

            $request->image->move(public_path('assets/images/contact'), $imagename);
        }

        $contact->image = $imagename;

        $contact->save();







        if (isset($contact)) {

            return response([

                'status' => true,

                'data' => $contact,

                'message' => 'Contact Successfully Save Data',

            ], 200);
        }
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

    public function push_notification(Request $request, $id)
    {
        $data = Devices::whereUserId($id)->first();
        NotificationHelper::send($data->token, 'ABC-TO-Go', 'send successfully', $data->type);
        return response([
            'status' => true,
            'message' => 'successfully',
        ]);
    }

    protected function history()
    {
        $user = request()->user();
        // dd($user);
        // $ordersDetails = $user->with(['orders', 'orders.order_products'])->where('id', $user->id)->first();
        // $ordersDetails = $user->load([
        //     'orders' => function ($query) {
        //         $query->load('order_products');
        //     }
        // ]);
        $usersData = $user->with([
            'orders' => function ($query) {
                $query->select([
                    'id',
                    'user_id',
                    'store_id',
                    'status',
                    'total',
                    'sub_total',
                    'tax',
                    'pickup_method',
                    'created_at',
                ]);
            },
            'orders.order_products' => function ($query) {
                $query->select([
                    'id',
                    'order_id',
                    'variant_id',
                    'price',
                    'qty',
                ]);
            },
            'orders.store' => function ($query) {
                $query->select([
                    'id',
                    // 'full_name',
                    'first_name',
                    'last_name',
                    // 'CONCAT(first_name, last_name) AS full_name',
                    'address'
                ]);
            },
            'orders.order_products.varient' => function ($query) {
                $query->select([
                    'id',
                    'product_id',
                    'measurement_id',
                    'quantity',
                    'picture',
                ]);
            },
            'orders.order_products.varient.product' => function ($query) {
                $query->select([
                    'id',
                    'name',
                ]);
            },
        ])->where('id', $user->id)->first();

        $ordersData = $usersData->orders;
        // dd($ordersData->toArray());
        $orders = [];
        foreach ($ordersData as $orderDetail) {

            $orderItemsData = $orderDetail['order_products'];
            $orderItems = [];
            foreach ($orderItemsData as $orderItemDetail) {
                $orderItem = $this->getOrderItemDetails($orderItemDetail);
                array_push($orderItems, $orderItem);
            }

            $order = [
                'id' => $orderDetail['id'],
                'status' => $orderDetail['status'],
                'total' => $orderDetail['total'],
                'sub_total' => $orderDetail['sub_total'],
                'tax' => $orderDetail['tax'],
                'pickup_method' => $orderDetail['pickup_method'],
                'ordered_at' => $orderDetail['created_at'],
                'store_name' => $orderDetail['store']['first_name'] . ' ' . $orderDetail['store']['last_name'],
                'store_address' => $orderDetail['store']['address'],
                'order_products' => $orderItems

            ];
            array_push($orders, $order);
        }

        return response()->json([
            'status' => true,
            'message' => 'list of all orders and its varients',
            'data' => $orders


        ]);
    }

    public function order_detail()
    {
        $user = request()->user();
        $requestData = request()->only(['order_id']);
        request()->validate([
            'order_id' => 'required|numeric'
        ]);
        $usersData = $user->with([
            'orders' => function ($query)  use ($requestData) {
                $query->select([
                    'id',
                    'user_id',
                    'store_id',
                    'status',
                    'reached',
                    'total',
                    'sub_total',
                    'tax',
                    'pickup_method',
                    'created_at',
                ])->where('id', $requestData['order_id']);
            },
            'orders.order_products' => function ($query) {
                $query->select([
                    'id',
                    'order_id',
                    'variant_id',
                    'price',
                    'qty',
                ]);
            },
            'orders.store' => function ($query) {
                $query->select([
                    'id',
                    // 'full_name',
                    'first_name',
                    'last_name',
                    'address'
                ]);
            },
            'orders.order_products.varient' => function ($query) {
                $query->select([
                    'id',
                    'product_id',
                    'measurement_id',
                    'quantity',
                    'picture',
                ]);
            },
            'orders.order_products.varient.product' => function ($query) {
                $query->select([
                    'id',
                    'name',
                ]);
            },
        ])->where('id', $user->id)->first();

        $ordersData = $usersData->orders;
        $orders = [];
        foreach ($ordersData as $orderDetail) {
            $orderItemsData = $orderDetail['order_products'];
            $orderItems = [];
            foreach ($orderItemsData as $orderItemDetail) {
                $orderItem = $this->getOrderItemDetails($orderItemDetail);
                array_push($orderItems, $orderItem);
            }
            $order = [
                'id' => $orderDetail['id'],
                'status' => $orderDetail['status'],
                'total' => $orderDetail['total'],
                'sub_total' => $orderDetail['sub_total'],
                'tax' => $orderDetail['tax'],
                'pickup_method' => $orderDetail['pickup_method'],
                'reached' => $orderDetail['reached'],
                'ordered_at' => $orderDetail['created_at'],
                'store_name' => $orderDetail['store']['first_name'] . ' ' . $orderDetail['store']['last_name'],
                'store_address' => $orderDetail['store']['address'],
                'order_products' => $orderItems

            ];
            array_push($orders, $order);
        }

        if ($orders && !empty($orders)) {
            return response()->json([
                'status' => true,
                'message' => 'details of individual order',
                'data' => $orders[0]
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'order not found',
            ]);
        }
    }

    public function toggle_im_here()
    {
        request()->validate([
            'order_id' => 'required',
            'is_here' => 'required|in:0,1'
        ]);
        $user = request()->user();
        $requestData = request()->only(['order_id', 'is_here']);
        $usersData = $user->with([
            'orders' => function ($query) use ($requestData) {
                $query->select([
                    'id',
                    'customer_id',
                    'is_here',
                ])->where('id', $requestData['order_id']);
            },
        ])->where('id', $user->id)->first();

        $order = $usersData->orders[0];
        $order->is_here = $requestData['is_here'];
        $order->save();

        return response()->json([
            'status' => true,
            'message' => 'updated successfully',
        ]);
    }

    private function getOrderItemDetails($orderItem)
    {
        // dd($orderItem);
        return [
            'id' => $orderItem['id'],
            'price' => $orderItem['price'],
            'qty' => $orderItem['qty'],
            'size' => $orderItem['varient']['quantity'] . ' ' . $orderItem['varient']['measurement'],
            'picture' => $orderItem['varient']['picture'],
            'name' => $orderItem['varient']['product']['name'],
        ];
    }

    public function test_update_order_status()
    {
        request()->validate([
            'order_id' => 'required',
            'status' => 'required|in:1,2'
        ]);
        $user = request()->user();
        $requestData = request()->only(['order_id', 'status']);

        $order = Orders::where('id', $requestData['order_id'])->first();
        if ($order) {
            Orders::where('id', $requestData['order_id'])->update([
                'status' => $requestData['status'],
            ]);

            $user_id = $order->user_id;
            $data = Devices::where('user_id', $user_id)->first();
            $notification_arr['type'] = 'order';
            $notification_arr['order_id'] = $requestData['order_id'];
            if ($requestData['status'] == 1 && isset($data)) {
                NotificationHelper::send($data->token, 'Payment Success', 'Payment Success for Order ID' . $requestData['order_id'], $data->type, $notification_arr);
            } else {
                NotificationHelper::send($data->token, 'Payment Failed', 'Payment Failed for Order ID' . $requestData['order_id'], $data->type, $notification_arr);
            }

            return response()->json([
                'status' => true,
                'message' => 'successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Order data not found',
            ]);
        }
    }

    public function send_web_push(Request $request, $user_id)
    {
        // $data = Channel::whereUserId($user_id)->pluck('channel_id');
        // $params = [];
        // if($data)
        // {
        //     $params['include_player_ids'] = $data;
        //     $contents = [ 
        //         "en" => "Some English Message",
        //     ]; 
        //     $params['contents'] = $contents;
        //     OneSignal::setParam('priority', 10)->sendNotificationCustom($params);
        // }
        // return response([
        //     'status' => true,
        //     'message' => 'successfully',
        // ]);

        $order_details = Orders::where('id', $user_id)->first();
        $order_id = $order_details->id;
        if ($order_details) {
            $reached = now();
            $params = [];
            if ($order_details->store_id) {
                $user = User::where('id', $order_details->store_id)->first();
                $message = 'Order #' . $order_id . ' has been successfully completed';

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
                    $message = 'Order #' . $order_id . ' has been successfully completed';

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

            $admin_data = User::select('users.*', 'users_group.group_id')->join('users_group', 'users.id', '=', 'users_group.user_id')->where('users_group.group_id', '=', '1')->first();
            if ($admin_data) {
                $message = 'Order #' . $order_id . ' has been successfully completed';

                $data = Channel::whereUserId($admin_data->id)->pluck('channel_id');
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

        return response([
            'status' => true,
            'message' => 'successfully',
        ]);
    }
}