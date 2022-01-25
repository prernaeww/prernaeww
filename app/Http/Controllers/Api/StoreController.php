<?php



namespace App\Http\Controllers\Api;



use App\Http\Controllers\Controller;

use App\Http\Resources\StoreDetailsResource;
use App\Http\Resources\Api\ProductListResource;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Banner;
use App\Models\Cart;
use App\Models\StoresProduct;
use App\Models\FavoriteProduct;
use App\Models\FavoriteStore;
use Illuminate\Http\Request;
use Validator;

class StoreController extends Controller

{
    protected function store_list(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'latitude' => 'required',
            'longitude' => 'required',
            'user_id' => '',
            'favorite' => '',
            'text' => ''
        ]);

        if($validator->fails()){
           return response([
                'status' => false,
                'message' => $validator->errors()->all(),
            ], 200);
        }

        $fav_stores = FavoriteStore::whereUserId($request->user_id)
                        ->with(['store' => function ($query) {
                            $query->where('status', '1');
                        }])->get()->pluck('store_id')->toArray();
        //return $fav_stores;
        if(isset($request->favorite) && $request->favorite == '1'){
            if(isset($request->user_id) && $request->user_id != ''){
                

                if(count($fav_stores) == 0){
                    return response([
                        'status' => false,
                        'message' => 'No any favorite store found',
                    ], 200);
                }
            }else{
                return response([
                    'status' => false,
                    'message' => 'User id is required for favorite stores.',
                ], 200);
            }
        }

        $where = array(
            ['users_group.group_id' ,'=', '3'],
            ['latitude' ,'!=', ''],
            ['longitude' ,'!=', ''],
            ['status' ,'=', '1']
        );

        if(isset($request->text) && $request->text != ''){
            $where[] = array('first_name' ,'like', '%'.$request->text.'%');
        }

        $haversine = "(6371 * acos(cos(radians(".$request->latitude.")) 
                     * cos(radians(latitude)) 
                     * cos(radians(longitude) 
                     - radians(".$request->longitude.")) 
                     + sin(radians(".$request->latitude.")) 
                     * sin(radians(latitude))))";

        if(isset($request->favorite) && $request->favorite == '1'){
            $stores = User::select('users.*')->selectRaw("{$haversine} AS distance")->join('users_group', 'users.id', '=', 'users_group.user_id')->whereIn('users.id', $fav_stores)->orderBy('distance', 'ASC')->where($where)->get();

            if(isset($stores) && count($stores)){
                foreach ($stores as $key => $value) {
                    $stores[$key]['favorite'] = TRUE;
                    $stores[$key]['distance'] = number_format((float)$value->distance, 2, '.', '');
                }
            }

        }else{

            $stores = User::select('users.*')->selectRaw("{$haversine} AS distance")->join('users_group', 'users.id', '=', 'users_group.user_id')->where($where)->where('status', '1')->orderBy('distance', 'ASC')->get();
            if(isset($stores) && count($stores)){
                foreach ($stores as $key => $value) {

                    if($value->distance <= '10'){
                        $stores[$key]['favorite'] = FALSE;
                        $stores[$key]['distance'] = number_format((float)$value->distance, 2, '.', '');

                        if(isset($request->user_id) && $request->user_id != ''){
                            if(isset($fav_stores) && count($fav_stores) > 0) {
                                //$fav_stores = $fav_stores->toArray();
                                if(in_array($value->id, $fav_stores)){
                                    $stores[$key]['favorite'] = TRUE;
                                }
                            }
                            
                        }
                    }else{
                        unset($stores[$key]);
                    }
                    
                }
                //$stores = array_values($stores);
            }
        }

        return ([
                'status' => true,
                'data' => $stores,
                'message' => ''
            ]);
    }

    protected function product(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'store_id' => 'required|numeric|not_in:0',
            'user_id' => 'numeric|not_in:0'
        ]);

        if($validator->fails()){
           return response([
                'status' => false,
                'message' => $validator->errors()->all(),
            ], 200);
        }

        $limit = 11;
        $store = User::where('id', $request->store_id)->where('status', '1')->first();   
        $data['category'] = Category::whereStatus('1')->get();
        $data['cart_products_count'] = 0;

        if(isset($store)){

            $data['banner'] = Banner::whereUserId($store->parent_id)->whereDate('start_date', '<=', date('Y-m-d'))->whereDate('end_date', '>=', date('Y-m-d'))->orderBy('id', 'DESC')->get();
            $data['product_on_sale'] = $product_on_sale = $data['new_arrived'] = [];
            $store_products = StoresProduct::whereUserId($store->id)->where('stock', '>', 0)->get();
            if(isset($store_products)){

                $products = $store_products->pluck('product_id');
                $product_data = Product::whereStatus("1")->orderBy('id','DESC')->whereIn('id', $products)->limit($limit)->get()->toArray();                

                foreach ($product_data as $key => $value) {
                    $product_data[$key]['favorite'] = FALSE;
                }

                if(isset($request->user_id) && $request->user_id != ''){
                    $favorite_products = FavoriteProduct::whereUserId($request->user_id)->whereStoreId($request->store_id)->get()->pluck('product_id')->toArray();

                    if($favorite_products && count($favorite_products) > 0){
                        foreach ($product_data as $key => $value) {
                            if(in_array($value['id'], $favorite_products)){
                                $product_data[$key]['favorite'] = TRUE;
                            }
                        }
                    }

                    $cart = Cart::whereUserId($request->user_id)->whereOrderId('0')->with(['cart_products'])->first();
                    if($cart){
                        $cart_products_count = count($cart->cart_products);
                        $data['cart_products_count'] = $cart_products_count;
                    }
                }

                $user_id = isset($request->user_id) ? $request->user_id : null;
                $user_type = User::whereId($user_id)->value('user_type');
                $discount = $user_type == 1 ? 'business_discount' : 'retail_discount';        

                foreach ($product_data as $key => $value) {

                                
                    if($value[$discount] > 0){
                        $product_on_sale[] = $product_data[$key];
                    }
                }

                if(count($product_data) > 10){
                    $product_data = array_slice($product_data, 0, 10);
                    $data['new_arrived_view_all'] = TRUE;
                }else{
                    $data['new_arrived_view_all'] = FALSE;
                }

                if(count($product_on_sale) > 10){
                    $product_on_sale = array_slice($product_on_sale, 0, 10);
                    $data['product_on_sale_view_all'] = TRUE;
                }else{
                    $data['product_on_sale_view_all'] = FALSE;
                }
                
                $data['product_on_sale'] = $product_on_sale;
                $data['new_arrived'] = $product_data;
                
            }

            return ([
                'status' => true,
                'message' => 'successfully',
                'data' => $data,
                'store_deactivated' => FALSE
            ]);

        }else{
            return ([
                'status' => FALSE,
                'message' => 'Store deactivated',
                'store_deactivated' => TRUE
            ]);

        }     
        

        
    }

    protected function product_view_all(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'store_id' => 'required|numeric|not_in:0',
            'is_product_on_sale' => '',
            'category_id' => '',
            'text' => '',
            'user_id' => 'numeric|not_in:0'
        ]);

        if($validator->fails()){
           return response([
                'status' => false,
                'message' => $validator->errors()->all(),
            ], 200);
        }

        $store_id = $request->store_id;
        $store = User::where('id', $store_id)->where('status', 1)->first();
        if($store){

            $store_products = StoresProduct::whereUserId($store_id)->where('stock', '>', 0)->get();
            $product_data = [];
            if(isset($store_products)){

                $products = $store_products->pluck('product_id')->toArray();

                if(isset($request->text) && $request->text != ''){
                    $mathched_category = Category::where('name', 'like', '%' . $request->text . '%')->whereStatus('1')->get()->pluck('id')->toArray();
                    if($mathched_category){
                        $matched_products = Product::whereNotIn('id', $products)->whereStatus('1')->whereIn('category_id', $mathched_category)->pluck('id')->toArray();
                    }
                    
                }

                
                $category_id = $request->category_id;

                $category = Category::where('id', $category_id)->whereStatus('1')->first();
                if($category){
                    $category_condition = ($request->category_id && $request->category_id != '')?$request->category_id:NULL;
                }else{
                    $category_condition = NULL;
                }
                $product = Product::query()->whereStatus("1")->whereIn('id', $products);

                $product->when($category_condition, function ($query) use ($category_id) {
                    $query->where('category_id', $category_id)->orderBy('id','DESC');
                });

                $onsale_condition = ($request->is_product_on_sale && $request->is_product_on_sale == '1')?$request->is_product_on_sale:NULL;
                $is_product_on_sale = $request->is_product_on_sale;

                $user_id = isset($request->user_id) ? $request->user_id : null;
                $user_type = User::whereId($user_id)->value('user_type');
                $discount = $user_type == 1 ? 'business_discount' : 'retail_discount';       

                $product->when($onsale_condition, function ($query) use ($discount) {
                    $query->where($discount, '>', '0')->orderBy($discount, 'DESC');
                });

                $text_condition = (isset($request->text) && $request->text != '')?$request->text:NULL;
                $text = $request->text;

                // $product->when($text_condition, function ($query) use ($matched_products) {
                $product->when($text_condition, function ($query) use ($text) {
                    $query->where(function($query) use($text){
                        $query->where('name', 'like', '%' . $text . '%')->orWhereHas('category',function($query) use($text){
                            $query->where('name', 'like', '%' . $text . '%');
                        });
                    });
                });

                $product_data = $product->paginate(10);
                
                if(isset($product_data) && count($product_data) > 0){

                    if(isset($request->user_id) && $request->user_id != ''){
                        $favorite_products = FavoriteProduct::whereUserId($request->user_id)->whereStoreId($request->store_id)->get()->pluck('product_id')->toArray();
                    }

                    foreach ($product_data as $key => $value) {
                        $product_data[$key]['favorite'] = FALSE;

                        if(isset($favorite_products) && count($favorite_products) > 0){
                            if(in_array($value['id'], $favorite_products)){
                                $product_data[$key]['favorite'] = TRUE;
                            }
                        }
                    }
                }
                
            }

            return (new ProductListResource($product_data))->additional([
                'status' => TRUE,
                'store_deactivated' => FALSE
            ]);

        }else{
            return response([
                'status' => false,
                'store_deactivated' => TRUE,
                'message' => 'Store deactivated',
            ]);
        }
        

    }

    protected function search(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'category_id' => 'numeric|not_in:0',
            'store_id' => 'required|numeric|not_in:0',
            'text' => 'required',
            'user_id' => 'numeric|not_in:0'
        ]);

        if($validator->fails()){
           return response([
                'status' => false,
                'message' => $validator->errors()->all(),
            ], 200);
        }

        $store_id = $request->store_id;

        $search_result = [];

        if(isset($request->category_id) && $request->category_id != '' && $request->category_id != '0'){

        }else{
            $mathched_category = Category::where('name', 'like', '%' . $request->text . '%')->where('status', 1)->get();

            if($mathched_category){
                foreach ($mathched_category as $key => $value) {
                    $temp = array(
                        'id' => $value->id,
                        'name' => $value->name,
                        'type' => 'category'
                    );
                    array_push($search_result, $temp);
                }
            }
        }

        

        $store_products = StoresProduct::whereUserId($store_id)->where('stock', '>', 0)->get();
        if($store_products){
            $products = $store_products->pluck('product_id')->toArray();

            $product = Product::query()->whereStatus("1")->whereIn('id', $products);
            $text = $request->text;
            $product->where(function($query) use ($text){
                $query->where('name', 'like', '%' . $text . '%');
                $query->orWhere('item_code', 'like', '%' . $text . '%');
            });

            $category_condition = ($request->category_id && $request->category_id != '')?$request->category_id:NULL;
            $category_id = $request->category_id;
            $product->when($category_condition, function ($query) use ($category_id) {
                $query->where('category_id', $category_id);
            });

            $product_data = $product->get();

            if($product_data){
                foreach ($product_data as $key => $value) {
                    $temp = array(
                        'id' => $value->id,
                        'name' => $value->name,
                        'item_code' => $value->item_code,
                        'category_name' => $value->category_name,
                        'type' => 'product'
                    );
                    array_push($search_result, $temp);
                }
            }

        }

        return ([
            'status' => true,
            'message' => '',
            'data' => $search_result
        ]);

    }

    protected function details(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'store_id' => 'required|numeric|not_in:0',
            'product_id' => 'required|numeric|not_in:0',
            'user_id' => 'numeric|not_in:0'
        ]);

        if($validator->fails()){
           return response([
                'status' => false,
                'message' => $validator->errors()->all(),
            ], 200);
        }

        $store_id = $request->store_id;
        $product_id = $request->product_id;
        $store_product = StoresProduct::whereUserId($store_id)->whereProductId($product_id)->where('stock', '>', 0)->with(['store'])->first();        
        if($store_product){

            if($store_product->store->status != 1){
                return ([
                    'status' => FALSE,
                    'message' => 'Store deactivated',
                    'store_deactivated' => TRUE,
                    'product_deleted' => FALSE
                ]);
            }

            $product_data = Product::whereId($product_id)->whereStatus('1')->first();
            if($product_data){
                $product_data = $product_data->toArray();
                $product_data['favorite'] = FALSE;
                $product_data['stock'] = $store_product->stock;
                $product_data['cart_store_id'] = 0;

                if(isset($request->user_id) && $request->user_id != ''){
                    $favorite = FavoriteProduct::whereUserId($request->user_id)->whereStoreId($store_id)->whereProductId($product_id)->first();
                    if($favorite){
                        $product_data['favorite'] = TRUE;
                    }

                    $cart = Cart::whereUserId($request->user_id)->whereOrderId('0')->first();
                    if($cart){
                        $product_data['cart_store_id'] = $cart->store_id;
                    }
                    
                }
            
                return ([
                    'status' => true,
                    'message' => '',
                    'data' => $product_data,
                    'product_deleted' => FALSE,
                    'store_deactivated' => FALSE
                ]);

            }else{
                return ([
                    'status' => false,
                    'message' => 'Product not found',
                    'product_deleted' => TRUE,
                    'store_deactivated' => FALSE
                ]);
            }
            
        }else{
            return ([
                'status' => false,
                'message' => 'Product not found',
                'product_deleted' => TRUE,
                'store_deactivated' => FALSE
            ]);
        }       

    }

    public function deals(Request $request) {

        $validator = Validator::make(request()->all(), [
            'user_id' => 'numeric|not_in:0',
            'latitude' => 'required|not_in:0',
            'longitude' => 'required|not_in:0'
        ]);
        if($validator->fails()){
           return response([
                'status' => false,
                'message' => $validator->errors()->all(),
            ], 200);
        }

        if(isset($request->user_id) && $request->user_id != ''){
            $user_id = $request->user_id;
            $user_type = User::whereId($user_id)->value('user_type');
            $discount = $user_type == 1 ? 'business_discount' : 'retail_discount';
        }else{
            $discount = 'retail_discount';
        }
             
        $discounted_products = Product::where($discount, '>', 0)->whereStatus('1')->orderBy($discount , 'DESC')->with(['stores'])->orderBy('id', 'DESC')->get();
        
        if($discounted_products){
            $stores = [];
            $discounted_products = $discounted_products->toArray();
            $find_store_ids = [];
            foreach ($discounted_products as $key => $value) {
                $discounted_products[$key]['available_stores'] = [];
                $discounted_products[$key]['favorite'] = FALSE;
                if(count($value['stores']) > 0){
                    foreach($value['stores'] as $s_key => $s_value){
                        if($s_value['stock'] > 0){
                            if(!in_array($s_value['user_id'], $find_store_ids)){
                                array_push($find_store_ids, $s_value['user_id']);
                            }

                            if(!in_array($s_value['user_id'], $discounted_products[$key]['available_stores'])){
                                array_push($discounted_products[$key]['available_stores'], $s_value['user_id']);
                            }
                        }
                    }
                }
            }

            if(count($find_store_ids) > 0){

                $coordinates = array(
                    "latitude" => $request->latitude,
                    "longitude" => $request->longitude
                );   

                $stores = User::whereIn('id', $find_store_ids)->whereStatus('1')->isWithinGetDistance($coordinates)->get();

                if($stores){

                    $stores = $stores->toArray();

                    foreach ($stores as $key => $value) {
                        $stores[$key]['products'] = [];
                        $stores[$key]['view_all'] = FALSE;
                        $stores[$key]['store_name'] = $value['first_name'].' '.$value['last_name'];
                        $stores[$key]['distance'] = number_format((float)$value['distance'], 2, '.', '');
                    }
                    foreach ($stores as $s_key => $s_value) {
                        foreach ($discounted_products as $key => $value) {
                            if(in_array($s_value['id'], $value['available_stores'])){
                                if(count($stores[$s_key]['products']) <= 10){
                                    $stores[$s_key]['products'][] = $value;
                                }else{
                                    $stores[$s_key]['view_all'] = TRUE;
                                }
                                
                            }
                        }
                    }

                    if(isset($request->user_id) && $request->user_id != ''){
                        $favorite_products = FavoriteProduct::whereUserId($request->user_id)->get();

                        if($favorite_products && count($favorite_products) > 0){
                            foreach ($favorite_products as $f_key => $f_value) {
                                foreach ($stores as $key => $value) {
                                    if($value['id'] == $f_value->store_id){
                                        foreach ($value['products'] as $p_key => $p_value) {
                                            if($p_value['id'] == $f_value->product_id){
                                                $stores[$key]['products'][$p_key]['favorite'] = TRUE;
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
                        'data' => $stores,
                    ]);

                }else{
                    return ([
                        'status' => false,
                        'message' => 'Product not found',
                    ]);
                }

            }else{
                return ([
                    'status' => false,
                    'message' => 'Product not found',
                ]);
            }

        }else{
            return ([
                'status' => false,
                'message' => 'Product not found',
            ]);
        }      

    }
    

}

