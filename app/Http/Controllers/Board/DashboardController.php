<?php
namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use CommonHelper;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Orders;
use App\Models\StoresProduct;

class DashboardController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

        $login_id =  Auth::user()->id;
        // dd($login_id);
        $store = User::select('id')->where('parent_id',$login_id)->get()->pluck('id')->toArray();
        // dd($store); 
        $orders_completed=Orders::whereIn('store_id',$store)->where('status','6')->count();
        // dd($orders);
        
        $total=Orders::whereIn('store_id',$store)->where('status','6')->sum('total');

        $customers=User::where('parent_id',$login_id)->count();

        
        $orders = Orders::whereIn('store_id',$store)->count();
        // dd($orders);
        
        $params['store'] = User::select('*')->where('parent_id',$login_id)->count();
        // dd($params['store']);
        $params['product'] = Product::count();
        return view('board.pages.dashboard',compact('orders_completed','total','customers','orders'),$params);
    }
}
