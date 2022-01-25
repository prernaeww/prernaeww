<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\OrdersProduct;
use App\Models\Product;
use App\Models\User;
use App\Models\Orders;
use App\Models\School;
use CommonHelper;
use PaymentHelper;


class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        //$data  = PaymentHelper::makeRequest();
        // CommonHelper::ConvertDate(date('Y-m-d'));
        $login_id =  Auth::user()->id;
        $total = Orders::where('status', '6')->sum('total');

        // $orders=Orders::where('status','1')->whereNotNull('reached')->get()->count();
        $orders = Orders::where('status', '6')->count();


        $params['board'] =  User::select('users.*')->join('users_group', 'users.id', '=', 'users_group.user_id')->where('users_group.group_id', 2)->count();
        $params['store'] =  User::select('users.*')->join('users_group', 'users.id', '=', 'users_group.user_id')->where('users_group.group_id', 3)->count();
        $params['users'] = User::select('users.*')->join('users_group', 'users.id', '=', 'users_group.user_id')->where('users_group.group_id', 4)->count();
        $params['order'] = Orders::count();
        // dd($params['order']);

        $params['product'] = OrdersProduct::select('*')->selectRaw('count(product_id) as total_product_id')->with(['product'])->orderBy('total_product_id', 'DESC')->groupBy('product_id')->limit(10)->get();

        // $params['product']=Product::selectRaw('count(id) as total_product_id')->limit(10)->get();
        // dd($params['product']->toArray());

        return view('admin.pages.dashboard', compact('orders', 'total'), $params);
    }
}