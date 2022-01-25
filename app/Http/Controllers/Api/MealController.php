<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Models\MealCategory;
use App\Models\Product;
use App\Models\Category;
use App\Models\School;
use App\Models\Issue;
use App\Models\User;
use App\Models\Orders;
use App\Models\OrdersDate;
use App\Models\Favourite;
use App\Models\Blacklist;
use App\Models\OrdersProduct;
use App\Traits\ApiResponser;
use CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
 
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PhpParser\Node\Stmt\For_;
use BookeeyHelper;
use NotificationHelper;

class MealController extends Controller {
    use ApiResponser;

    public function home(Request $request) {
        $validator = Validator::make(request()->all(), [
            'user_id' => 'required',
        ]);
        if (!$validator->fails()) {
            $user = User::where('id',$request->user_id)->first(); 
            if (isset($user)) {

                if($user->group == 3){
                    $meals = Meal::with(['category'])->orderBy('id', 'DESC')->take(5)->get();
                }else{
                    $school = School::where('id',$user->school)->whereStatus(1)->first();
                    if (isset($school)) {
                        $meals = Meal::where('canteen_id', $school->canteen_id)->with(['category'])->orderBy('id', 'DESC')->take(5)->get();
                        $order = Orders::where('customer_id', $request->user_id)->whereDate('to_date', '>=', Carbon::now())->where('status', 1)->with(['meal'])->first();
                    }else {
                        return $this->errorResponse(__('School is not activated'));
                    }
                }
                if (isset($meals)) {
                    $meals = $meals->toArray();
                    foreach ($meals as $key => $value) {
                        // $meals[$key]['category'] = array_slice($value['category'], 0, 3);
                        $meals[$key]['category'] = $value['category'];
                    }
                    $data = [];
                    $data['meals'] = $meals;
                    if (isset($order)) {
                        $data['running_order'] = $order;
                    } else {
                        $data['running_order'] = [];
                    }

                    if($user->parent_id != 0){
                        $parent = User::where('id',$user->parent_id)->first();
                        $parent->is_order = false;
                        $parentsids[0] = $user->id;
                        $orders = Orders::whereIn('customer_id',$parentsids)->whereStatus(1)->where('to_date','>=',date('Y-m-d'))->first();
                        if(!empty($orders))
                        {
                            $parent->is_order = true;
                        }    
                        $data['user'] =$parent;
                    }else{
                        $user->is_order = false;
                        $parents = User::where('parent_id',$user->id)->get()->toArray();
                        $parentsids = array_column($parents,'id');
                        array_push($parentsids,$user->id);
                        $orders = Orders::whereIn('customer_id',$parentsids)->whereStatus(1)->where('to_date','>=',date('Y-m-d'))->first();
                        if(!empty($orders))
                        {
                            $user->is_order = true;
                        }
                        $data['user'] =$user;
                    }

                    return $this->successResponse($data, __('List of Meals'));
                } else {
                    return $this->errorResponse(__('Meals not found'));
                }
            } else {
                return $this->errorResponse(__('User not found'));
            }
        }
        return $this->errorResponse($validator->messages(), true);
    }

    public function get_all_meals(Request $request) {
        $validator = Validator::make(request()->all(), [
            'user_id' => 'required',
        ]);
        if (!$validator->fails()) {
            $user = User::where('id',$request->user_id)->first(); 
            if (isset($user)) {
                if($user->group == 3){
                    $meals = Meal::with(['category'])->orderBy('id', 'DESC')->get();
                }else{
                    $school = School::where('id',$user->school)->first();
                    if (isset($school)) {
                        $meals = Meal::where('canteen_id',$school->canteen_id)->with(['category'])->orderBy('id', 'DESC')->get();
                    }else {
                        return $this->errorResponse(__('School not found'));
                    }
                }
                if(isset($meals)){
                    $meals = $meals->toArray();

                    foreach ($meals as $key => $value) {
                        $meals[$key]['category'] = array_slice($value['category'], 0,3);
                    }
                    $data['meals'] = $meals; 

                    if($user->parent_id != 0){
                        $parent = User::where('id',$user->parent_id)->first();
                        $data['user'] =$parent;
                    }else{
                        $data['user'] =$user;
                    }
                    return $this->successResponse($data, __('List of Meals'));
                }else{
                    return $this->errorResponse(__('Meals not found'));
                }
                
            } else {
                return $this->errorResponse(__('User not found'));
            }
        }
        return $this->errorResponse($validator->messages(), true);
    }
    public function get_meal_detail(Request $request) {
        $validator = Validator::make(request()->all(), [
            'user_id' => 'required',
            'meal_id' => 'required'
        ]);
        if (!$validator->fails()) {
            $user = User::where('id',$request->user_id)->first(); 
            if (isset($user)) {
                $meals = Meal::whereId($request->meal_id)->with(['category'])->first();
                if(isset($meals)){
                    $meals = $meals->toArray();

                    $blacklist_product_ids = $category_ids  = [];
                    $blacklist_products = Blacklist::where('user_id',$request->user_id)->get();
                    if(count($blacklist_products) > 0){
                        $blacklist_products = $blacklist_products->toArray();
                        $blacklist_product_ids = array_column($blacklist_products,'product_id');
                    }
                    $blacklist_product_ids = array_unique($blacklist_product_ids);
                    
                    $products = Product::whereIn('id',$blacklist_product_ids)->get();
                    if (count($products) > 0) {
                        $products = $products->toArray();
                        $category_ids = array_column($products,'category_id');
                        $category_ids = array_unique($category_ids);
                    }
                    foreach ($meals['category'] as $key => $value) {
                        $meals['category'][$key]['is_blacklist'] = false;
                        foreach ($category_ids as $ckey => $cvalue) {
                            if($cvalue == $value['category_id']){
                                $meals['category'][$key]['is_blacklist'] = true;
                            }
                        }
                    }
                    $category_datas = $temp = [];
                    $allproducts = Product::all();
                    if(count($allproducts) > 0){
                        $allproducts = $allproducts->toArray();
                        foreach ($allproducts as $bkey => $bvalue) {
                            $allproducts[$bkey]['blacklist'] = 0;
                            if(in_array($bvalue['id'],$blacklist_product_ids)){
                                $allproducts[$bkey]['blacklist'] = 1;
                            }
                        }
                        $all_cat_ids = array_column($allproducts,'category_id');
                        $all_cat_ids = array_unique($all_cat_ids);
                        $all_cat_ids = array_values($all_cat_ids);

                        foreach ($all_cat_ids as $allkey => $allvalue) {
                            $temp['id'] = $allvalue;
                            $temp['total_product'] = 0;
                            $temp['blacklisted_product'] = 0;
                            array_push($category_datas,$temp);
                        }

                        foreach ($allproducts as $pkey => $pvalue) {
                            foreach ($category_datas as $catkey => $catvalue) {
                                if ($catvalue['id'] == $pvalue['category_id']) {
                                    $category_datas[$catkey]['total_product'] += 1 ;
                                    if ($pvalue['blacklist'] == 1) {
                                        $category_datas[$catkey]['blacklisted_product'] += 1 ;
                                    }
                                }
                            }
                        }

                        foreach ($meals['category'] as $kkey => $kvalue) {
                            foreach ($category_datas as $cdkey => $cdvalue) {
                                if($cdvalue['id'] == $kvalue['category_id']){
                                    $meals['category'][$kkey]['total_product'] = $cdvalue['total_product'];
                                    $meals['category'][$kkey]['blacklisted_product'] = $cdvalue['blacklisted_product'];
                                }
                            }
                        }

                    }

                    return $this->successResponse($meals, __('List of Meals'));
                }else{
                    return $this->errorResponse(__('Meals not found'));
                }
            } else {
                return $this->errorResponse(__('User not found'));
            }
        }
        return $this->errorResponse($validator->messages(), true);
    }

