<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Orders;
use App\Models\Meal;
use App\Models\OrdersDate;
use App\Models\School;
use App\Models\OrdersProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use CommonHelper; 
use BookeeyHelper; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Blacklist;
use DB;
use NotificationHelper;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all_orders(Request $request)
    {
        
       if ($request->ajax())
       {
            $data = Orders::select('*')->whereDate('to_date','>=',Carbon::now())->where('status',1)->with(['meal','customer'])->get();
            $datat = Datatables::of($data);
            if($request->has('school') && $request->has('date'))
            {
              $datat->filter(function ($instance) use ($request) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        if ($request->get('date') == "all") {
                            return true;
                        }
                        if(in_array($request->get('date'),$row['running_order']) && $row['school_id'] == $request->get('school')){
                            return true;
                        }else{
                            return false;
                        }
                    });
                });
            }

            if ($request->has('date')) {
                $datat->filter(function ($instance) use ($request) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        if ($request->get('date') == "all") {
                            return true;
                        }
                        if(in_array($request->get('date'),$row['running_order'])){
                            return true;
                        }else{
                            return false;
                        }
                    });
                });
            }
            
            if ($request->has('school')) {
                $datat->filter(function ($instance) use ($request) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        if ($request->get('school') == "all") {
                            return true;
                        }
                        return $row['school_id'] == $request->get('school') ? true : false;
                    });
                });
            }
            return $datat->addIndexColumn()
            ->addColumn('meal_name', function (Orders $orders) {
                return $orders->meal->name ;
            })
            ->addColumn('school_name', function (Orders $orders) {
                return $orders->customer ? $orders->customer->school_name : '' ;
            })
            ->addColumn('school_id', function (Orders $orders) {
                return $orders->customer ? $orders->customer->school : '' ;
            })
            ->addColumn('grade_name', function (Orders $orders) {
                return $orders->customer ? $orders->customer->grade_name : '' ;
            })
            ->addColumn('group', function (Orders $orders) {
                if(isset($orders->customer)){
                    if ($orders->customer->group == 1) {
                        $role = 'Admin';
                    } elseif ($orders->customer->group == 2) {
                        $role = 'Canteen';
                    } elseif ($orders->customer->group == 3) {
                        $role = 'Parent';
                    } elseif ($orders->customer->group == 4) {
                        $role = 'Student';
                    } elseif ($orders->customer->group == 5) {
                        $role = 'Employee';
                    } elseif ($orders->customer->group == 6) {
                        $role = 'Children';
                    } else {
                        $role = '';
                    }
                } else {
                    $role = '';
                }
                return $role;
            })
            ->editColumn('action', function ($row){
                $btn = '<a target="_blank" href="'.route('admin.order.all.view',$row['id']).'" class="mr-2"><i class="fa fa-eye"></i></a>';
                return $btn;
            })
            ->editColumn('is_diet_station', function ($row){
                $btn = $row['is_diet_station'];
                if($row['is_diet_station'] ==0)
                {
                    $res= $row['diet_station_reason'] == ""?[]:json_decode(trim($row['diet_station_reason']));
                    $reason = empty($res)?"":$res->result->error;
                    $btn = '<a href="javascript:void(0);" data-reason="'.$reason.'" data-orderId="'.$row['id'].'" data-transId="'.$row['transaction_id'].'" onclick="showModal(this);" class="mr-2 btn btn-danger btn-xs">Failed</a>';
                }
                return $btn;
            })
            ->editColumn('total', function ($row){
                return "KD ".$row['total'];
            })
            ->rawColumns(['meal_name', 'action','is_diet_station'])
            ->make(true);
       }
       else
       {
           $columns = [
               ['data' => 'id','name' => 'id','title' => "Id"], 
               ['data' => 'is_diet_station','name' => 'id','title' => "Is Diet Satation"], 
               ['data' => 'customer_name', 'name' => 'customer_name', 'title' => __("Customer Name"),'searchable'=>true],
               ['data' => 'school_name', 'name' => 'customer_id', 'title' => __("School Name")],
               ['data' => 'school_id', 'name' => 'customer_id', 'title' => __(""),'visible' => false],
               ['data' => 'grade_name', 'name' => 'customer_id', 'title' => __("Grade")],
               ['data' => 'group', 'title' => __("Role"), 'searchable' => false],
               ['data' => 'meal_name', 'name' => 'meal_name', 'title' => __("Meal Name"),'searchable'=>true],
               ['data' => 'from_date', 'name' => 'from_date', 'title' => __("From Date"),'searchable'=>false],
               ['data' => 'to_date', 'name' => 'to_date', 'title' => __("To Date"),'searchable'=>false],
               ['data' => 'total', 'name' => 'total', 'title' => __("Total"),'searchable'=>false],
               ['data' => 'order_on_formatted', 'name' => 'created_at', 'title' => __("Ordered On"),'searchable'=>false],
               ['data' => 'action','name' => 'action', 'title' => "Action",'searchable'=>false,'orderable'=>false]];
           $params['dateTableFields'] = $columns;
           $params['dateTableUrl'] = route('admin.order.all');
           $params['dateTableTitle'] = "All Orders";
           $params['dataTableId'] = time();
           $params['breadcrumb_name'] = 'all';
           $params['school'] = School::select('*')->where('status',1)->get();
           return view('admin.pages.order.index',$params);
       }
    }

    public function all_orders_view($id)
    {
        $params['pageTittle'] = "View" ;
        $order = Orders::with(['customer','canteen'])->find($id);

        
        if(isset($order)){
            $order = $order->toArray();
            $meals = Meal::whereId($order['meal_id'])->with(['category'])->first();
            
            $order_dates = OrdersDate::where('order_id',$id)->with(['order_products'])->get();
           
            $order['meal'] = $meals->toArray();
            $order['order_dates'] = $order_dates->toArray();
        }
        // dd($order);

        $params['order'] = $order;
        $params['breadcrumb_name'] = 'viewall';
        $params['backUrl'] = route('admin.order.all');
        return view('admin.pages.order.view',$params);
    }


    public function refund_test(Request $request)
    {
        $order_dates = [];
        $refund_amount = 0;
        $count_date = isset($request->ids) ? count($request->ids) : 0;
        if ($count_date > 0) {
            $order_dates = OrdersDate::whereIn('id', $request->ids)->with(['orders'])->first();
            $order_dates_count = OrdersDate::where('order_id', $order_dates->order_id)->with(['orders'])->get();
            $days = isset($order_dates_count) ? count($order_dates_count) : 0;
            $per_day_price = number_format($order_dates->orders->total/$days, 2);
            $refund_amount = $per_day_price * $count_date;
        }
        dd($refund_amount);
    }

    public function refund()
    {
        BookeeyHelper::refund();
    }


    public function get_user(Request $request)
    {
        // dd($request);
        $user_type=$request->user_type;
        $data = User::select('users.*', 'users_group.group_id', DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"))->join('users_group', 'users.id', '=', 'users_group.user_id')->where('users_group.group_id', '=', $user_type)->get()->toArray();
        // dd($data);
        echo json_encode($data);


    }

    public function get_meal(Request $request)
    {
        
      
        $user_id = $request->select_user;

        $user = User::where('id',$user_id)->first();
        // dd($user);             

        if($user->group == 3){
            $data['meals'] = Meal::with(['category'])->orderBy('id', 'DESC')->take(5)->get();
        }else{
            $school = School::where('id',$user->school)->first();
            if (isset($school)) {
                $data['meals'] = Meal::where('canteen_id', $school->canteen_id)->get();
            } else {
                $data['meals'] = [];
            }
        }
        
        $dates = [];
        $school = School::where('id',$user->school)->with(['holiday'])->first();
        // dd($school);
        if(isset($school)){
            $school = $school->toArray();
            $from_date = array_column($school['holiday'],'from_date');
            $to_date = array_column($school['holiday'],'to_date');
            $dates = array_merge($from_date,$to_date);
            $dates = array_unique($dates);

            usort($dates, function($a, $b) {
                return strtotime($a) - strtotime($b);
            });
        }

            $data['dates'] = $dates;            
        // dd($data);
        
        echo json_encode($data);


    }


    public function get_meal_details(Request $request)
    {
        $get_meal_details = $request->meal_type;
       $data['meals'] = Meal::where('id', $get_meal_details)->value('price');
       $data['tax'] = CommonHelper::ConfigGet('tax');
        // dd($meals);
        echo json_encode($data);
    }

    public function create_order()
    {
        // echo "under development...";exit;        
        $params['pageTittle'] = "View" ;        
        $params['breadcrumb_name'] = 'viewall';
        $params['backUrl'] = route('admin.order.all');
        return view('admin.pages.order.create_order',$params);
    }

    public function check_date_format($date)
    {
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) {
            return true;
        } else {
            return false;
        }
    }

    public function shuffleThis($list) { 

        if (!is_array($list)) return $list; 
        
        $keys = array_keys($list); 
        shuffle($keys); 
        $random = array(); 
        foreach ($keys as $key) { 
            $random[] = $list[$key]; 
        }
        return $random; 
    }

     public function book_meal(Request $request) {        
        // dd($request);
        $validator = Validator::make(request()->all(), [
            'user_id' => 'required',            
            'meal_id' => 'required',
            'calendar_constants' => 'required',
            'transaction_id' => 'required',
        ]);
        if (!$validator->fails()) {     
            // echo "here";exit;
            $date = explode(' to ',$request->calendar_constants);
            $from_date = $date[0];
            if (count($date) > 1) {
                $to_date = $date[1];
            } else
            {
                $to_date = $date[0];
            }
            $meal_id = $request->meal_id;
            $user_id = $request->user_id;
            // $from_date = $request->from_date;
            // $to_date = $request->to_date;       
            $from_date = $from_date;
            $to_date = $to_date;       
            $transaction_id = $request->transaction_id;


            $period = new CarbonPeriod($from_date, '24 hours', $to_date); // for create use 24 hours format later change format 
            $requested_dates = [];
            foreach($period as $item){
                array_push($requested_dates,$item->format("Y-m-d"));
            }

            // $dates = implode(',', $slots);

            // dd($dates);
            // print_r($request->dates);
            // $requested_dates = explode(',',$dates);
            // $requested_dates =  json_decode($request->dates,TRUE);
            // return $this->successResponse($requested_dates, __('Meals Detail'));
            foreach ($requested_dates as $key => $value) {
                if ($this->check_date_format($value) == false){
                    return $this->errorResponse(__('Date format is not correct'));
                }
            }
            usort($requested_dates, function($a, $b) {
                return strtotime($a) - strtotime($b);
            });
            $total_dates_array = count($requested_dates);
            // return $this->successResponse($requested_dates, __('Meals Detail'));
            $user = User::where('id',$request->user_id)->first(); 
            if (isset($user)) {
                $school = School::where('id',$user->school)->with(['holiday'])->first();
                if(isset($school)){
                    $school = $school->toArray();
                    $meals = Meal::whereId($request->meal_id)->with(['category'])->first();
                    if(isset($meals)){
                        $meals = $meals->toArray();
                        
                        $from_date = $requested_dates[0];
                        $to_date = $requested_dates[$total_dates_array - 1];

                            $category = [];
                            foreach ($meals['category'] as $mkey => $mvalue) {
                                $temp1['category_id'] = $mvalue['category_id'];
                                $temp1['item_number'] = $mvalue['items_number'];
                                $temp1['category_name'] = $mvalue['category_name'];
                                $temp1['category_image'] = $mvalue['category_image'];
                                array_push($category,$temp1);
                            }
                            $category_ids = array_column($meals['category'],'category_id');

                            $blacklist_product_ids = [];
                            $blacklist_products = Blacklist::where('user_id',$request->user_id)->get();
                            // print_r($blacklist_products);exit;
                            if(count($blacklist_products) > 0){
                                $blacklist_products = $blacklist_products->toArray();
                                $blacklist_product_ids = array_column($blacklist_products,'product_id');
                                $blacklist_product_ids = array_unique($blacklist_product_ids);
                            }
                            $products = Product::whereIn('category_id',$category_ids)->whereNotIn('id',$blacklist_product_ids)->get();

                            // print_r($products->toArray());exit;

                            if(count($products) > 0){
                                $products = $products->toArray();
                                foreach ($category as $ckey => $cvalue) {
                                    foreach ($products as $pkey => $pvalue) {
                                        if($cvalue['category_id'] == $pvalue['category_id']){
                                            $category[$ckey]['products'][] = $pvalue;
                                        }
                                    }
                                    if(!isset($category[$ckey]['products']))
                                    {
                                        return $this->errorResponse(__('Sorry, You have added all items in black list!'));
                                    }
                                }

                            }else{
                                return $this->errorResponse(__('Sorry, You have added all items in black list!'));
                            }                                                        
                            $dateRange = $requested_dates;
                            $check_fri_sat =[];
                            foreach ($dateRange as $date) {
                                $date = Carbon::createFromFormat('Y-m-d', $date);
                                $meal_dates[] = $date->format('Y-m-d');
                                $temp_data['date'] = $date->format('Y-m-d');
                                $temp_data['day'] =$date->format('l');

                                array_push($check_fri_sat,$temp_data);
                            }
                            $temp_date = [];
                            foreach ($check_fri_sat as $check_key => $check_value) {
                                if($check_value['day'] == 'Friday' || $check_value['day'] == 'Saturday'){
                                    $temp_date[] = $check_value['date'];
                                }
                            }
                            $from_date = array_column($school['holiday'],'from_date');
                            $to_date = array_column($school['holiday'],'to_date');
                            $holiday_dates = array_merge($from_date,$to_date);
                            $holiday_dates = array_unique($holiday_dates);
                            
                            $weekend_holiday = array_merge($holiday_dates,$temp_date);
                            $weekend_holiday = array_unique($weekend_holiday);
                            
                            usort($weekend_holiday, function($a, $b) {
                                return strtotime($a) - strtotime($b);
                            });
                            
                            //school holiday dates = $weekend_holiday

                            $dates = array_diff($meal_dates, $weekend_holiday);
                            $dates = array_values($dates);
                            
                            //Meal from to date  = $dates
                            
                            foreach ($dates as $key => $value) {
                                $days[] = Carbon::createFromFormat('Y-m-d', $value)->format('l');
                            }
                            $days = array_unique($days);
                            //Meal days  = $days

                            $weekdays = array('Sunday','Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

                            foreach ($weekdays as $wkey => $wvalue) {
                                if(!in_array($wvalue,$days)){
                                    unset($weekdays[$wkey]);
                                }
                            }

                            $total_days_price = count($dates) * $meals['price'];
                            $total_days_price = number_format((float)$total_days_price, 2, '.', '');
                            $tax = CommonHelper::ConfigGet('tax');
                            $tax = number_format((float)$tax, 2, '.', '');
                            // $total_day_tax = $total_days_price  + $tax;
                            $total_day_tax = $total_days_price;
                            $total_day_tax = number_format((float)$total_day_tax, 2, '.', '');

                            $day_wise_data = [];
                            foreach ($weekdays as $dkey => $dvalue) {
                                $day_list['day'] = $dvalue;
                                foreach ($category as $key => $value) {
                                    $day_list['category'][$key]['id'] = $value['category_id'];
                                    $day_list['category'][$key]['category_name'] = $value['category_name'];
                                    $day_list['category'][$key]['item_number'] = $value['item_number'];
                                    $day_list['category'][$key]['category_image'] = $value['category_image'];

                                    $shuffle_products = $this->shuffleThis($category[$key]['products']);
                                    
                                    $shuffle_products_array = array_slice($shuffle_products, 0, $value['item_number']);
                                    if(count($shuffle_products_array) < $value['item_number']){
                                        $count = count($shuffle_products_array);
                                        $final_product_array = [];
                                        for ($i=0; $i < $value['item_number'] ; $i++) { 
                                            $count = $count - 1;
                                            // $final_product_array = $shuffle_products_array;
                                            array_push($final_product_array,$shuffle_products_array[$count]);
                                            if($count <= 0){
                                                $count = count($shuffle_products_array);
                                            }
                                        }
                                        $day_list['category'][$key]['products'] = $final_product_array;
                                    }else{
                                        $day_list['category'][$key]['products'] = $shuffle_products_array;
                                    }
                                }
                                array_push($day_wise_data,$day_list);
                            }
                            // print_r($products);exit;
                            // $data['user'] = $user;
                            // $data['day_wise_data'] = $day_wise_data;
                            // $data['total'] = $total_day_tax; 
                            // $data['meal'] = $meals; 
                            // return $this->successResponse($data, __('Meals Detail'));                        
                        // }else{
                        //     return $this->errorResponse(__('You have already placed an order during this period of time'));
                        // }
                    }else{                    
                        return redirect()->route('admin.order.create')->with('error', 'Meals not found');
                    }
                }else{                
                    return redirect()->route('admin.order.create')->with('error', 'School not found');
                }
            } else {            
                return redirect()->route('admin.order.create')->with('error', 'User not found');
            }            
            // $requested_dates = explode(',',$request->dates);
            // $requested_dates =  json_decode($request->dates,TRUE);
            foreach ($requested_dates as $key => $value) {
                if ($this->check_date_format($value) == false){
                    return $this->errorResponse(__('Date format is not correct'));
                }
            }
            usort($requested_dates, function($a, $b) {
                return strtotime($a) - strtotime($b);
            });
            $total_dates_array = count($requested_dates);
            $user = User::where('id',$request->user_id)->with(['devices'])->first(); 
            if (isset($user)) {
                $user = $user->toArray();
                if($user['parent_id'] != 0){
                    $parent = User::where('id',$user['parent_id'])->with(['devices'])->first();
                    if (isset($parent)) {
                        $parent = $parent->toArray();
                    }
                }
                $school = School::where('id',$user['school'])->with(['holiday'])->first();
                if(isset($school)){
                    $school = $school->toArray();

                    $meals = Meal::whereId($request->meal_id)->first();
                    if (isset($meals)) {
                        $meals = $meals->toArray();
                        $meal_data =  $day_wise_data;
                        $dateRange = $requested_dates;
                        $check_fri_sat =[];
                        foreach ($dateRange as $date) {
                            $date = Carbon::createFromFormat('Y-m-d', $date);
                            $meal_dates[] = $date->format('Y-m-d');
                            $temp_data['date'] = $date->format('Y-m-d');
                            $temp_data['day'] =$date->format('l');

                            array_push($check_fri_sat,$temp_data);
                        }
                        $temp_date = [];
                        foreach ($check_fri_sat as $check_key => $check_value) {
                            if($check_value['day'] == 'Friday' || $check_value['day'] == 'Saturday'){
                                $temp_date[] = $check_value['date'];
                            }
                        }
                        
                        $from_date = array_column($school['holiday'],'from_date');
                        $to_date = array_column($school['holiday'],'to_date');
                        $holiday_dates = array_merge($from_date,$to_date);
                        $holiday_dates = array_unique($holiday_dates);
                        
                        $weekend_holiday = array_merge($holiday_dates,$temp_date);
                        $weekend_holiday = array_unique($weekend_holiday);
                        
                        usort($weekend_holiday, function($a, $b) {
                            return strtotime($a) - strtotime($b);
                        });
                        
                        //school holiday dates = $weekend_holiday

                        $dates = array_diff($meal_dates, $weekend_holiday);
                        $dates = array_values($dates);
                        
                        //Meal from to date  = $dates
                        
                        foreach ($dates as $key => $value) {
                            $days[] = Carbon::createFromFormat('Y-m-d', $value)->format('l');
                        }
                        
                        $meal_dates_days = [];
                        foreach ($dates as $key => $value) {
                            $temp['date'] = $value;
                            $temp['day'] = Carbon::createFromFormat('Y-m-d', $value)->format('l');
                            array_push($meal_dates_days,$temp);
                        }

                        $total_days_price = count($dates) * $meals['price'];
                        $total_days_price = number_format((float)$total_days_price, 2, '.', '');

                        $tax = CommonHelper::ConfigGet('tax');
                        $tax = number_format((float)$tax, 2, '.', '');

                        $total = $total_days_price + $tax;
                        $total = number_format((float)$total, 2, '.', '');

                        $order_data['customer_id'] = $request->user_id;
                        $order_data['canteen_id'] = $school['canteen_id'];
                        $order_data['meal_id'] = $request->meal_id;
                        $order_data['price'] = $meals['price'];
                        $order_data['sub_total'] = $total_days_price;
                        $order_data['total'] = $total;
                        $order_data['tax'] = $tax;
                        $order_data['from_date'] = $requested_dates[0];
                        $order_data['to_date'] = $requested_dates[$total_dates_array - 1];
                        $order_data['transaction_id'] = $transaction_id;

                        if(isset($request->special_instruction)){
                            $order_data['special_instruction'] =  $request->special_instruction;
                        }

                        $order_id = Orders::insertGetId($order_data);
                        $order_inserted_data = Orders::find($order_id);
                        // $order_id = 0;

                        foreach ($meal_dates_days as $mkey => $mvalue) {
                            $meal_dates_days[$mkey]['order_id'] = $order_id;
                        }
                        OrdersDate::insert($meal_dates_days);

                        $inserted_order_dates = OrdersDate::where('order_id',$order_id)->get()->toArray();

                        foreach ($inserted_order_dates as $ikey => $ivalue) {
                            foreach ($meal_dates_days as $mdkey => $mdvalue) {
                                $order_products =[];
                                if($ivalue['date'] == $mdvalue['date']){

                                    $order_products['order_id'] = $order_id;
                                    $order_products['date_id'] = $ivalue['id'];
                                    foreach ($meal_data as $meal_data_key => $meal_data_value) {
                                        if($mdvalue['day'] == $meal_data_value['day']){
                                            foreach ($meal_data_value['category'] as $ckey => $cvalue) {
                                                $order_products['category_id'] = $cvalue['id'];
                                                foreach ($cvalue['products'] as $pkey => $pvalue) {
                                                    $order_products['product_id'] = $pvalue['id'];
                                                    OrdersProduct::insert($order_products);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        //send url of payment

                        // BookeeyHelper::setTitle('Canteeny');
                        // BookeeyHelper::setDescription('Payment');
                        // BookeeyHelper::setMerchantID(CommonHelper::ConfigGet('bookeey_mer_id')); // Set the Merchant ID
                        // BookeeyHelper::setSecretKey(CommonHelper::ConfigGet('bookeey_mer_secretkey')); // Set the Secret Key
                        // BookeeyHelper::setOrderId($order_id); // Set Order ID - This should be unique for each transaction.
                        // BookeeyHelper::setAmount($order_inserted_data->total); // Set amount in KWD

                        // if($user['group'] == 6){
                        //     $fullname = $parent['last_name'].' '.$user['first_name']; 
                        //     $phone = $parent['phone'];
                        //     $notification_id = $parent['id'];
                        //     $device_token = $parent['devices']['token'];
                        // }else{
                        //     $fullname = $user['first_name'].' '.$user['last_name']; 
                        //     $phone = $user['phone'];
                        //     $notification_id = $user['id'];
                        //     $device_token = $user['devices']['token'];
                        // }
                        // BookeeyHelper::setPayerName($fullname);  // Set Payer Name
                        // BookeeyHelper::setPayerPhone($phone);  // Set Payer Phone Numner
                        // BookeeyHelper::setSelectedPaymentOption('knet');
                        // // setSelectedPaymentOption('credit');
                        // // setSelectedPaymentOption('Bookeey');
                        // // setSelectedPaymentOption('amex');

                        // $transactionDetails[0]['SubMerchUID'] = CommonHelper::ConfigGet('bookeey_mer_id');
                        // $transactionDetails[0]['Txn_AMT'] = $order_inserted_data->total;
                        $response = true;
                        // $response = BookeeyHelper::initiatePayment($transactionDetails);
                        if ($response) {

                            
                            $push_title = 'Order Confirmed'; 
                            $push_data = array();
                            $push_data['order_id'] = $order_id;
                            $push_data['message'] = "Your order has been confirmed";
                            $push_type = 'order_confirmed';

                            $data['status'] = true;
                            $data['order_id'] = $order_id;
                            // $data['payment_url'] = $response['url'];
                            // echo json_encode($data);
                            // dd($data);
                            return redirect()->route('admin.order.create')->with('success', 'Your order has been placed successfully.');
                        } else {
                            Orders::where('id',$order_id)->update(array("status" => 3));
                            //order_failed                            
                            return redirect()->route('admin.order.create')->with('error', $response['error']);
                        }
                    }else{                        
                        return redirect()->route('admin.order.create')->with('error', 'Meals not found');
                    }
                }else{                    
                    return redirect()->route('admin.order.create')->with('error', 'school not found');
                }
            } else {                
                return redirect()->route('admin.order.create')->with('error', 'user not found');
            }
        }
        return redirect()->route('admin.order.create')->with('error', $validator->messages());
    }    
}