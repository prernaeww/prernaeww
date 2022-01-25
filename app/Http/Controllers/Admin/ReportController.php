<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Meal;
use App\Models\Orders;
use App\Models\OrdersDate;
use App\Models\School;
use App\Models\User;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;

class ReportController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        // dd($request);
        // print_r($request->get('meal'));exit;
      // echo "under development...";exit();
        // dd(Orders::select('orders.*', 'order_dates.id as order_dates_id', 'order_dates.order_id', 'order_dates.date', 'order_dates.day', 'order_dates.status', 'users.grade')
        //     ->with(['customer','meal'])
        //     ->join('order_dates','order_dates.order_id','=','orders.id')
        //     ->join('users','orders.customer_id','=','users.id')->limit('2')->get()->toArray());
        if ($request->ajax()) {
           $data = Orders::select('orders.*', 'order_dates.id as order_dates_id', 'order_dates.order_id', 'order_dates.date', 'order_dates.day', 'order_dates.status', 'users.grade')
            ->with(['customer','meal'])
            ->join('order_dates','order_dates.order_id','=','orders.id')
            ->join('users','orders.customer_id','=','users.id');
            // ->get();

            return DataTables::of($data)
            
                ->filter(function ($query) use ($request) {
                    if ($request->get('date') != '') {
                        $daterange = explode(' to ', urldecode($request->get('date')));

                        $query->whereDate('order_dates.date', '>=', trim($daterange[0]));
                        $query->whereDate('order_dates.date', '<=', trim($daterange[1]));
                    } else {
                        // $query->where('order_dates.date', Carbon::now());
                    }
                    if ($request->get('school') != '') {
                        $school = is_array($request->get('school'))?$request->get('school'):[$request->get('school')];
                        $query->whereIn('school',$school);
                    }

                    if ($request->get('meal') != '') {
                        $meals = is_array($request->get('meal'))?$request->get('meal'):[$request->get('meal')];
                        $query->whereIn('orders.meal_id',$meals);
                    }

                    if ($request->get('grade') != '') {
                        $query->where('users.grade', $request->get('grade'));
                    }
                })
                ->addIndexColumn()
                ->editColumn('meal_id', function ($row) {
                    $meal = Meal::whereId($row['meal_id'])->value('name');
                    return $meal == null ? '-' : $meal;
                })
                ->editColumn('status', function ($row) {
                    if (isset($row['status'])) {
                        if ($row['status'] == 0) {
                            $role = 'Pending';
                        } elseif ($row['status'] == 1) {
                            $role = 'Delivered';
                        } elseif ($row['status'] == 2) {
                            $role = 'Issue Reported';
                        } elseif ($row['status'] == 3) {
                            $role = 'Expired';
                        } else {
                            $role = 'Changed';
                        }
                    } else {
                        $role = '';
                    }
                    return $role;
                })
                ->make(true);
        } else {
            $columns = [
                ['data' => 'id', 'name' => 'id', 'title' => "Id"],
                ['data' => 'meal_id', 'name' => 'meal_id', 'title' => "Meal Name"],
                ['data' => 'canteen_name', 'name' => 'canteen_name', 'title' => __("Canteen"),'searchable'=>true],
                ['data' => 'customer_name', 'name' => 'customer_name', 'title' => __("Customer Name"),'searchable'=>true],
                ['data' => 'date', 'name' => 'date', 'title' => __("Date"),'searchable'=>false],
                // ['data' => 'day', 'name' => 'day', 'title' => __("Day")],
                // ['data' => 'school_id', 'name' => 'customer_id', 'title' => __(""),'visible' => false],
                ['data' => 'status', 'name' => 'status', 'title' => __("Status"), 'searchable' => false]
            ];
            $params['dateTableFields'] = $columns;
            $params['dateTableUrl'] = route('admin.report.all');
            $params['dateTableTitle'] = "All Report";
            $params['dataTableId'] = time();
            $params['breadcrumb_name'] = 'all';
            $params['school'] = School::select('*')->where('status', 1)->get();
            $params['meal'] = Meal::select('*')->get();
            $params['grade'] = Grade::select('*')->get();
            return view('admin.pages.report.index', $params);
        }
    }

    public function index1(Request $request) {
        // dd(Orders::select('*')->join('order_dates','order_dates.order_id','=','orders.id')->limit('2')->get());
        if ($request->ajax()) {
            // $data = OrdersDate::with(['order_products', 'orders']);
            $data = Orders::select('orders.customer_id', 'orders.canteen_id', 'orders.meal_id', 'order_dates.id', 'order_dates.order_id', 'order_dates.date', 'order_dates.day', 'order_dates.status', 'order_products.product_id')
            ->join('order_dates','order_dates.order_id','=','orders.id')
            ->join('order_products','order_products.date_id','=','order_dates.id')
            ->join('meals','orders.meal_id','=','meals.id')
            ->get();
            // dd($data);

            $datat = Datatables::of($data);

            // $datat->filter(function ($query) use ($request) {
            //     if ($request->get('date') != '') {
            //         $daterange = explode(' to ', urldecode($request->get('date')));
            //         $query->where('date', '>=', trim(strtotime($daterange[0])));
            //         $query->where('date', '<=', trim(strtotime($daterange[1])));
            //         // $query->whereBetween('date', [trim(strtotime($daterange[0])), trim(strtotime($daterange[1]))];
            //     } else {
            //         $query->whereDate('date', Carbon::now());
            //     }

            //     if ($request->get('school') != '') {
            //         $query->where('orders.canteen_id', $request->get('school'));
            //     }

            //     if ($request->get('meal') != '') {
            //         $query->where('orders.meal_id', $request->get('meal'));
            //     }

            //     if ($request->get('grade') != '') {
            //         $query->where('grade_id', $request->get('grade'));
            //     }
            // });

            $datat->addIndexColumn()
                ->addColumn('id', function (Orders $orders) {
                    return $orders->id;
                })
                ->addColumn('canteen_id', function (Orders $orders) {
                    $school = School::where('id', $orders->canteen_id)->first();
                    if ($school) {
                        return $school->name;
                    }
                    return '';
                })
                ->addColumn('customer_id', function (Orders $orders) {
                    $customer = User::where('id', $orders->customer_id)->first();
                    if ($customer) {
                        return $customer->name;
                    }
                    return '';
                })
                ->addColumn('date', function (Orders $orders) {
                    return $orders->date;
                })
                ->addColumn('day', function (Orders $orders) {
                    return $orders->day;
                })
                ->addColumn('product_id', function (Orders $orders) {
                    $product_name = '';
                    foreach ($orders->product_id as $okey => $oproduct) {
                        if ($okey == 0) {
                            $product_name = $oproduct->product_name;
                        } else {
                            $product_name .= " , " . $oproduct->product_name;
                        }

                    }

                    return $product_name;
                })
                ->addColumn('status', function (Orders $orders) {
                    if (isset($orders->status)) {
                        if ($orders->status == 0) {
                            $role = 'Pending';
                        } elseif ($orders->status == 1) {
                            $role = 'Delivered';
                        } elseif ($orders->status == 2) {
                            $role = 'Issue Reported';
                        } elseif ($orders->status == 3) {
                            $role = 'Expired';
                        } else {
                            $role = 'Changed';
                        }
                    } else {
                        $role = '';
                    }
                    return $role;
                })
                ->make(true);
        } else {
            $columns = [
                ['data' => 'id', 'name' => 'id', 'title' => "Id"],
                ['data' => 'canteen_id', 'name' => 'canteen_id', 'title' => __("School Name")],
                ['data' => 'customer_id', 'name' => 'customer_id', 'title' => __("Customer Name")],
                ['data' => 'date', 'name' => 'date', 'title' => __("Date")],
                ['data' => 'day', 'name' => 'day', 'title' => __("Day")],
                ['data' => 'product_id', 'name' => 'product_id', 'title' => __("Order Products")],
                ['data' => 'status', 'name' => 'status', 'title' => __("Status"), 'searchable' => false],
            ];
            $params['dateTableFields'] = $columns;
            $params['dateTableUrl'] = route('admin.report.all');
            $params['dateTableTitle'] = "All Report";
            $params['dataTableId'] = time();
            $params['breadcrumb_name'] = 'all';
            $params['school'] = School::select('*')->where('status', 1)->get();
            $params['meal'] = Meal::select('*')->get();
            $params['grade'] = Grade::select('*')->get();
            return view('admin.pages.report.index', $params);
        }
    }
}