    public function get_category_wise_products(Request $request) {
        $validator = Validator::make(request()->all(), [
            'user_id' => 'required',
            'category_id' => 'required'
            
        ]);
        if (!$validator->fails()) {
            $user = User::where('id',$request->user_id)->first(); 
            if (isset($user)) {
                $favourite_product_ids =  $blacklist_product_ids = [];
                $favourite_products = Favourite::where('user_id',$request->user_id)->get();
                $blacklist_products = Blacklist::where('user_id',$request->user_id)->get();
                if(count($favourite_products) > 0){
                    $favourite_products = $favourite_products->toArray();
                    $favourite_product_ids = array_column($favourite_products,'product_id');
                }
                if(count($blacklist_products) > 0){
                    $blacklist_products = $blacklist_products->toArray();
                    $blacklist_product_ids = array_column($blacklist_products,'product_id');
                }
                $products = Product::where('category_id',$request->category_id)->get();
                if(count($products) > 0){
                    $products = $products->toArray();
                   
                    foreach ($products as $key => $value) {
                        $products[$key]['favourite'] = false;
                        $products[$key]['blacklist'] = false;
                        if(in_array($value['id'],$favourite_product_ids)){
                            $products[$key]['favourite'] = true;
                        }
                        if(in_array($value['id'],$blacklist_product_ids)){
                            $products[$key]['blacklist'] = true;
                        }
                    }
                   
                    return $this->successResponse($products, __('List of Products'));
                }else{
                    return $this->errorResponse(__('Product not found'));
                }
            } else {
                return $this->errorResponse(__('User not found'));
            }
        }
        return $this->errorResponse($validator->messages(), true);
    }

    public function get_all_products(Request $request) {
        $validator = Validator::make(request()->all(), [
            'user_id' => 'required',
        ]);
        if (!$validator->fails()) {
            $user = User::where('id',$request->user_id)->first(); 
            if (isset($user)) {
                $school = School::where('id',$user->school)->first();
                if(isset($school)){
                    $favourite_product_ids =  $blacklist_product_ids = [];
                    $favourite_products = Favourite::where('user_id',$request->user_id)->get();
                    $blacklist_products = Blacklist::where('user_id',$request->user_id)->get();
                    if(count($favourite_products) > 0){
                        $favourite_products = $favourite_products->toArray();
                        $favourite_product_ids = array_column($favourite_products,'product_id');
                    }
                    if(count($blacklist_products) > 0){
                        $blacklist_products = $blacklist_products->toArray();
                        $blacklist_product_ids = array_column($blacklist_products,'product_id');
                    }

                    $categorys = Category::where('status',1)->where('canteen_id',$school->canteen_id)->get();
                    $categorys=$categorys->toArray();
                    $products = [];
                    if(!empty($categorys))
                    {
                        $categoryIds = array_column($categorys,'id');
                        $products = Product::where('canteen_id',$school->canteen_id)->whereIn('category_id',$categoryIds)->get();
                        $products = $products->toArray();
                    }
                    if(count($products) > 0){
                    
                        foreach ($products as $key => $value) {
                            $products[$key]['favourite'] = false;
                            $products[$key]['blacklist'] = false;
                            if(in_array($value['id'],$favourite_product_ids)){
                                $products[$key]['favourite'] = true;
                            }
                            if(in_array($value['id'],$blacklist_product_ids)){
                                $products[$key]['blacklist'] = true;
                            }
                        }
                        $data = [];
                       
                        
                        // if(count($categorys) > 0){
                            
                        // }
                        $category[0]['id']= 0;
                        $category[0]['name']= "All";
                        $category[0]['image']= "" ;
                        $category[0]['canteen_id']= 0;
                        $category[0]['status']= 0;
                        $category[0]['canteen_name'] = "";
                        $result = array_merge($category, $categorys);
                        $result = array_unique($result, SORT_REGULAR);
                        $result = array_values($result);
                        if(empty($categorys))
                        {
                            $products = [];
                            $result = [];
                            return $this->errorResponse(__('Product not found'));
                        }
                        $data['category'] = $result;
                        $data['products'] = $products;
                        return $this->successResponse($data, __('List of Products'));
                    }else{
                        return $this->errorResponse(__('Product not found'));
                    }
                }
            } else {
                return $this->errorResponse(__('User not found'));
            }
        }
        return $this->errorResponse($validator->messages(), true);
    }

