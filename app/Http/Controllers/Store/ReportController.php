<?php

namespace App\Http\Controllers\Canteen;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Grade;
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

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all_report(Request $request) {
      // echo "under development...";exit();
        $login_id =  Auth::user()->id;
        if ($request->ajax()) {
           $data = Orders::select('orders.*', 'order_dates.id as order_dates_id', 'order_dates.order_id', 'order_dates.date', 'order_dates.day', 'order_dates.status', 'users.grade')
            ->with(['customer','meal'])
            ->where('orders.canteen_id',$login_id)
            ->join('order_dates','order_dates.order_id','=','orders.id')
            ->join('users','orders.customer_id','=','users.id');
            // ->get();

            return DataTables::of($data)            
                ->filter(function ($query) use ($request) {
                    if ($request->get('date') != '') {
                        $daterange = explode(' to ', urldecode($request->get('date')));
                        $query->where('order_dates.date', '>=', trim(strtotime($daterange[0])));
                        $query->where('order_dates.date', '<=', trim(strtotime($daterange[1])));                        
                    } else {
                        // $query->where('order_dates.date', Carbon::now());
                    }

                    if ($request->get('school') != '') {
                        $query->where('school', $request->get('school'));
                    }

                    if ($request->get('meal') != '') {
                        $query->where('orders.meal_id', $request->get('meal'));
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
                // ['data' => 'canteen_name', 'name' => 'canteen_name', 'title' => __("Canteen")],
                ['data' => 'customer_name', 'name' => 'customer_name', 'title' => __("Customer Name")],
                ['data' => 'date', 'name' => 'date', 'title' => __("Date"),'searchable'=>false],
                // ['data' => 'day', 'name' => 'day', 'title' => __("Day")],
                // ['data' => 'school_id', 'name' => 'customer_id', 'title' => __(""),'visible' => false],
                ['data' => 'status', 'name' => 'status', 'title' => __("Status"), 'searchable' => false]
            ];
            $params['dateTableFields'] = $columns;
            $params['dateTableUrl'] = route('canteen.report.all');
            $params['dateTableTitle'] = "All Report";
            $params['dataTableId'] = time();
            $params['breadcrumb_name'] = 'all';
            $params['school'] = School::select('*')->where('status', 1)->get();
            $params['meal'] = Meal::select('*')->get();
            $params['grade'] = Grade::select('*')->get();
            return view('canteen.pages.report.index', $params);
        }
    }
   
}