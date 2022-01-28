<?php
namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use CommonHelper;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Orders;
use App\Models\StoresProduct;
use App\Models\OrdersProduct;


class DashboardController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        //dd('helo');

        $login_id =  Auth::user()->id;

        $orders=Orders::where('store_id',$login_id)->where('status','6')->count();
        $orders_total=Orders::where('store_id',$login_id)->count();

    
        $earnings=Orders::where('store_id',$login_id)->where('status','6')->sum('total');
  

        

        $storeProduct = StoresProduct::select('*')->where('user_id',$login_id)->get();
        $productId=$storeProduct->pluck('product_id');
        $data = Product::select('*')->orderBy('id','asc')->whereIn('id',$productId)->get();


            $params['products']  = count($data);
            // StoresProduct::select('*')->groupBy('product_id')->where('user_id',$login_id)->count();
        // dd($params['products']);
        
        $params['store'] = User::select('users.*')->join('users_group', 'users.id', '=', 'users_group.user_id')->where('users_group.group_id',3)->count();
        $params['order'] = Orders::count();


        $get_id=Orders::select('*')->where('store_id',$login_id)->get()->pluck('id');
        // dd($get_id);
        $params['top_seilling'] = OrdersProduct::select('*')->selectRaw('count(product_id) as total_product_id')->whereIn('order_id',$get_id)->with(['product'])->orderBy('total_product_id', 'DESC')->groupBy('product_id')->limit(10)->get();
        return view('store.pages.dashboard',compact('orders','orders_total','earnings'),$params);
    }
}