    public function get_school_details(Request $request) {
        $validator = Validator::make(request()->all(), [
            'user_id' => 'required',            
        ]);
        if (!$validator->fails()) {
            $user = User::where('id',$request->user_id)->first(); 
            if (isset($user)) {
                $school = School::where('id',$user->school)->with(['holiday'])->first();
                if(isset($school)){
                    $school = $school->toArray();
                    $from_date = array_column($school['holiday'],'from_date');
                    $to_date = array_column($school['holiday'],'to_date');
                    $dates = [];
                    if(!empty($school['holiday']))
                    {
                        foreach($school['holiday'] as $row)
                        {
                            $period = CarbonPeriod::create($row['from_date'],$row['to_date']);
                            foreach($period as $p)
                            {
                                $dates[] = $p->format('Y-m-d'); 
                            }
                        }
                    }
                    // $dates = array_merge($from_date,$to_date);
                    // $dates = array_unique($dates);

                    usort($dates, function($a, $b) {
                        return strtotime($a) - strtotime($b);
                    });

                    $school['dates'] = $dates;

                    $order = Orders::where('customer_id',$request->user_id)->where('status',1)->get();

                    if (count($order) > 0) {
                        $order = $order->toArray();
                        $order_ids = array_column($order, 'id');

                        $order_dates = OrdersDate::whereIn('order_id',$order_ids)->get();

                        if(count($order_dates) > 0){
                            $order_dates = $order_dates->toArray();
                            $order_dates_all = array_column($order_dates,'date');
                        }

                        usort($order_dates_all, function($a, $b) {
                            return strtotime($a) - strtotime($b);
                        });

                        $school['booked_meal_dates'] = $order_dates_all;
                    }

                    return $this->successResponse($school, __('School Detail'));
                }else{
                    return $this->errorResponse(__('School not found'));
                }
            } else {
                return $this->errorResponse(__('User not found'));
            }
        }
        return $this->errorResponse($validator->messages(), true);
    }

