<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cart;
use App\Models\Product;
use App\Models\CartProducts;
use App\Models\StoresProduct;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Validator;
use CommonHelper;

class CartController extends Controller

{

    use ApiResponser;

    public function index(Request $request)
    {        

        $user_id = request()->user()->id;
        $cart = Cart::whereUserId($user_id)->whereOrderId('0')->first();

        if($cart){

            $cart_products = CartProducts::whereCartId($cart->id)->with(['product'])->get();
            $store = User::find($cart->store_id);

            $sub_total = '0';
            if(auth()->user()->user_type == '1'){
                foreach ($cart_products as $key => $value) {
                    if($value->stock > 0){
                        $product_total = $value->qty * $value->product->current_price_business;

                        $sub_total += $product_total;
                        $product_total = number_format((float)$product_total, 2, '.', '');
                        $cart_products[$key]->product_total = $product_total;
                    }else{
                        $cart_products[$key]->product_total = '0';
                    }
                }
            }else{

                foreach ($cart_products as $key => $value) {
                    if($value->stock > 0){
                        $product_total = $value->qty * $value->product->current_price_retail;

                        $sub_total += $product_total;
                        $product_total = number_format((float)$product_total, 2, '.', '');
                        $cart_products[$key]->product_total = $product_total;
                    }else{
                        $cart_products[$key]->product_total = '0';
                    }
                }
            }

            $data['sub_total'] = number_format((float)$sub_total, 2, '.', '');
            $tax = CommonHelper::ConfigGet('tax');
            if($tax > 0){
                $tax_amount = ($tax / 100) * $data['sub_total'];
                $data['tax'] = number_format((float)$tax_amount, 2, '.', '');
                $total = $data['sub_total'] + $tax_amount;
                $data['total'] = number_format((float)$total, 2, '.', '');
            }else{
                $data['total'] = $data['sub_total'];
                $data['tax'] = '0';
            }
            
            $data['id'] = $cart->id;
            $data['store'] = $store;
            $data['cart_products'] = $cart_products;
            $data['cart_products_count'] = count($cart_products);
            return response([
                'status' => true,
                'message' => 'successfully',
                'data' => $data
            ]);

        }else{
            return response([
                'status' => false,
                'message' => 'No any product in cart',
            ]);
        }
        

    }

    protected function add(Request $request)
    {        
        $validator = Validator::make($request->all(),[
            'store_id' => 'required|numeric|not_in:0',
            'product_id' => 'required|numeric|not_in:0',
            'qty' => 'numeric',
            'is_case' => 'boolean'
        ]);

        if($validator->fails()){
           return response([
                'status' => false,
                'message' => $validator->errors()->all(),
            ], 200);
        }

        $store = User::where('id', $request->store_id)->where('status', 1)->first();

        if($store){

            if(isset($request->is_case) && $request->is_case == '1'){
                $request->qty = 12;
            }else{
                if(!isset($request->qty) || $request->qty == '0'){
                    return ([
                        'status' => false,
                        'message' => 'Quantity is required if not case.',
                        'store_deactivated' => FALSE,
                        'product_deleted' => FALSE
                    ]);
                }
            }

            $user_id = request()->user()->id;
            $product = Product::whereId($request->product_id)->whereStatus('1')->first();
            if($product){

                $store_product = StoresProduct::whereUserId($request->store_id)->whereProductId($request->product_id)->where('stock', '>=', $request->qty)->first();

                if($store_product){
                    $cart_data = array(
                        'user_id' => $user_id,
                        'store_id' => $request->store_id,
                        'order_id' => 0
                    );
                    $cart = Cart::firstOrCreate($cart_data);

                    $cart_product_data = array(
                        'cart_id' => $cart->id,
                        'product_id' => $request->product_id
                    );
                    $cart_product = CartProducts::firstOrCreate($cart_product_data);
                    $cart_product->qty = $cart_product->qty + $request->qty;
                    $cart_product->save();

                    return response([
                        'status' => true,
                        'message' => 'Added in cart successfully.',
                        'data' => $store_product,
                        'store_deactivated' => FALSE,
                        'product_deleted' => FALSE
                    ]);
                }else{
                    return response([
                        'status' => false,
                        'message' => 'Out of stock',
                        'store_deactivated' => FALSE,
                        'product_deleted' => TRUE
                    ]);
                }

            }else{
                return response([
                    'status' => false,
                    'message' => 'Product deleted',
                    'product_deleted' => TRUE,
                    'store_deactivated' => FALSE
                ]);
            }

        }else{
            return response([
                'status' => false,
                'store_deactivated' => TRUE,
                'product_deleted' => FALSE,
                'message' => 'Store deactivated',
            ]);
        }
        

    }

