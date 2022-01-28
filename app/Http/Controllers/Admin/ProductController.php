<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Measurement;
use App\Models\Product;
use App\Models\StoresProduct;
use App\Models\Family;
use App\Models\Cart;
use App\Models\CartProducts;
use App\Models\FavoriteProduct;
use DataTables;
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
       if ($request->ajax())
       {
            $data = Product::select('*')->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('status', function ($row)
            {
                if($row['status'] == 0){
                    return '<button onclick="active_deactive(this);" data-id="' . $row['id'] . '" data-token="' . csrf_token() . '"  class="btn btn-danger btn-xs waves-effect waves-light" data-table="products" data-status="' . $row['status']. '">Inactive</button>';
                }else{
                    return '<button onclick="active_deactive(this);" data-id="' . $row['id'] . '" data-token="' . csrf_token() . '"   class="btn btn-success btn-xs waves-effect waves-light" data-table="products" data-status="' . $row['status']. '">Active</button>';
                }
            })
            ->editColumn('action', function ($row){
                $btn = '<a href="'.route('admin.product.edit',$row['id']).'" class="mr-2"><i class="fa fa-edit"></i></a>';
                $btn .= '<a href="'.route('admin.product.show',$row['id']).'" class="mr-2"><i class="fa fa-eye"></i></a>';
                $btn .= '<a href="'.route('admin.product.destroy', $row['id']).'" data-url="product" data-id="'.$row["id"].'" data-popup="tooltip" onclick="delete_notiflix(this);return false;" data-token="'.csrf_token().'" ><i class="fa fa-trash"></i></a>';
                return $btn;
            })
            ->rawColumns(['image','canteen_name','action','category_name','status'])
            ->make(true);
       }
       else
       {
           $columns = [
               ['data' => 'id','name' => 'id','title' => "Id"],
               ['data' => 'category_name', 'name' => 'category_name','title' => __("Category Name"),'searchable'=>true ],
               ['data' => 'name', 'name' => 'name','title' => __("Product Name"),'searchable'=>true ],
               ['data' => 'item_code', 'name' => 'item_code','title' => __("Item Code"),'searchable'=>true ], 
               ['data' => 'status','title' => __("Status"),'searchable'=>false],
               ['data' => 'action','name' => 'action', 'title' => "Action",'searchable'=>false,'orderable'=>false]];
           $params['dateTableFields'] = $columns;
           $params['dateTableUrl'] = route('admin.product.index');
           $params['dateTableTitle'] = "Product Management";
           $params['dataTableId'] = time();
           $params['addUrl'] = route('admin.product.create');
           return view('admin.pages.product.index',$params);
       }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $params['pageTittle'] = "Add Product" ;
        $category = Category::all();
        $measurement = Measurement::all();
        $family = Family::all();
        if(isset($category)){
            $category = $category->toArray();
            $params['category'] = $category ;
        }
        if(isset($measurement)){
            $measurement = $measurement->toArray();
            $params['measurement'] = $measurement ;
        }
        if(isset($family)){
            $family = $family->toArray();
            $params['family'] = $family ;
        }


        $params['backUrl'] = route('admin.product.index');
        return view('admin.pages.product.post',$params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'item_code' => 'required|unique:products,item_code',
            'name' => 'required',
            'quantity' => 'required|numeric',
            'measurement_id' => 'required|numeric',
            'category_id' => 'required|numeric',
            'family_id' => '',
            'age' => '',
            'proof' => 'between:0,99.99',
            'previous_price_retail' => 'required',
            'current_price_retail' => 'required',
            'previous_price_business' => 'required',
            'current_price_business' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        //dd($request->all());


        $discount = ($request->current_price_retail / $request->previous_price_retail) * 100;
        $retail_discount = 100 - $discount;
        //$retail_discount = number_format((float)$retail_discount, 2, '.', '');
        $retail_discount = round($retail_discount);

        $discount = ($request->current_price_business / $request->previous_price_business) * 100;
        $business_discount = 100 - $discount;
        $business_discount = round($business_discount);


        $data = array(
            'item_code' => $request->item_code,
            'name' => $request->name,
            'quantity' => $request->quantity,
            'measurement_id' => $request->measurement_id,
            'family_id' => $request->family_id == '0' ? NULL : $request->family_id,
            'category_id' => $request->category_id,
            'previous_price_retail' => $request->previous_price_retail,
            'current_price_retail' => $request->current_price_retail,
            'previous_price_business' => $request->previous_price_business,
            'current_price_business' => $request->current_price_business,
            'retail_discount' => $retail_discount,
            'business_discount' => $business_discount
        );

        if(isset($request->image)){
            $dir = "images/product";
            $image = CommonHelper::imageUpload($request->image,$dir);
            $data['image'] = $image;
        }

        //dd($data);

        if($request->age != '' && $request->age >= 0){
            $data['age'] = $request->age;
        }else{
            $data['age'] = 0;
        }

        if($request->proof != '' && $request->proof >= 0){
            $data['proof'] = $request->proof;
        }else{
            $data['proof'] = 0;
        }

        

        $product = Product::create($data);

        //$product_id = $product->id;


        // redirect
        return redirect()->route('admin.product.index')->with('success','Product created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $params['pageTittle'] = "View Product";
        $params['product'] = Product::find($id);
        // dd($params['product']);
        $params['backUrl'] = route('admin.product.index');
        return view('admin.pages.product.view',$params);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $params['pageTittle'] = "Edit Product";
        $params['product'] = Product::find($id);

        if($params['product']){
            // dd($params['product']);
            $category = Category::all();
            $measurement = Measurement::all();
            $family = Family::all();
            if(isset($category)){
                $category = $category->toArray();
                $params['category'] = $category ;
            }
            if(isset($measurement)){
                $measurement = $measurement->toArray();
                $params['measurement'] = $measurement ;
            }
            if(isset($family)){
                $family = $family->toArray();
                $params['family'] = $family ;
            }
            $params['backUrl'] = route('admin.product.index');
            return view('admin.pages.product.put',$params);
        }else{
            return redirect()->back()->with('error','Product deleted.');
        }
        
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
        //dd($request->all());
        $request->validate([
            'item_code' => 'required|unique:products,item_code,'.$id,
            'name' => 'required',
            'quantity' => 'required|numeric',
            'measurement_id' => 'required|numeric',
            'category_id' => 'required|numeric',
            'family_id' => '',
            'age' => '',
            'proof' => 'between:0,99.99',
            'previous_price_retail' => 'required',
            'current_price_retail' => 'required',
            'previous_price_business' => 'required',
            'current_price_business' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $discount = ($request->current_price_retail / $request->previous_price_retail) * 100;
        $retail_discount = 100 - $discount;
        $retail_discount = round($retail_discount);
        $product['retail_discount'] = $retail_discount;

        

        $product['item_code'] = $request->item_code;
        $product['name'] = $request->name;
        $product['quantity'] = $request->quantity;
        $product['measurement_id'] = $request->measurement_id;
        $product['category_id'] = $request->category_id;
        $product['family_id'] = ($request->family_id == '0')?NULL:$request->family_id;
        $product['age'] = $request->age;
        $product['proof'] = $request->proof;
        $product['previous_price_retail'] = $request->previous_price_retail;
        $product['current_price_retail'] = $request->current_price_retail;
        $product['previous_price_business'] = $request->previous_price_business;
        $product['current_price_business'] = $request->current_price_business;


        $discount = ($request->current_price_business / $request->previous_price_business) * 100;
        $business_discount = 100 - $discount;
        $business_discount = round($business_discount);
        $product['business_discount'] = $business_discount;
        

        if(isset($request->image)){
            $dir = "images/product";
            $image = CommonHelper::imageUpload($request->image,$dir);
            $product['image'] = $image;
        }

        //dd($data);

        if($request->age != '' && $request->age >= 0){
            $product['age'] = $request->age;
        }else{
            $product['age'] = 0;
        }

        if($request->proof != '' && $request->proof >= 0){
            $product['proof'] = $request->proof;
        }else{
            $product['proof'] = 0;
        }
    
        $product=Product::whereId($id)->update($product);
        

        return redirect()->route('admin.product.index')->with('success','Product updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::whereId($id)->delete();
        FavoriteProduct::whereProductId($id)->delete();
        StoresProduct::whereProductId($id)->delete();

        $cart_products = CartProducts::whereProductId($id)->get();
        if(isset($cart_products) && !empty($cart_products)){
            foreach ($cart_products as $key => $value) {
                $c_d = CartProducts::where('product_id', '!=',$id)->where('cart_id', $value->cart_id)->get();
                $count = $c_d->count();
                if($count == 0){
                    Cart::where('id', $value->cart_id)->delete();
                }
                CartProducts::where('id', $value->id)->delete();
            }
        }


        // $cart_id = CartProducts::whereProductId($id)->value('cart_id');
        // if (CartProducts::whereProductId($id)->count() > 1) {
        //     CartProducts::whereProductId($id)->delete();
        // } else
        // {
        //     CartProducts::whereProductId($id)->delete();
        //     Cart::whereId($cart_id)->delete();
        // }
        return redirect()->route('admin.product.index')
                        ->with('success','Product deleted successfully');
    }

    public function remove_product_store($id)
    {
        StoresProduct::where('id', $id)->delete();
        echo 1;
    }

    public function get_image()
    {
        $request = $_POST;

        $extension = pathinfo($request['image_link'],PATHINFO_EXTENSION);
        $exte = array('jpeg','png','jpg','gif','svg');
        if(in_array($extension,$exte))
        {
            $filename = time().'.'.$extension;
            $filepath = 'images/product/';
            $full_path = $filepath.$filename;
           // $filename = 'images/product/TEST44.'.$extension;
            //public_path($dir)
            $file = file_get_contents($request['image_link']);

            // $dir = "images/product";
            // $image = CommonHelper::imageUpload($file, $dir);

            $image=file_put_contents($full_path, $file);


            $data['image_full_link'] = url($full_path);
            $data['image_name'] = $filename;
            $data['status'] = TRUE;
            $data['message'] = 'Image fetched.';
        }else{
            $data['status'] = FALSE;
            $data['message'] = 'Only image link allowed.';
        }

        
        echo json_encode($data);
    }

    public function save_product_store()
    {
        $request = $_POST;
        $store_product = StoresProduct::where('id', $request['id'])->first();
        if($store_product){
            $update_data['stock'] = $request['stock'];
            $store_product = StoresProduct::where('id', $request['id'])->update($update_data);
            $data['status'] = TRUE;
            $data['message'] = 'Stock updated';
        }else{
            $data['status'] = FALSE;
            $data['message'] = 'Stock data not found';
        }
        echo json_encode($data);
    }
}