    public function check_date_format($date)
    {
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) {
            return true;
        } else {
            return false;
        }
    }

    public function date_wise_meal_plan(Request $request) {
        $validator = Validator::make(request()->all(), [
            'user_id' => 'required',
            // 'from_date' => 'required',
            // 'to_date' => 'required',
            'meal_id' => 'required',
            // 'dates' => 'required',
            
        ]);
        if (!$validator->fails()) {
            // print_r($request->dates);
            $requested_dates = explode(',',$request->dates);
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
                $school = School::where('id',$user->school)->with(['holiday'])->whereStatus(1)->first();
                if(isset($school)){
                    $school = $school->toArray();
                    $meals = Meal::whereId($request->meal_id)->with(['category'])->first();
                    if(isset($meals)){
                        $meals = $meals->toArray();
                        // $from_date = $request->from_date;
                        // $to_date = $request->to_date;
                        $from_date = $requested_dates[0];
                        $to_date = $requested_dates[$total_dates_array - 1];

                        // $order = Orders::where('customer_id',$request->user_id)->where('status',1)
                        // ->where(function($query) use ($from_date, $to_date){
                        //     $query->where(function($query) use ($from_date, $to_date){
                        //          $query->where('from_date', '>=', $from_date);
                        //          $query->where('from_date', '<=', $to_date);
                        //     });
                        //     $query->orWhere(function($query) use ($from_date, $to_date){
                        //          $query->where('to_date', '>=', $from_date);
                        //          $query->where('to_date', '<=', $to_date);
                        //     });
                        //     $query->orWhere(function($query) use ($from_date, $to_date){
                        //          $query->where('from_date', '<=', $from_date);
                        //          $query->where('to_date', '>=', $to_date);
                        //     });
                        //     $query->orWhere(function($query) use ($from_date, $to_date){
                        //          $query->where('from_date', '>=', $from_date);
                        //          $query->where('to_date', '<=', $to_date);
                        //     });
                        // })->first();

                    // if(!isset($order)){

                            $category = [];
                            foreach ($meals['category'] as $mkey => $mvalue) {
                                $temp['category_id'] = $mvalue['category_id'];
                                $temp['item_number'] = $mvalue['items_number'];
                                $temp['category_name'] = $mvalue['category_name'];
                                $temp['category_image'] = $mvalue['category_image'];
                                array_push($category,$temp);
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
                            $products = Product::whereIn('category_id',$category_ids)->whereNotIn('id',$blacklist_product_ids)->whereStatus(1)->get();

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
                            // print_r($category);exit;
                            // $startDate = Carbon::createFromFormat('Y-m-d', $request->from_date);
                            // $endDate = Carbon::createFromFormat('Y-m-d', $request->to_date);
                            // $dateRange = CarbonPeriod::create($startDate, $endDate);
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
                           
                            // return $this->successResponse($category, __('Meals Detailq'));

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
                            $data['user'] = $user;
                            $data['day_wise_data'] = $day_wise_data;
                            $data['total'] = $total_day_tax; 
                            $data['meal'] = $meals; 
                            return $this->successResponse($data, __('Meals Detail'));
                        // }else{
                        //     return $this->errorResponse(__('You have already placed an order during this period of time'));
                        // }
                    }else{
                        return $this->errorResponse(__('Meals not found'));
                    }
                }else{
                    return $this->errorResponse(__('School is not activated'));
                }
            } else {
                return $this->errorResponse(__('User not found'));
            }
        }
        return $this->errorResponse($validator->messages(), true);
    }

    public function checkout_screen_details(Request $request) {
        $validator = Validator::make(request()->all(), [
            'user_id' => 'required',
            'meal_id' => 'required',
            'dates' => 'required'
            
        ]);
        if (!$validator->fails()) {
            $requested_dates = explode(',',$request->dates);
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
            
            $user = User::where('id',$request->user_id)->first(); 
            if (isset($user)) {
                
                $school = School::where('id',$user->school)->with(['holiday'])->first();
                if(isset($school)){
                    $school = $school->toArray();
                    $meals = Meal::whereId($request->meal_id)->first();
                    if(isset($meals)){
                        $meals = $meals->toArray();
                        // return $this->successResponse($meals, __('Meals Detail'));

                        /* $startDate = Carbon::createFromFormat('Y-m-d', $request->from_date);
                        $endDate = Carbon::createFromFormat('Y-m-d', $request->to_date);

                        $dateRange = CarbonPeriod::create($startDate, $endDate); */
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

                        $total_days_price = count($dates) * $meals['price'];
                        $total_days_price = number_format((float)$total_days_price, 2, '.', '');
                        $tax = CommonHelper::ConfigGet('tax');
                        $tax = number_format((float)$tax, 2, '.', '');
                        $total_day_tax = $total_days_price  + $tax;
                        $total_day_tax = number_format((float)$total_day_tax, 2, '.', '');

                        $pricing = [];

                        $pricing[] =array("label"=>"total_days_price" ,"title"=>__("Meal price of (".count($dates)." Days)"), "value"=>$total_days_price);
                        $pricing[] =array("label"=>"taxes" ,"title"=>__("Service fees"), "value"=>$tax);
                        $pricing[] =array("label"=>"discount" ,"title"=>__("Discount"), "value"=>"0");
                        $pricing[] =array("label"=>"total" ,"title"=>__("Total"), "value"=>$total_day_tax);
                        
                        $user->pricing = $pricing;

                        $user->meals = $meals;
                        return $this->successResponse($user, __('Details'));
                    }else{
                        return $this->errorResponse(__('Meals not found'));
                    }
                }else{
                    return $this->errorResponse(__('School not found'));
                }
            } else {
                return $this->errorResponse(__('User not found'));
            }
        }
        return $this->errorResponse($validator->messages(), true);
    }

    public function book_meal(Request $request) {
        $validator = Validator::make(request()->all(), [
            'user_id' => 'required',
            // 'from_date' => 'required',
            // 'to_date' => 'required',
            'meal_data' => 'required',
            'meal_id' => 'required',
            'dates' => 'required',
            
        ]);
        if (!$validator->fails()) {
            $requested_dates = explode(',',$request->dates);
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
                $school = School::where('id',$user['school'])->with(['holiday'])->whereStatus(1)->first();
                if(isset($school)){
                    $school = $school->toArray();

                    $meals = Meal::whereId($request->meal_id)->first();
                    if (isset($meals)) {
                        $meals = $meals->toArray();
                        $meal_data =  json_decode($request['meal_data'], true);

                        // $startDate = Carbon::createFromFormat('Y-m-d', $request->from_date);
                        // $endDate = Carbon::createFromFormat('Y-m-d', $request->to_date);
                        
                        // $dateRange = CarbonPeriod::create($startDate, $endDate);
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

                        BookeeyHelper::setTitle('Canteeny');
                        BookeeyHelper::setDescription('Payment');
                        BookeeyHelper::setMerchantID(CommonHelper::ConfigGet('bookeey_mer_id')); // Set the Merchant ID
                        BookeeyHelper::setSecretKey(CommonHelper::ConfigGet('bookeey_mer_secretkey')); // Set the Secret Key
                        BookeeyHelper::setOrderId($order_id); // Set Order ID - This should be unique for each transaction.
                        BookeeyHelper::setAmount($order_inserted_data->total); // Set amount in KWD

                        if($user['group'] == 6){
                            $fullname = $parent['last_name'].' '.$user['first_name']; 
                            $phone = $parent['phone'];
                            $notification_id = $parent['id'];
                            $device_token = $parent['devices']['token'];
                        }else{
                            $fullname = $user['first_name'].' '.$user['last_name']; 
                            $phone = $user['phone'];
                            $notification_id = $user['id'];
                            $device_token = $user['devices']['token'];
                        }
                        BookeeyHelper::setPayerName($fullname);  // Set Payer Name
                        BookeeyHelper::setPayerPhone($phone);  // Set Payer Phone Numner
                        BookeeyHelper::setSelectedPaymentOption('knet');
                        // setSelectedPaymentOption('credit');
                        // setSelectedPaymentOption('Bookeey');
                        // setSelectedPaymentOption('amex');

                        $transactionDetails[0]['SubMerchUID'] = CommonHelper::ConfigGet('bookeey_mer_id');
                        $transactionDetails[0]['Txn_AMT'] = $order_inserted_data->total;
                        $response = BookeeyHelper::initiatePayment($transactionDetails);
                        if ($response['status']) {

                            
                            $push_title = 'Order Confirmed'; 
                            $push_data = array();
                            $push_data['order_id'] = $order_id;
                            $push_data['message'] = "Your order has been confirmed";
                            $push_type = 'order_confirmed';
                            // if(isset($device_token) && $device_token != ''){
                            //     $notification =  NotificationHelper::send($device_token, $push_title, $push_data, $push_type);
                            // }
                            // $add_notification =  NotificationHelper::add($notification_id, $push_data['message'], $push_type, $order_id);

                            $data['order_id'] = $order_id;
                            $data['payment_url'] = $response['url'];
                            // $data['notification'] = $notification;
                            $data['device_token'] = $device_token;
                            return $this->successResponse($data, __('Your order has been placed successfully'));
                        } else {
                            Orders::where('id',$order_id)->update(array("status" => 3));
                            //order_failed
                            return $this->errorResponse($response['error']);
                        }
                    }else{
                        return $this->errorResponse(__('Meals not found'));
                    }
                }else{
                    return $this->errorResponse(__('School is not activated'));
                }
            } else {
                return $this->errorResponse(__('User not found'));
            }
        }
        return $this->errorResponse($validator->messages(), true);
    }

    public function get_order_details(Request $request) {
        $validator = Validator::make(request()->all(), [
            'user_id' => 'required',
            'order_id' => 'required'
        ]);
        if (!$validator->fails()) {
            $user = User::where('id',$request->user_id)->first(); 
            if (isset($user)) {
                $order = Orders::whereId($request->order_id)->with(['customer'])->where('customer_id',$request->user_id)->first();
                if(isset($order)){
                    $meals = Meal::whereId($order->meal_id)->with(['category'])->first();
                    
                    // $order_dates = OrdersDate::where('order_id',$request->order_id)->with(['order_products'])->orderBy('date','desc')->get();

                   // $order_dates = OrdersDate::where('order_id',$request->order_id)->orderBy('date','desc')->get();
                   $order_dates = OrdersDate::where('order_id',$request->order_id)->where('status','!=',4)->with(['order_products'])->orderBy('date','asc')->get();

                   if($order->status == 2)
                   {
                      foreach ($order_dates as  $value) {
                          $value->status = 5;
                      }
                   }
                    $total_days = count($order_dates);
                    $meals->total_days =  $total_days;
                    $order->meal = $meals;
                    $order->order_dates = $order_dates;

                    $pricing = [];

                    $pricing[] =array("label"=>"total_days_price" ,"title"=>__("Meal price of (".$total_days." Days)"), "value"=>$order->sub_total);
                    $pricing[] =array("label"=>"taxes" ,"title"=>__("Taxes"), "value"=>$order->tax);
                    $pricing[] =array("label"=>"discount" ,"title"=>__("Discount"), "value"=>"0");
                    $pricing[] =array("label"=>"total" ,"title"=>__("Total"), "value"=>$order->total);

                    $order->pricing = $pricing;
 
                    $school = School::where('id',$user->school)->with(['holiday'])->first();
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

                        if(count($order_dates) > 0){

                            $order_dates = (array) $order_dates;
                            
                            $order_dates_all = array_column($order_dates,'date');
                        }
                       
                        $final_dates = array_merge($dates, $order_dates_all);
                        $final_dates = array_unique($final_dates);
                        $previous_orders =  OrdersDate::join('orders','order_dates.order_id','orders.id')
                                ->where('order_id','!=',$request->order_id)
                                ->where('orders.customer_id',$request->user_id)    
                                ->where('order_dates.status',0)
                                ->orderBy('date','desc')->get();
                        usort($final_dates, function($a, $b) {
                            return strtotime($a) - strtotime($b);
                        });
                        $old_dates = [];

                        if(!empty($previous_orders))
                        {
                            $old_dates = array_column($previous_orders->toArray(),'date');
                        }
                        $new_array = array_merge($old_dates,$final_dates);
                        $order->hide_dates = $new_array;
                        
                    }
                   

                    return $this->successResponse($order, __('Order Detail'));
                }else{
                    return $this->errorResponse(__('Order not found'));
                }
            } else {
                return $this->errorResponse(__('User not found'));
            }
        }
        return $this->errorResponse($validator->messages(), true);
    }

    public function get_current_order_list(Request $request) {

        $validator = Validator::make(request()->all(), [
            'user_id' => 'required',
        ]);
        if (!$validator->fails()) {
            $user = User::where('id',$request->user_id)->first(); 
            if (isset($user)) {
                // $order = Orders::where('customer_id',$request->user_id)->whereDate('to_date','>=',Carbon::now())->where('status',1)->orderBy('id', 'DESC')->get();
                $order = Orders::where('customer_id',$request->user_id)->where('status',1)->orderBy('id', 'DESC')->get();
                if(count($order) > 0){
                    $order = $order->toArray();
                    $meal_ids = array_column($order,'meal_id');
                    $meals = Meal::whereIn('id',$meal_ids)->with(['category'])->get();
                // print_r($order);exit;
                    
                    $order_ids = array_column($order,'id');
                    //->with(['order_products']) 
                    $order_dates = OrdersDate::whereIn('order_id',$order_ids)->get();

                    if(count($meals) > 0){
                        $meals = $meals->toArray();
                        foreach ($order as $key => $value) {
                            $to_date = strtotime($value['to_date']);
                            $order[$key]['past'] = false;
                            if($to_date < time())
                            {
                                $order[$key]['past'] = true;
                            }
                            foreach ($meals as $mkey => $mvalue) {
                                if($value['meal_id'] == $mvalue['id']){
                                    $order[$key]['meal'] = $mvalue;
                                }
                            }
                            foreach ($order_dates as $okey => $ovalue) {
                                if($value['id'] == $ovalue['order_id']){
                                    $order[$key]['order_dates'][] = $ovalue;
                                }
                            }
                        }
                    }

                    return $this->successResponse($order, __('List of Current Orders'));
                }else{
                    return $this->errorResponse(__('Order not found'));
                }
            } else {
                return $this->errorResponse(__('User not found'));
            }
        }
        
        return $this->errorResponse($validator->messages(), true);
    }

    public function get_past_order_list(Request $request) {
        $validator = Validator::make(request()->all(), [
            'user_id' => 'required',
        ]);
        if (!$validator->fails()) {
            $user = User::where('id',$request->user_id)->first(); 
            if (isset($user)) {
                $order = Orders::where('customer_id',$request->user_id)->with(['order_dates'])->whereDate('to_date','<=',Carbon::now())->whereIn('status',[1,2,3])->orderBy('id', 'DESC')->get();
                if(count($order) > 0){
                    $order = $order->toArray();
                    $meal_ids = array_column($order,'meal_id');
                    $meals = Meal::whereIn('id',$meal_ids)->with(['category'])->get();

                    $order_ids = array_column($order,'id');
                    $order_dates = OrdersDate::whereIn('order_id',$order_ids)->with(['order_products'])->get();

                    if(count($meals) > 0){
                        $meals = $meals->toArray();
                        foreach ($order as $key => $value) {
                            foreach ($meals as $mkey => $mvalue) {
                                if($value['meal_id'] == $mvalue['id']){
                                    $order[$key]['meal'] = $mvalue;
                                }
                            }
                            foreach ($order_dates as $okey => $ovalue) {
                                if($value['id'] == $ovalue['order_id']){
                                    $order[$key]['order_dates'][] = $ovalue;
                                }
                            }
                        }
                    }
                    return $this->successResponse($order, __('List of Past Orders'));
                }else{
                    return $this->errorResponse(__('Order not found'));
                }
            } else {
                return $this->errorResponse(__('User not found'));
            }
        }
        return $this->errorResponse($validator->messages(), true);
    }

    public function get_invoices(Request $request) {
        $validator = Validator::make(request()->all(), [
            'user_id' => 'required',
        ]);
        if (!$validator->fails()) {
            $user = User::where('id',$request->user_id)->first(); 
            if (isset($user)) {
                if($user->group == 3){
                    $childrens = User::where('parent_id',$request->user_id)->where('status',1)->get(); 
                    if(count($childrens) > 0){
                        $childrens = $childrens->toArray();
                        $child_ids = array_column($childrens,'id');
                        // return $this->successResponse($child_ids, __('List of Invoices'));
                        $order = Orders::whereIn('customer_id',$child_ids)->with(['customer'])->where('status',1)->orderBy('id', 'DESC')->get();
                        if(count($order) > 0){
                            return $this->successResponse($order, __('List of Invoices'));
                        }else{
                            return $this->errorResponse(__('No any Invoices'));
                        }
                    }else{
                        return $this->errorResponse(__('No any Invoices'));
                    }
                }else{
                    $order = Orders::where('customer_id',$request->user_id)->with(['customer'])->where('status',1)->orderBy('id', 'DESC')->get();
                    if(count($order) > 0){
                        return $this->successResponse($order, __('List of Invoices'));
                    }else{
                        return $this->errorResponse(__('No any Invoices'));
                    }
                }
            } else {
                return $this->errorResponse(__('User not found'));
            }
        }
        return $this->errorResponse($validator->messages(), true);
    }

    public function search(Request $request) {
        $validator = Validator::make(request()->all(), [
            'user_id' => 'required',
            'search_text' => 'required|min:3',
        ]);
        if (!$validator->fails()) {
            $user = User::where('id',$request->user_id)->first(); 
            if (isset($user)) {
                $school = School::where('id',$user->school)->first();
                if (isset($school)) {

                $category = Category::where('status',1)->where('canteen_id',$school->canteen_id)->where('name', 'like', '%' . $request['search_text'] . '%')->orderBy('id', 'desc')->get();

                $products = Product::where('canteen_id',$school->canteen_id)->where('name', 'like', '%' . $request['search_text'] . '%')->orderBy('id','desc')->get();

                if(count($category) > 0){
                    $category = $category->toArray();
                    $category_ids = array_column($category,'id');
                    $category_products = Product::whereIn('category_id',$category_ids)->get();
                    if (count($category_products) > 0) {
                        $category = $category_products->toArray();
                    }else{
                        $category = array();
                    }
                }else{
                    $category = array();
                }
                if(count($products) > 0){
                    $products = $products->toArray();
                }else{
                    $products = array();
                }
                
                $result = array_merge($category, $products);
                $result = array_unique($result, SORT_REGULAR);
                $result = array_values($result);

                $favourite_product_ids =  $blacklist_product_ids = [];
                $favourite_products = Favourite::where('user_id',$request->user_id)->get();
                $blacklist_products = Blacklist::where('user_id',$request->user_id)->get();
                if(count($favourite_products) > 0){
                    $favourite_products = $favourite_products->toArray();
                    $favourite_product_ids = array_column($favourite_products,'product_id');
                }
                if(count($blacklist_products) > 0){
                    $blacklist_products = $blacklist_products->toArray();
                    $blacklist_product_ids = array_column($blacklist_products,'product_id');
                }

                foreach ($result as $key => $value) {
                    $result[$key]['favourite'] = false;
                    $result[$key]['blacklist'] = false;
                    if(in_array($value['id'],$favourite_product_ids)){
                        $result[$key]['favourite'] = true;
                    }
                    if(in_array($value['id'],$blacklist_product_ids)){
                        $result[$key]['blacklist'] = true;
                    }
                }

                if(count($result) > 0){
                    return $this->successResponse($result, __('Result of Search'));
                }else{
                    return $this->errorResponse(__('Data not found'));
                }
            } else {
                return $this->errorResponse(__('School not found'));
            }
        } else {
            return $this->errorResponse(__('User not found'));
        }
        }
        return $this->errorResponse($validator->messages(), true);
    }

    public function issue_list() {
       /*  $device_token = 'fL3MTg1lRiKL5bTZcMd_rT:APA91bGNwZZiFpx9Fm7HManRuJuR7PbabRnX6osZpv_rtSpsnplpNDW8UHA2thp9ZqFYgEJEZdn_Am7jXJfdxgWi2hw4TPmrro8Kdi0H0KYaTgnM3Lkte_42X6q3ZI4Sx0oyOy5jjGWU';
        $push_title = 'Order Confirmed'; 
        $push_data = array();
        $push_data['order_id'] = "1";
        $push_data['message'] = "Your order has been confirmed";
        $push_type = 'order_confirmed';
        if(isset($device_token) && $device_token != ''){
            $notification =  NotificationHelper::send($device_token, $push_title, $push_data, $push_type);
        }
        return $this->successResponse($notification, __('List of issues')); */

        $issues = Issue::orderBy('id', 'DESC')->get();
        if (count($issues) > 0) {
            return $this->successResponse($issues, __('List of issues'));
        } else {
            return $this->errorResponse(__('Issue not found'));
        }
    }

    public function contact_us() {
        $data['phone'] = CommonHelper::ConfigGet('phone');
        $data['twitter'] = CommonHelper::ConfigGet('twitter');
        $data['instagram'] = CommonHelper::ConfigGet('instagram');
        $data['email'] = CommonHelper::ConfigGet('smtp_user');
      
        return $this->successResponse($data, __('Contact Us'));
       
    }

    public function favourite_unfavourite_product(Request $request) {

        $validator = Validator::make(request()->all(), [
            'user_id' => 'required',
            'product_id' => 'required',
            'status' => 'required'
        ]);
        if (!$validator->fails()) {
            $user = User::where('id',$request->user_id)->first(); 
            if (isset($user)) {
                $products = Product::where('id',$request->product_id)->get();
                if(count($products) > 0){
                    $products = $products->toArray();

                    if($request->status == "1"){
                        $favourite_exists = Favourite::where('user_id',$request->user_id)->where('product_id',$request->product_id)->first();
                        if(!isset($favourite_exists)){
                            $insert_data = [];
                            $insert_data['user_id'] = $request->user_id;
                            $insert_data['product_id'] = $request->product_id;
                            Favourite::insert($insert_data);

                        }
                        Blacklist::where('user_id',$request->user_id)->where('product_id',$request->product_id)->delete();
                        return $this->successResponse($products, __('Added to favourite'));
                    }else{
                        Favourite::where('user_id',$request->user_id)->where('product_id',$request->product_id)->delete();
                        return $this->successResponse($products, __('Removed from favourite'));
                    }
                }else{
                    return $this->errorResponse(__('Product not found'));
                }
            } else {
                return $this->errorResponse(__('User not found'));
            }
        }
        return $this->errorResponse($validator->messages(), true);
    }

    public function list_of_favourite(Request $request) {

        $validator = Validator::make(request()->all(), [
            'user_id' => 'required',
        ]);
        if (!$validator->fails()) {
            $user = User::where('id',$request->user_id)->first(); 
            if (isset($user)) {
                $favourite_exists = Favourite::where('user_id',$request->user_id)->get();
                if (count($favourite_exists) > 0) {
                    $favourite_exists = $favourite_exists->toArray();
                    $product_ids = array_column($favourite_exists,'product_id');
                    $products = Product::whereIn('id', $product_ids)->get();
                    if (count($products) > 0) {
                        $products = $products->toArray();
                        /* foreach ($products as $key => $value) {
                            $products[$key]['favourite'] = true;
                        } */
                        return $this->successResponse($products, __('List of favorite products'));
                    } else {
                        return $this->errorResponse(__('Product not found'));
                    }
                }else {
                    return $this->errorResponse(__('No any favourite products'));
                }
            } else {
                return $this->errorResponse(__('User not found'));
            }
        }
        return $this->errorResponse($validator->messages(), true);
    }

    public function list_of_blacklist(Request $request) {

        $validator = Validator::make(request()->all(), [
            'user_id' => 'required',
        ]);
        if (!$validator->fails()) {
            $user = User::where('id',$request->user_id)->first(); 
            if (isset($user)) {
                $blacklist_exists = Blacklist::where('user_id',$request->user_id)->get();
                if (count($blacklist_exists) > 0) {
                    $blacklist_exists = $blacklist_exists->toArray();
                    $product_ids = array_column($blacklist_exists,'product_id');
                    $products = Product::whereIn('id', $product_ids)->get();
                    if (count($products) > 0) {
                        $products = $products->toArray();
                        /* foreach ($products as $key => $value) {
                            $products[$key]['favourite'] = true;
                        } */
                        return $this->successResponse($products, __('List of blacklist products'));
                    } else {
                        return $this->errorResponse(__('Product not found'));
                    }
                }else {
                    return $this->errorResponse(__('No any blacklist products'));
                }
            } else {
                return $this->errorResponse(__('User not found'));
            }
        }
        return $this->errorResponse($validator->messages(), true);
    }

    public function blacklist_unblacklist_product(Request $request) {

        $validator = Validator::make(request()->all(), [
            'user_id' => 'required',
            'product_id' => 'required',
            'status' => 'required'
        ]);
        if (!$validator->fails()) {
            $user = User::where('id',$request->user_id)->first(); 
            if (isset($user)) {
                $products = Product::where('id',$request->product_id)->first();
                if(isset($products)){
                    $products = $products->toArray();

                    if($request->status == "1"){
                        $blacklist_exists = Blacklist::where('user_id',$request->user_id)->where('product_id',$request->product_id)->first();
                        if(!isset($blacklist_exists)){
                            $insert_data = [];
                            $insert_data['user_id'] = $request->user_id;
                            $insert_data['product_id'] = $request->product_id;
                            Blacklist::insert($insert_data);
                            
                        }
                        Favourite::where('user_id',$request->user_id)->where('product_id',$request->product_id)->delete();
                        $blacklist_product_ids = $category_ids  = $category_datas = $temp = [];
                        $blacklist_products = Blacklist::where('user_id',$request->user_id)->get();
                        if(count($blacklist_products) > 0){
                            $blacklist_products = $blacklist_products->toArray();
                            $blacklist_product_ids = array_column($blacklist_products,'product_id');
                        }
                        $blacklist_product_ids = array_unique($blacklist_product_ids);

                       
                        $all_products =  Product::where('category_id',$products['category_id'])->get();
                        if (count($all_products) > 0) {
                            $all_products = $all_products->toArray();

                            foreach ($all_products as $bkey => $bvalue) {
                                $all_products[$bkey]['blacklist'] = 0;
                                if(in_array($bvalue['id'],$blacklist_product_ids)){
                                    $all_products[$bkey]['blacklist'] = 1;
                                }
                            }
                        }

                        $temp['id'] = $products['category_id'];
                        $temp['total_product'] = 0;
                        $temp['blacklisted_product'] = 0;
                           
                        foreach ($all_products as $pkey => $pvalue) {
                            if ($products['category_id'] == $pvalue['category_id']) {
                                $temp['total_product'] += 1 ;
                                if ($pvalue['blacklist'] == 1) {
                                    $temp['blacklisted_product'] += 1 ;
                                }
                            }
                        }
                        if(isset($temp) && !empty($temp)){
                            if($temp['total_product'] == $temp['blacklisted_product']){
                                return $this->successResponse($products, __('You have blacklisted all the product of '.$products["category_name"].' category'));
                            }
                        }

                        return $this->successResponse($products, __('Added to Blacklist'));
                    }else{
                        Blacklist::where('user_id',$request->user_id)->where('product_id',$request->product_id)->delete();
                        return $this->successResponse($products, __('Removed from Blacklist'));
                    }
                }else{
                    return $this->errorResponse(__('Product not found'));
                }
            } else {
                return $this->errorResponse(__('User not found'));
            }
        }
        return $this->errorResponse($validator->messages(), true);
    }

    
    public function report_issue(Request $request) {
        $validator = Validator::make(request()->all(), [
            'user_id' => 'required',
            'issue_id' => 'required',
            'order_date_id' => 'required'
        ]);
        if (!$validator->fails()) {
            $user = User::where('id',$request->user_id)->first(); 
            if (isset($user)) {
                $order_data =  OrdersDate::whereId($request->order_date_id)->where('status',1)->first();
                if (isset($order_data)){
                    $update_data['status'] = 2;
                    $update_data['issue_id'] = $request->issue_id;
                    $update_data['description'] = $request->description;
                    OrdersDate::whereId($request->order_date_id)->update($update_data);
                    return $this->successResponse([], __('Issue reported successfully'));
                }else {
                    return $this->errorResponse(__('You cannot report issue for this order'));
                }
            } else {
                return $this->errorResponse(__('User not found'));
            }
        }
        return $this->errorResponse($validator->messages(), true);
    }
    public function get_day_wise_order(Request $request) {
        $validator = Validator::make(request()->all(), [
            'user_id' => 'required',
            'order_date_id' => 'required'
        ]);
        if (!$validator->fails()) {
            $user = User::where('id',$request->user_id)->first(); 
            if (isset($user)) {
                $order_data =  OrdersProduct::where('date_id',$request->order_date_id)->get();
                if (count($order_data) > 0){
                    $order_data = $order_data->toArray();
                    $category_ids = array_column($order_data,'category_id');
                    $category_ids = array_unique($category_ids);

                    $category_data = Category::where('status',1)->whereIn('id',$category_ids)->get();

                    if(count($category_data) > 0){
                        $category_data = $category_data->toArray();
                        foreach ($category_data as $key => $value) {
                            foreach ($order_data as $okey => $ovalue) {
                                if($value['id'] == $ovalue['category_id']){
                                    $category_data[$key]['products'][] = $ovalue;
                                }
                            }
                        }
                        return $this->successResponse($category_data, __('Order Data'));
                    }else {
                        return $this->errorResponse(__('Data not found'));
                    }
                    
                }else {
                    return $this->errorResponse(__('Data not found'));
                }
            } else {
                return $this->errorResponse(__('User not found'));
            }
        }
        return $this->errorResponse($validator->messages(), true);
    }

    public function change_order_items(Request $request) {
        $validator = Validator::make(request()->all(), [
            'user_id' => 'required',
            'order_product_id' => 'required',
            'category_id' => 'required',
            'product_id' => 'required',
        ]);
        if (!$validator->fails()) {
            $user = User::where('id',$request->user_id)->first(); 
            if (isset($user)) {
                $order_data =  OrdersProduct::where('id',$request->order_product_id)->first();
                if (isset($order_data) ){
                    $order_data = $order_data->toArray();
                    $order_update = [];
                    $order_update['product_id'] = $request->product_id;
                    OrdersProduct::where('id',$request->order_product_id)->update($order_update);
                    $order_data = OrdersProduct::where('id',$request->order_product_id)->first();
                    return $this->successResponse($order_data, __('Item updated successfully'));
                }else {
                    return $this->errorResponse(__('Data not found'));
                }
            } else {
                return $this->errorResponse(__('User not found'));
            }
        }
        return $this->errorResponse($validator->messages(), true);
    }

    public function change_order_date(Request $request) {
        $validator = Validator::make(request()->all(), [
            'user_id' => 'required',
            'order_date_id' => 'required',
            'date' => 'required',
        ]);
        if (!$validator->fails()) {
            $user = User::where('id',$request->user_id)->first(); 
            if (isset($user)) {
                $order_data =  OrdersDate::where('id',$request->order_date_id)->with(['order_products'])->where('status','!=',4)->first();


                if (isset($order_data) ){
                    $order_data = $order_data->toArray();
                    $myDate = $request->date;
                    $day = Carbon::createFromFormat('Y-m-d', $myDate)->format('l');
                    
                    $order_update = [];
                    $order_update['status'] = 4;
                    OrdersDate::where('id',$request->order_date_id)->update($order_update);

                    $insert_data['order_id'] = $order_data['order_id'];
                    $insert_data['date'] = $request->date;
                    $insert_data['day'] = $day;
                    $id = OrdersDate::insertGetId($insert_data);
                    
                    $temp = $insert_product =  [];
                    $temp['order_id'] = $order_data['order_id'];
                    $temp['date_id'] = $id;
                    foreach ($order_data['order_products'] as $key => $value) {
                        $temp['category_id'] = $value['category_id'];
                        $temp['product_id'] = $value['product_id'];
                        array_push($insert_product,$temp);
                    }
                    OrdersProduct::insert($insert_product);

                    $order_data =  OrdersDate::where('id',$id)->with(['order_products'])->first();
                    $order = Orders::whereId($order_data->order_id)->first();
                    if($request->date > $order->to_date )
                    {
                        Orders::whereId($order_data->order_id)->update(['to_date'=>$request->date]);
                    }
                    return $this->successResponse($order_data, __('Date updated successfully'));
                }else {
                    return $this->errorResponse(__('Data not found'));
                }
            } else {
                return $this->errorResponse(__('User not found'));
            }
        }
        return $this->errorResponse($validator->messages(), true);
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

}
