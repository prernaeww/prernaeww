<?php

namespace App\Http\Controllers\board;

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
    if ($request->ajax())
       {
            $login_id =  Auth::user()->id;
            $store = User::select('id')->where('parent_id',$login_id)->get()->pluck('id')->toArray();
            $storeproducts=StoresProduct::select('product_id')->whereIn('user_id',$store)->get()->pluck('product_id')->toArray();
            $data = Product::whereIn('id',$storeproducts)->get();
            return Datatables::of($data)
            ->addIndexColumn()
            
            ->editColumn('status', function ($row)
            {
                if($row['status'] == 0){
                    return '<span class="badge badge-warning p-1">Pending</span>';
                }elseif($row['status'] == 1){
                    return '<p  data-id="' . $row['id'] . '" data-token="' . csrf_token() . '"   class="badge badge-success p-1" data-table="users" data-status="' . $row['status']. '">Active</p>';
                }else{
                    return '<p data-id="' . $row['id'] . '" data-token="' . csrf_token() . '"  class="badge badge-danger p-1" data-table="users" data-status="' . $row['status']. '">Inactive</p>';
                }
            })
            ->editColumn('action', function ($row){
                $btn = '<a href="'.route('board.product.show',$row['id']).'" class="mr-2"><i class="fa fa-eye"></i></a>';
                // $btn .= '<a href="'.route('admin.product.show',$row['id']).'" class="mr-2"><i class="fa fa-eye"></i></a>';
              
                return $btn;
            })
            ->rawColumns(['image','category_name','status','action'])
            ->make(true);
       }
       else
       {
           $columns = [
               ['data' => 'id','name' => 'id','title' => "Id"], 
               ['data' => 'category_name','name' => 'category_name', 'title' => __("Category Name"),'searchable'=>true],
               ['data' => 'name','name' => 'name', 'title' => __("Product Name"),'searchable'=>true],
               ['data' => 'item_code','name' => 'item_code', 'title' => __("Item Code"),'searchable'=>true],  
               ['data' => 'status','name' => 'status', 'title' => __("Status"),'searchable'=>true], 
               ['data' => 'action','name' => 'action', 'title' => "Action",'searchable'=>false]
           ];
           $params['dateTableFields'] = $columns;
           $params['dateTableUrl'] = route('board.product.index');
           $params['dateTableTitle'] = "Product Management";
           $params['dataTableId'] = time();
           $params['addUrl'] = route('board.product.create');
           return view('board.pages.product.index',$params);
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
        $board_id = Auth::user()->id;
        $params['pageTittle'] = "View Product with Stores";
        $stores = User::select('id')->where('parent_id',$board_id)->get()->pluck('id')->toArray();
        $params['storeproducts']=StoresProduct::where('product_id',$id)->whereIn('user_id', $stores)->with(['product', 'store'])->get();
        $params['backUrl'] = route('store.product.index');
        return view('board.pages.product.view',$params);
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
