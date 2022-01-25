<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoresProduct;
use App\Models\Product;
use App\Models\User;
use App\Models\Cart;
use App\Models\CartProducts;
use App\Models\FavoriteStore;
use App\Models\FavoriteProduct;
use DataTables;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
            
    if ($request->ajax())
       {
            $data = StoresProduct::select('*')->with(['store','product'])->get();
            return Datatables::of($data)
            ->addIndexColumn()

            ->editColumn('product', function($row) {
                    
                    $item_code = $row['product_id'];
                    return  $item_code;
                })
            ->editColumn('store', function ($row)
            {
                
                $store_name = $row['user_id'];
                return  $store_name; 
            })
             ->editColumn('product', function ($row)
            {
                
                $product_name = $row['product_id'];
                return  $product_name; 
            })
            ->editColumn('action', function ($row)
            {
               $btn = '<a href="'.route('admin.inventory.edit',$row['id']).'" class="mr-2"><i class="fa fa-edit"></i></a>';
               $btn .= '<a href="'.route('admin.inventory.destroy', $row['id']).'" data-url="inventory" data-id="'.$row["id"].'" data-popup="tooltip" onclick="delete_notiflix(this);return false;" data-token="'.csrf_token().'" ><i class="fa fa-trash"></i></a>';
               return $btn;
            })
            ->rawColumns(['product_code','product_name','store_name','action'])
            ->make(true);
       }
       else
       {
           $columns = [
               ['data' => 'id','name' => 'id','title' => "Id"],
               ['data' => 'product_code', 'name' => 'product_code','title' => __("Item Code")],
               ['data' => 'product_name', 'name' => 'product_name','title' => __("Product Name")], 
               ['data' => 'store_name', 'name' => 'store_name','title' => __("Store Name") ,'searchable'=>true],
               ['data' => 'stock', 'name' => 'stock','title' => __("Stock") ,'searchable'=>true],
               ['data' => 'action', 'name' => 'action','title' => "Action",'searchable'=>false,'orderable'=>false]];
           $params['dateTableFields'] = $columns;
           $params['dateTableUrl'] = route('admin.inventory.index');
           $params['dateTableTitle'] = "Inventory";
           $params['dataTableId'] = time();
           $params['addUrl'] = route('admin.inventory.create');
           return view('admin.pages.inventory.index',$params);
       }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $params['pageTittle'] = "Add Inventory";
        $params['backUrl'] = route('admin.inventory.index');
        $data = User::select('users.*')->join('users_group', 'users.id', '=', 'users_group.user_id')->where('users_group.group_id',2)->get();
        $product=Product::get(['id','item_code']);
        return view('admin.pages.inventory.post',compact('data','product'),$params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'user_id' => 'required',
            'product_id' => 'required',
            'stock' => 'required|numeric|min:0',
           
        ]);

        $store_product = StoresProduct::where('user_id', $request->user_id)->where('product_id', $request->product_id)->first();

        if($store_product){
            return redirect()->route('admin.inventory.index')->with('error','Inventory data already exist.');
        }
        $user = StoresProduct::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'stock' => $request->stock,
        ]);

        return redirect()->route('admin.inventory.index')->with('success','Inventory created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $params['pageTittle'] = "Edit Inventory";
        $params['storeproduct'] = StoresProduct::find($id);
        $params['backUrl'] = route('admin.inventory.index');
        $params['board'] = $data = User::select('users.*')->join('users_group', 'users.id', '=', 'users_group.user_id')->where('users_group.group_id',2)->get();
        $params['board_id'] = User::where('id', $params['storeproduct']->user_id)->value('parent_id');

        $params['store'] = User::select('users.*')->join('users_group', 'users.id', '=', 'users_group.user_id')->where('users_group.group_id',3)->where('parent_id',$params['board_id'])->get();

        $product=Product::get(['id','item_code']);

        return view('admin.pages.inventory.put',compact('data','product'),$params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
         $request->validate([
         
            
            'stock' => 'required|numeric|min:0'
        ]);        

        
        $inventory['stock'] = $request->stock;
        // if ($request->stock == 0) {
        //     $store_id = StoresProduct::whereId($id)->value('user_id');
        //     $product_id = StoresProduct::whereId($id)->value('product_id');
        //     if (FavoriteProduct::whereProductId($product_id)->count() > 1) {
        //         FavoriteProduct::whereProductId($product_id)->delete();
        //     } else
        //     {
        //         FavoriteProduct::whereProductId($product_id)->delete();                    
        //         FavoriteStore::whereStoreId($store_id)->delete();
        //     }

        //     // StoresProduct::whereProductId($product_id)->delete();
        //     $cart_id = CartProducts::whereProductId($product_id)->value('cart_id');
        //     if (CartProducts::whereProductId($product_id)->count() > 1) {
        //         CartProducts::whereProductId($product_id)->delete();
        //     } else
        //     {
        //         CartProducts::whereProductId($product_id)->delete();
        //         Cart::whereId($cart_id)->delete();
        //     }

        //     // Product::whereId($product_id)->delete();
        //     FavoriteProduct::whereProductId($product_id)->delete();
        //     // StoresProduct::whereProductId($product_id)->delete();
        //     $cart_id = CartProducts::whereProductId($product_id)->value('cart_id');
        //     if (CartProducts::whereProductId($product_id)->count() > 1) {
        //         CartProducts::whereProductId($product_id)->delete();
        //     } else
        //     {
        //         CartProducts::whereProductId($product_id)->delete();
        //         Cart::whereId($cart_id)->delete();
        //     }
        // }

        StoresProduct::whereId($id)->update($inventory);
    
        return redirect()->route('admin.inventory.index')
                        ->with('success','Store updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $store_product = StoresProduct::find($id);
        $store_id = $store_product->store_id;
        $product_id = $store_product->product_id;

        FavoriteProduct::whereProductId($product_id)->whereStoreId($store_id)->delete();
        $cart = Cart::where('store_id', $store_id)->whereOrderId(0)->get();
        // dd($cart);
        // if(isset($cart) && !empty($cart)){
        //     $cart_ids = $cart->pluck('id');
        //     $cart_products = CartProducts::whereIn('cart_id', $cart_ids)->get();
        //     if(isset($cart_products) && !empty($cart_products)){
        //         $cart_ids = $cart_products->pluck('cart_id');
        //         $unique_carts = array_unique($cart_ids);
        //         $unique_carts = array_values($unique_carts);
        //         Cart::whereIn('id', $unique_carts)->delete();
        //         CartProducts::where('store_id', $store_id)->where('product_id', $product_id)->delete();
        //     }
        // }
        
        StoresProduct::whereId($id)->delete();
        return redirect()->route('admin.inventory.index')
                        ->with('success','Inventory deleted successfully');
    }

    public function get_store_data(Request $request)
    {
       
        $store = User::where('parent_id',$request->parent_id)->get()->toArray();
        return json_encode($store);
    }
}
