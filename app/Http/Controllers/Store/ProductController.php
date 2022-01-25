<?php

namespace App\Http\Controllers\store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\StoresProduct;
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
        //

       if ($request->ajax())
       {
            // $data = Product::select('*')->orderBy('id','asc')->with('category','variant')->get();
            $login_id =  Auth::user()->id;
       
            $storeProduct = StoresProduct::select('*')->where('user_id',$login_id)->get();
            $productId=$storeProduct->pluck('product_id');
            $data = Product::select('*')->orderBy('id','asc')->whereIn('id',$productId)->get();

            return Datatables::of($data)
            ->editColumn('status', function ($row)
            {
                if($row['status'] == 0){
                    return '<button onclick="active_deactive_product(this);" data-id="' . $row['id'] . '" data-token="' . csrf_token() . '"  class="btn btn-danger btn-xs waves-effect waves-light" data-table="products" data-status="' . $row['status']. '">In Active</button>';
                }else{
                    return '<button onclick="active_deactive_product(this);" data-id="' . $row['id'] . '" data-token="' . csrf_token() . '"  class="btn btn-success btn-xs waves-effect waves-light" data-table="products" data-status="' . $row['status']. '">Active</button>';
                }
            })
            ->editColumn('action', function ($row){
                $btn = '<a href="'.route('store.product.show',$row['id']).'" class="mr-2"><i class="fa fa-eye"></i></a>';
                // $btn .= '<a href="'.route('admin.product.show',$row['id']).'" class="mr-2"><i class="fa fa-eye"></i></a>';
              
                return $btn;
            })
            ->rawColumns(['image','category_name','varient_data','status','action'])
            ->make(true);
       }
       else
       {
           $columns = [
               ['data' => 'id','name' => 'id','title' => "Id"], 
               ['data' => 'category_name','name' => 'category_name', 'title' => __("Category Name"),'searchable'=>true],
               ['data' => 'name','name' => 'name', 'title' => __("Product Name"),'searchable'=>true],
               ['data' => 'item_code','name' => 'item_code', 'title' => __("Item Code"),'searchable'=>true],
               // ['data' => 'stock','name' => 'stock', 'title' => __("Stock")],
               // ['data' => 'image','name' => 'image', 'title' => __("Image"),'searchable'=>false],
             // ['data' => 'status','title' => __("Status"),'searchable'=>false],
               ['data' => 'action','name' => 'action', 'title' => "Action",'searchable'=>false,'orderable'=>false]
           ];
           $params['dateTableFields'] = $columns;
           $params['dateTableUrl'] = route('store.product.index');
           $params['dateTableTitle'] = "Product Management";
           $params['dataTableId'] = time();
           $params['addUrl'] = route('store.product.create');
           return view('store.pages.product.index',$params);
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         // $login_id =  Auth::user()->id;
        
        $params['pageTittle'] = "View Product";
        $login_id =  Auth::user()->id;
        $params['product']=StoresProduct::where('product_id',$id)->where('user_id',$login_id)->with('product')->first();
        //dd($params['variant']);
        $params['backUrl'] = route('store.product.index');
        return view('store.pages.product.view',$params);
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
    }
}