    protected function qty_update(Request $request)
    {        
        $validator = Validator::make($request->all(),[
            'cart_product_id' => 'required|numeric|not_in:0',
            'update' => 'required|boolean'
        ]);

        if($validator->fails()){
           return response([
                'status' => false,
                'message' => $validator->errors()->all(),
            ], 200);
        }

        $cart_product = CartProducts::whereId($request->cart_product_id)->with(['cart'])->first();

        if($cart_product){

            $message = 'Quantity updated';
            $data['cart_empty'] = FALSE;
            $data['sub_total'] = '0';
            $data['cart_products_count'] = 0;
            $cart_id = $cart_product->cart_id;

            if($request->update == '1'){

                $check_stock_count = $cart_product->qty + 1;

                $store_product = StoresProduct::whereUserId($cart_product->cart->store_id)->whereProductId($cart_product->product_id)->where('stock', '>=', $check_stock_count)->first();

                if($store_product){
                    $cart_product->qty = $cart_product->qty + 1;
                    $cart_product->save();
                }else{
                    return response([
                        'status' => false,
                        'message' => 'Out of stock'
                    ]);
                }

                
            }else{
                if($cart_product->qty == '1'){
                    
                    $cart_product->forceDelete();
                    $message = 'Product removed from cart'; 

                    $cart_product_other_count = CartProducts::whereCartId($cart_id)->count();
                    if($cart_product_other_count == 0){
                        Cart::find($cart_product->cart_id)->delete();
                        $data['cart_empty'] = TRUE;
                    }
                }else{
                    $cart_product->qty = $cart_product->qty - 1;
                    $cart_product->save();
                }
            }

            $cart_product->product_total = '0';

            if($data['cart_empty'] == FALSE){
                $cart_products = CartProducts::whereCartId($cart_id)->with(['product'])->get();
                $data['cart_products_count'] = count($cart_products);
                $sub_total = 0;
                if(auth()->user()->user_type == '1'){
                    foreach ($cart_products as $key => $value) {
                        if($value->stock > 0){
                            $product_total = $value->qty * $value->product->current_price_business;
                            $sub_total += $product_total;

                            if($value->id == $cart_product->id){
                                $cart_product->product_total = number_format((float)$product_total, 2, '.', '');
                            }
                        }else{
                            if($value->id == $cart_product->id){
                                $cart_product->product_total = '0';
                            }
                        }
                        
                    }
                }else{
                    foreach ($cart_products as $key => $value) {
                        if($value->stock > 0){
                            $product_total = $value->qty * $value->product->current_price_retail;
                            $sub_total += $product_total;

                            if($value->id == $cart_product->id){
                                $cart_product->product_total = number_format((float)$product_total, 2, '.', '');
                            }
                        }else{
                            if($value->id == $cart_product->id){
                                $cart_product->product_total = '0';
                            }
                        }
                        
                    }
                }

                $data['sub_total'] = number_format((float)$sub_total, 2, '.', '');
                $tax = CommonHelper::ConfigGet('tax');
                if($tax > 0){
                    $tax_amount = ($tax / 100) * $data['sub_total'];
                    $data['tax'] = number_format((float)$tax_amount, 2, '.', '');
                    $total = $data['sub_total'] + $tax_amount;
                    $data['total'] = number_format((float)$total, 2, '.', '');
                }else{
                    $data['total'] = $data['sub_total'];
                    $data['tax'] = '0';
                }

            }

            $data['cart_product'] = $cart_product;

            return response([
                'status' => true,
                'data' => $data,
                'message' => $message
            ]);
        }else{
            return response([
                'status' => false,
                'message' => 'Cart data not found.'
            ]);
        }

        

    }

    protected function remove_product(Request $request)
    {        
        $validator = Validator::make($request->all(),[
            'cart_product_id' => 'required|numeric|not_in:0',            
        ]);

        if($validator->fails()){
           return response([
                'status' => false,
                'message' => $validator->errors()->all(),
            ], 200);
        }

        $cart_product = CartProducts::find($request->cart_product_id);                
        if($cart_product) 
        {
            $data['sub_total'] = $data['total'] = $data['tax'] = $sub_total = '0';
            $data['cart_empty'] = FALSE;
            $data['cart_products_count'] = 0;

            $cart_products = CartProducts::whereCartId($cart_product->cart_id)->get();

            if(count($cart_products) == 1) 
            {
                Cart::where('id', $cart_product->cart_id)->delete();
                $cart_product->forceDelete();
                $data['cart_empty'] = TRUE;
            } else {
                $cart_product->forceDelete();

                $cart_products = CartProducts::whereCartId($cart_product->cart_id)->with(['product'])->get();
                $data['cart_products_count'] = count($cart_products);
                if(auth()->user()->user_type == '1'){
                    foreach ($cart_products as $key => $value) {
                        if($value->stock > 0){
                            $sub_total += $value->qty * $value->product->current_price_business;
                        }
                    }
                }else{
                    foreach ($cart_products as $key => $value) {
                        if($value->stock > 0){
                            $sub_total += $value->qty * $value->product->current_price_retail;
                        }
                    }
                }

                $data['sub_total'] = number_format((float)$sub_total, 2, '.', '');

                $tax = CommonHelper::ConfigGet('tax');
                if($tax > 0){
                    $tax_amount = ($tax / 100) * $data['sub_total'];
                    $data['tax'] = number_format((float)$tax_amount, 2, '.', '');
                    $total = $data['sub_total'] + $tax_amount;
                    $data['total'] = number_format((float)$total, 2, '.', '');
                }else{
                    $data['total'] = $data['sub_total'];
                    $data['tax'] = '0';
                }

            }

            return response([
                'status' => true,
                'data' => $data,
                'message' => 'Product removed successfully',
            ]);
        } else {
            return response([
                'status' => false,
                'message' => 'Cart data not found.'
            ]);
        }

        

    }

