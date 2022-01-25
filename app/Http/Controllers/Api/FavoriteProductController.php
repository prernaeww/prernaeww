<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FavoriteProduct;
use App\Models\Product;
use App\Models\Variant;
use App\Models\User;
use App\Models\StoresProduct;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavoriteProductController extends Controller {
    use ApiResponser;

    public function index(Request $request) {

        $favorite_products = FavoriteProduct::whereUserId(request()->user()->id)->with([
            'store' => function ($query) {
                $query->where('status', 1);
            }
            , 'product' => function ($query) {
                $query->where('status', 1);
            }])->orderBy('id', 'DESC')->get(); 
        $store_data = array();

        
        if($favorite_products){
            $stores = [];

            foreach ($favorite_products as $key => $value) {
                if(isset($value->store)){
                    $stores[] = $value->store_id;
                }
                if(isset($value->product)){
                    $product[] = $value->product_id;
                }
            }

            
            $temp = [];

            if(count($stores)){

                $stores = array_unique($stores);
                $stores = array_values($stores);

                //return $favorite_products;

                foreach ($favorite_products as $key => $value) {

                    if(isset($value->product) && !is_null($value->product) && $value->stock > 0){
                        foreach ($stores as $s_key => $s_value) {
                            if($value->store_id == $s_value){
                                if(!in_array($s_value, $temp)){

                                    $store_data[] = array(
                                        'id' => $s_value,
                                        'store_name' => $value->store->first_name.' '.$value->store->last_name,
                                        'view_all' => FALSE,
                                        'address' => $value->store->address,
                                        'products' => []
                                    );
                                }
                                
                                array_push($temp, $s_value);
                            }
                        }
                    }
                }

                if(isset($store_data) && count($store_data) > 0){
                    foreach ($store_data as $key => $value) {
                        foreach ($favorite_products as $f_key => $f_value) {
                            if($value['id'] == $f_value->store_id){

                                if(isset($f_value->product) && !is_null($f_value->product) && $f_value->stock > 0){
                                    if(count($store_data[$key]['products']) <= 10){
                                        $f_value->product->favorite = TRUE;
                                        $store_data[$key]['products'][] = $f_value->product;
                                    }else{
                                        $store_data[$key]['view_all'] = TRUE;
                                    }
                                }

                            }
                        }
                    }

                }
            }
            
            return response([
                'status' => true,
                'message' => '',
                'data' => $store_data,
            ]);

        }
                
        return response([
            'status' => true,
            'message' => '',
            'data' => $store_data,
        ]);

    }

    /**
     *  makes a service provider favourite or removes him from favourite
     */

    public function add(Request $request) {
        $validator = Validator::make(request()->all(), [
            'product_id' => 'required',
            'store_id' => 'required',
        ]);

        if (!$validator->fails()) {
            if (Product::whereId($request->product_id)->whereStatus('1')->first()) {

                $store = User::where('id', $request->store_id)->where('status', 1)->first();
                if($store){

                    $store_product = StoresProduct::whereUserId($request->store_id)->whereProductId($request->product_id)->where('stock','>', 0)->first();
                    if($store_product){

                        if (FavoriteProduct::whereProductId($request->product_id)->whereUserId($request->user()->id)->whereStoreId($request->store_id)->first()) {

                            return response([
                                'status' => false,
                                'store_deactivated' => FALSE,
                                'product_deleted' => FALSE,
                                'message' => 'Product already exits as favorite',
                            ]);
                            
                        }else{
                            $favorite_product = new FavoriteProduct;
                            $favorite_product->user_id = $request->user()->id;
                            $favorite_product->product_id = $request->product_id;
                            $favorite_product->store_id = $request->store_id;
                            $favorite_product->save();

                            if (isset($favorite_product)) {
                                return response([
                                    'status' => true,
                                    'store_deactivated' => FALSE,
                                    'product_deleted' => FALSE,
                                    'message' => 'Product add as favorite successfully',
                                ]);
                            }
                        }
                        
                    }else{
                        return response([
                            'status' => false,
                            'store_deactivated' => FALSE,
                            'product_deleted' => TRUE,
                            'message' => 'Product not found'
                        ]);
                    }
                    
                }else{
                    return response([
                        'status' => false,
                        'store_deactivated' => TRUE,
                        'product_deleted' => FALSE,
                        'message' => 'Store deactivated'
                    ]);
                }

            } else {
                return response([
                    'status' => false,
                    'store_deactivated' => FALSE,
                    'product_deleted' => TRUE,
                    'message' => 'Product not found',
                ]);
            }
        }
        return $this->errorResponse($validator->messages(), true);
    }

    /**



     *  returns the favorites of the customer



     */

    // public function remove(FavoriteProductRequest $request)

    public function remove(Request $request) {

        $validator = Validator::make(request()->all(), [
            'product_id' => 'required',
            'store_id' => 'required',
        ]);

        if (!$validator->fails()) {

            $store = User::where('id', $request->store_id)->where('status', 1)->first();
            if($store){

                $product = Product::whereId($request->product_id)->whereStatus('1')->first();

                if($product){

                    $store_product = StoresProduct::whereUserId($request->store_id)->whereProductId($request->product_id)->where('stock','>', 0)->first();

                    if($store_product){
                        FavoriteProduct::whereProductId($request->product_id)->whereStoreId($request->store_id)->whereUserId($request->user()->id)->delete();
                        return response([
                            'status' => true,
                            'store_deactivated' => FALSE,
                            'product_deleted' => FALSE,
                            'message' => 'Product remove successfully',
                        ]);
                    }else{
                        return response([
                            'status' => false,
                            'store_deactivated' => FALSE,
                            'product_deleted' => TRUE,
                            'message' => 'Product not found'
                        ]);
                    }
                    
                }else{
                    return response([
                        'status' => false,
                        'store_deactivated' => FALSE,
                        'product_deleted' => TRUE,
                        'message' => 'Product not found'
                    ]);
                }
                
            }else{
                return response([
                    'status' => false,
                    'store_deactivated' => TRUE,
                    'product_deleted' => FALSE,
                    'message' => 'Store deactivated'
                ]);
            }

        }

        return $this->errorResponse($validator->messages(), true);

    }

    public function product_view_all(Request $request) {

        $validator = Validator::make(request()->all(), [            
            'store_id' => 'required',
        ]);
        if (!$validator->fails()) {
            $user_id = $request->user()->id;
            $store = User::where('id', $request->store_id)->where('status', 1)->first();

            if($store){
                $favorite_products = FavoriteProduct::whereUserId(request()->user()->id)->whereStoreId($request['store_id'])->orderBy('id', 'DESC')->get(); 
                $store_data = array();
                $store_data_ = array();
                if($favorite_products){
                    $stores = $favorite_products->pluck('store_id')->toArray();
                    $products = $favorite_products->pluck('product_id')->toArray();

                    $stores = array_unique($stores);
                    $stores = array_values($stores);

                    $temp = [];
                    foreach ($favorite_products as $key => $value) {
                        foreach ($stores as $s_key => $s_value) {
                            if($value->store_id == $s_value){
                                if(!in_array($s_value, $temp)){
                                    $store_data_ = array(
                                        'id' => $s_value,
                                        'store_name' => $store->first_name.' '.$store->last_name,
                                        'product_ids' => [],
                                        'products' => []
                                    );
                                }
                                
                                array_push($temp, $s_value);
                            }
                        }
                    }

                    // foreach ($store_data as $key => $value) {
                        foreach ($favorite_products as $f_key => $f_value) {
                            if($store_data_['id'] == $f_value->store_id){
                                array_push($store_data_['product_ids'], $f_value->product_id);
                            }
                        }
                    // }
                    $product_data = Product::whereStatus("1")->orderBy('id','DESC')->whereIn('id', $products)->get();

                    // foreach ($store_data as $key => $value) {
                        foreach ($product_data as $v_key => $v_value) {
                            if(in_array($v_value->id, $store_data_['product_ids'])){
                                $store_data[] = $v_value;
                                // $store_data['products'][] = $v_value;
                            }
                        }
                        unset($store_data['product_ids']);
                    // }
                }
                        
                return response([
                    'status' => true,
                    'message' => '',
                    'store_deactivated' => FALSE,
                    'data' => $store_data,
                ]);

            }else{
                return response([
                    'status' => false,
                    'store_deactivated' => TRUE,
                    'message' => 'Store deactivated',
                ]);
            }
            

        }

        return $this->errorResponse($validator->messages(), true);

    }

}
