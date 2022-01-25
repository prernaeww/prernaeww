<?php

namespace App\Http\Controllers\Canteen;

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
use Illuminate\Support\Facades\Auth;
use DataTables;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function all(Request $request)
    {
       if ($request->ajax())
       {
            $login_id =  Auth::user()->id;
            $data = Orders::select('*')->where('canteen_id',$login_id)->whereDate('to_date','>=',Carbon::now())->where('status',1)->with(['meal','customer'])->get();
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
            
            return $datat->addIndexColumn()
            ->addColumn('meal_name', function (Orders $orders) {
                return $orders->meal->name ;
            })
            ->addColumn('school_name', function (Orders $orders) {
                return $orders->customer ? $orders->customer->school_name : '' ;
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
                $btn = '<a target="_blank" href="'.route('canteen.order.all.view',$row['id']).'" class="mr-2"><i class="fa fa-eye"></i></a>';
                return $btn;
            })
            ->editColumn('total', function ($row){
                return "KD ".$row['total'];
            })
            ->rawColumns(['meal_name', 'action'])
            ->make(true);            
       }
       else
       {
           $columns = [
               ['data' => 'id','name' => 'id','title' => "Id"], 
               ['data' => 'customer_name', 'name' => 'customer_id', 'title' => __("Customer Name")],
               ['data' => 'school_name', 'name' => 'customer_id', 'title' => __("School Name")],               
               ['data' => 'grade_name', 'name' => 'customer_id', 'title' => __("Grade")],
               ['data' => 'group', 'title' => __("Role"), 'searchable' => false],
               ['data' => 'meal_name', 'name' => 'meal_id', 'title' => __("Meal Name")],
               ['data' => 'from_date', 'name' => 'from_date', 'title' => __("From Date"),'searchable'=>false],
               ['data' => 'to_date', 'name' => 'to_date', 'title' => __("To Date"),'searchable'=>false],
               ['data' => 'total', 'name' => 'total', 'title' => __("Total"),'searchable'=>false],
               ['data' => 'order_on_formatted', 'name' => 'created_at', 'title' => __("Ordered On")],
               ['data' => 'action','name' => 'action', 'title' => "Action",'searchable'=>false,'orderable'=>false]];
           $params['dateTableFields'] = $columns;
           $params['dateTableUrl'] = route('canteen.order.all');
           $params['dateTableTitle'] = "All Orders";
           $params['dataTableId'] = time();
           $params['breadcrumb_name'] = 'all';
           $params['school'] = School::select('*')->where('status',1)->get();
           return view('canteen.pages.order.index',$params);
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
        $params['backUrl'] = route('canteen.order.all');
        return view('canteen.pages.order.view',$params);
    }

    public function inprocess(Request $request)
    {
        
       if($request->ajax())
       {
            $login_id =  Auth::user()->id;
            $data = Orders::select('*')->where('canteen_id',$login_id)->whereDate('to_date','>=',Carbon::now())->where('status',1)->with(['meal','customer'])->get();
            $datat = Datatables::of($data);
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
            // if ($request->has('school')) {
            //     $datat->filter(function ($instance) use ($request) {
            //         $instance->collection = $instance->collection->filter(function ($row) use ($request) {
            //             if ($request->get('school') == "all") {
            //                 return true;
            //             }
            //             return $row['school_id'] == $request->get('school') ? true : false;
            //         });
            //     });
            // }
            return $datat->addIndexColumn()
            ->addColumn('meal_name', function (Orders $orders) {
                return '<a href="' . route("canteen.meal.show", $orders->meal_id) . '" >' . $orders->meal->name . '</a>';
            })
            ->addColumn('school_name', function (Orders $orders) {
                return $orders->customer ? $orders->customer->school_name : '' ;
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
                $btn = '<a href="'.route('canteen.order.inprocess.view',$row['id']).'" class="mr-2"><i class="fa fa-eye"></i></a>';
                return $btn;
            })
            ->editColumn('total', function ($row){
                return "KD ".$row['total'];
            })
            ->rawColumns(['meal_name', 'action'])
            ->make(true);
       }
       else
       {
           $columns = [
               ['data' => 'id','name' => 'id','title' => "Id"], 
               ['data' => 'customer_name', 'name' => 'customer_id', 'title' => __("Customer Name")],
               ['data' => 'school_name', 'name' => 'customer_id', 'title' => __("School Name")],
               ['data' => 'grade_name', 'name' => 'customer_id', 'title' => __("Grade")],
               ['data' => 'group', 'title' => __("Role"), 'searchable' => false],
               ['data' => 'meal_name', 'name' => 'meal_id', 'title' => __("Meal Name")],
               ['data' => 'from_date', 'name' => 'from_date', 'title' => __("From Date")],
               ['data' => 'to_date', 'name' => 'to_date', 'title' => __("To Date")],
               ['data' => 'total', 'name' => 'total', 'title' => __("Total")],
               ['data' => 'order_on_formatted', 'name' => 'created_at', 'title' => __("Ordered On")],
               ['data' => 'action','name' => 'action', 'title' => "Action",'searchable'=>false,'orderable'=>false]];
           $params['dateTableFields'] = $columns;
           $params['dateTableUrl'] = route('canteen.order.inprocess');
           $params['dateTableTitle'] = "Current Orders";
           $params['dataTableId'] = time();
           $params['breadcrumb_name'] = 'inprocess';
           $params['school'] = School::select('*')->where('status',1)->get();
           return view('canteen.pages.order.index',$params);
       }
    }

    public function inprocess_view($id)
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
        $params['breadcrumb_name'] = 'viewinprocess';
        $params['backUrl'] = route('canteen.order.inprocess');
        return view('canteen.pages.order.view',$params);
    }
    public function item_update(Request $request)
    {
        $order_update['product_id'] = $request->product;
        OrdersProduct::where('id',$request->product_id)->update($order_update);
        return redirect()->back()->with('success','Item updated successfully.');;
    }
    public function date_update(Request $request)
    {
        $order_data =  OrdersDate::where('id',$request->order_dates_id)->with(['order_products'])->where('status','!=',4)->first();
        if (isset($order_data)) {
            $order_data = $order_data->toArray();
            $order_update = [];
            $order_update['status'] = 4;
            OrdersDate::where('id', $request->order_dates_id)->update($order_update);

            $insert_data['order_id'] = $request->order_id;
            $insert_data['date'] = $request->date;
            $insert_data['day'] = $request->day;
            $id = OrdersDate::insertGetId($insert_data);
            
            $temp = $insert_product =  [];
            $temp['order_id'] = $request->order_id;
            $temp['date_id'] = $id;
            foreach ($order_data['order_products'] as $key => $value) {
                $temp['category_id'] = $value['category_id'];
                $temp['product_id'] = $value['product_id'];
                array_push($insert_product, $temp);
            }
            OrdersProduct::insert($insert_product);
        }
        return redirect()->route('canteen.order.inprocess.view', [$request->order_id])->with('success','Date updated successfully.');
    }
    public function inprocess_edit($id)
    {
        $params['pageTittle'] = "Edit" ;
        $order_dates = OrdersDate::where('id',$id)->with(['order_products'])->first();
        // dd($order_dates);
        if(isset($order_dates)){
            $order_dates = $order_dates->toArray();
            $order = Orders::with(['customer','canteen','order_dates'])->find($order_dates['order_id']);
            $order = $order->toArray();
            $school = School::where('id',$order['customer']['school'])->with(['holiday'])->first();
            if (isset($school)) {
                $school = $school->toArray();
                $final_dates = $dates = $order_dates_all = [];

                $from_date = array_column($school['holiday'], 'from_date');
                $to_date = array_column($school['holiday'], 'to_date');
                $dates = array_merge($from_date, $to_date);
                $dates = array_unique($dates);
                
                usort($dates, function ($a, $b) {
                    return strtotime($a) - strtotime($b);
                });
               
                $order_dates_all = array_column($order['order_dates'],'date');
                
                $final_dates = array_merge($dates, $order_dates_all);
                $final_dates = array_unique($final_dates);

                usort($final_dates, function($a, $b) {
                    return strtotime($a) - strtotime($b);
                });

            }

            $products = Product::where('canteen_id',$school['canteen_id'])->get();
            if(count($products) > 0){
                $products = $products->toArray();
            }
        }
        // dd($products);
        $params['order_dates'] = $order_dates;
        $params['order'] = $order;
        $params['products'] = $products;
        $params['hide_dates'] = $final_dates;
        $params['breadcrumb_name'] = 'viewinprocess';
        //  dd($params);
        return view('canteen.pages.order.put',$params);
    }
    public function completed_view($id)
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
        $params['order'] = $order;
        $params['breadcrumb_name'] = 'viewcompleted';
        $params['backUrl'] = route('canteen.order.completed');
        return view('canteen.pages.order.view',$params);
    }
    public function fail_view($id)
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
        $params['order'] = $order;
        $params['breadcrumb_name'] = 'viewfail';
        $params['backUrl'] = route('canteen.order.fail');
        return view('canteen.pages.order.view',$params);
    }
    public function pending_order(Request $request){
        OrdersDate::whereId($request->id)->update(array("status" => 1));
        return json_encode($request->id);
    }

    public function completed(Request $request)
    {
        if ($request->ajax())
        {
            $login_id =  Auth::user()->id;
            $data = Orders::select('*')->where('canteen_id',$login_id)->whereDate('to_date','<=',Carbon::now())->where('status',1)->with(['meal']);
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('meal_name', function (Orders $orders) {
                return '<a href="' . route("canteen.meal.show", $orders->meal_id) . '" >' . $orders->meal->name . '</a>';
            })
            ->addColumn('school_name', function (Orders $orders) {
                return $orders->customer ? $orders->customer->school_name : '' ;
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
                $btn = '<a href="'.route('canteen.order.completed.view',$row['id']).'" class="mr-2"><i class="fa fa-eye"></i></a>';
                return $btn;
            })
            ->editColumn('total', function ($row){
                return "KD ".$row['total'];
            })
            ->rawColumns(['meal_name', 'action'])
            ->make(true);
        }
        else
        {
           $columns = [
               ['data' => 'id','name' => 'id','title' => "Id"], 
               ['data' => 'customer_name', 'name' => 'customer_id', 'title' => __("Customer Name")],
               ['data' => 'school_name', 'name' => 'customer_id', 'title' => __("School Name")],
               ['data' => 'grade_name', 'name' => 'customer_id', 'title' => __("Grade")],
               ['data' => 'group', 'title' => __("Role"), 'searchable' => false],
               ['data' => 'meal_name', 'name' => 'meal_id', 'title' => __("Meal Name")],
               ['data' => 'from_date', 'name' => 'from_date', 'title' => __("From Date")],
               ['data' => 'to_date', 'name' => 'to_date', 'title' => __("To Date")],
               ['data' => 'total', 'name' => 'total', 'title' => __("Total")],
               ['data' => 'order_on_formatted', 'name' => 'created_at', 'title' => __("Ordered On")],
               ['data' => 'action','name' => 'action', 'title' => "Action",'searchable'=>false,'orderable'=>false]];
           $params['dateTableFields'] = $columns;
           $params['dateTableUrl'] = route('canteen.order.completed');
           $params['dateTableTitle'] = "Completed Orders";
           $params['dataTableId'] = time();
           $params['breadcrumb_name'] = 'completed';
           return view('canteen.pages.order.index',$params);
         }
       
    }

    public function fail(Request $request)
    {
        if ($request->ajax())
        {
            $login_id =  Auth::user()->id;
            $data = Orders::select('*')->where('canteen_id',$login_id)->whereIn('status',[2,3])->with(['meal']);
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('meal_name', function (Orders $orders) {
                return '<a href="' . route("canteen.meal.show", $orders->meal_id) . '" >' . $orders->meal->name . '</a>';
            })
            ->addColumn('school_name', function (Orders $orders) {
                return $orders->customer ? $orders->customer->school_name : '' ;
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
                $btn = '<a href="'.route('canteen.order.fail.view',$row['id']).'" class="mr-2"><i class="fa fa-eye"></i></a>';
                return $btn;
            })
            ->editColumn('total', function ($row){
                return "KD ".$row['total'];
            })
            ->rawColumns(['meal_name', 'action'])
            ->make(true);
        }
        else
        {
           $columns = [
               ['data' => 'id','name' => 'id','title' => "Id"], 
               ['data' => 'customer_name', 'name' => 'customer_id', 'title' => __("Customer Name")],
               ['data' => 'school_name', 'name' => 'customer_id', 'title' => __("School Name")],
               ['data' => 'grade_name', 'name' => 'customer_id', 'title' => __("Grade")],
               ['data' => 'group', 'title' => __("Role"), 'searchable' => false],
               ['data' => 'meal_name', 'name' => 'meal_id', 'title' => __("Meal Name")],
               ['data' => 'from_date', 'name' => 'from_date', 'title' => __("From Date")],
               ['data' => 'to_date', 'name' => 'to_date', 'title' => __("To Date")],
               ['data' => 'total', 'name' => 'total', 'title' => __("Total")],
               ['data' => 'order_on_formatted', 'name' => 'created_at', 'title' => __("Ordered On")],
               ['data' => 'action','name' => 'action', 'title' => "Action",'searchable'=>false,'orderable'=>false]];
           $params['dateTableFields'] = $columns;
           $params['dateTableUrl'] = route('canteen.order.fail');
           $params['dateTableTitle'] = "Failed Orders";
           $params['dataTableId'] = time();
           $params['breadcrumb_name'] = 'fail';
           return view('canteen.pages.order.index',$params);
        }
       
    }
}