    protected function clear(Request $request)
    {        
        $user_id = request()->user()->id;

        $cart = Cart::whereUserId($user_id)->whereOrderId('0')->first();
        if($cart){
            $cart->delete();

            return response([
                'status' => true,
                'data' => [],
                'message' => 'Cart products removed successfully',
            ]);
        }  else {
            return response([
                'status' => false,
                'message' => 'Cart data not found.'
            ]);
        }        
        

    }

    protected function verify(Request $request)
    {        
        $user_id = request()->user()->id;

        $cart = Cart::whereUserId($user_id)->with('cart_products.product','store')->whereOrderId('0')->first();
        if($cart){
            
            if($cart->store->status == 1){
                $response = array(
                    'status' => true,
                    'message' => 'All data available',
                    'store_deactivated' => FALSE,
                    'product_deleted' => FALSE,
                    'cart_available' => TRUE
                );
                $message = '';
                foreach ($cart->cart_products as $key => $value) {
                    $product = Product::where('id', $value->product_id)->where('status', 1)->first();

                    if(isset($value->product) && $product){

                        $store_product = StoresProduct::whereUserId($cart->store_id)->whereProductId($value->product_id)->first();
                        //return $store_product;
                        if($store_product){
                            $product = Product::withTrashed()->where('id', $value->product_id)->first();

                            if($store_product->stock == 0){
                                $message .= $product->name.' out of stock. ';

                                $cart_products = $cart->cart_products->toArray();
                                if(count($cart_products) == 1) 
                                {
                                    Cart::where('id', $cart->id)->delete();
                                }
                                CartProducts::where('id', $value->id)->forceDelete();

                                $response['status'] = FALSE;
                                $response['message'] = $message;
                                $response['product_deleted'] = TRUE;

                                // return response([
                                //     'status' => false,
                                //     'message' => $message,
                                //     'store_deactivated' => FALSE,
                                //     'product_deleted' => TRUE,
                                //     'cart_available' => TRUE
                                // ]);

                            }else if($store_product->stock < $value->qty){
                                $message .= $product->name.' available for '.$store_product->stock.' quantity only. ';
                                // return response([
                                //     'status' => false,
                                //     'message' => $message,
                                //     'store_deactivated' => FALSE,
                                //     'product_deleted' => FALSE,
                                //     'cart_available' => TRUE
                                // ]);

                                $response['status'] = FALSE;
                                $response['message'] = $message;

                            }else{

                            }

                        }else{
                            $product = Product::find($value->product_id);
                            $message .= $product->name.' out of stock. ';
                            
                            $cart_products = $cart->cart_products->toArray();
                            if(count($cart_products) == 1) 
                            {
                                Cart::where('id', $cart->id)->delete();
                            }
                            CartProducts::where('id', $value->id)->forceDelete();
                            
                            $response['status'] = FALSE;
                            $response['message'] = $message;
                            $response['product_deleted'] = TRUE;


                            // return response([
                            //     'status' => false,
                            //     'message' => $message,
                            //     'store_deactivated' => FALSE,
                            //     'product_deleted' => TRUE,
                            //     'cart_available' => TRUE
                            // ]);
                        }
                    }else{
                        $product = Product::withTrashed()->where('id', $value->product_id)->first();
                        $message .= $product->name.' not available. ';

                        if(count($cart->cart_products) == 1){
                            Cart::where('id', $cart->id)->delete();
                        }
                        CartProducts::where('id', $value->id)->delete();

                        // return response([
                        //     'status' => false,
                        //     'message' => $message,
                        //     'store_deactivated' => FALSE,
                        //     'product_deleted' => TRUE,
                        //     'cart_available' => TRUE
                        // ]);

                        $response['status'] = FALSE;
                        $response['message'] = $message;
                        $response['product_deleted'] = TRUE;

                        // break;
                    }
                }

                return response($response);

            }else{
                return response([
                    'status' => false,
                    'message' => 'Store deactivated',
                    'store_deactivated' => TRUE,
                    'product_deleted' => FALSE,
                    'cart_available' => TRUE
                ]);
            }

        }  else {
            return response([
                'status' => false,
                'message' => 'Cart data not found.',
                'store_deactivated' => FALSE,
                'product_deleted' => FALSE,
                'cart_available' => FALSE
            ]);
        }        
        

    }

}
