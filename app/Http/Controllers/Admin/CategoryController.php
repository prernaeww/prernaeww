<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\CommonHelper;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use DataTables;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
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
			$data = Category::get();
			return Datatables::of($data)
			->addIndexColumn()
			->editColumn('image', function ($row) {
				return '<img class="border rounded p-0" src="'.$row['image'].'" onerror="this.src=/images/default.png" alt="your image" style="height: 70px;width: 70px; object-fit: contain;" id="blah1"/>';
			})
			->editColumn('action', function ($row){
				$btn = '<a href="'.route('admin.category.edit',$row['id']).'" class="mr-2"><i class="fa fa-edit"></i></a>';
				$btn .= '<a href="'.route('admin.category.show',$row['id']).'" class="mr-2"><i class="fa fa-eye"></i></a>';
				$btn .= '<a href="'.route('admin.category.destroy', $row['id']).'" data-url="category" data-id="'.$row["id"].'" data-popup="tooltip" onclick="delete_notiflix(this);return false;" data-token="'.csrf_token().'" ><i class="fa fa-trash"></i></a>';
				return $btn;
			})
			->editColumn('status', function ($row)
			{
				if($row['status'] == 0){
					return '<button onclick="active_deactive(this);" data-id="' . $row['id'] . '" data-token="' . csrf_token() . '"  class="btn btn-danger btn-xs waves-effect waves-light" data-table="category" data-status="' . $row['status']. '">Inactive</button>';
				}else{
					return '<button onclick="active_deactive(this);" data-id="' . $row['id'] . '" data-token="' . csrf_token() . '"   class="btn btn-success btn-xs waves-effect waves-light" data-table="category" data-status="' . $row['status']. '">Active</button>';
				}
			})
			->rawColumns(['image', 'action','status'])
			->make(true);
	   }
	   else
	   {
		   $columns = [
			   ['data' => 'id','name' => 'id','title' => "Id"], 
			   ['data' => 'image','name' => 'image', 'title' => __("Image"),'searchable'=>false],
			   ['data' => 'name','name' => 'name', 'title' => __("Name")],
			   ['data' => 'status','title' => __("Status"),'searchable'=>false],
			   ['data' => 'action','name' => 'action', 'title' => "Action",'searchable'=>false,'orderable'=>false]];
		   $params['dateTableFields'] = $columns;
		   $params['dateTableUrl'] = route('admin.category.index');
		   $params['dateTableTitle'] = "Category Management";
		   $params['dataTableId'] = time();
		   $params['addUrl'] = route('admin.category.create');
		   return view('admin.pages.category.index',$params);
	   }
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$params['pageTittle'] = "Add Category" ;
		$params['backUrl'] = route('admin.category.index');
		return view('admin.pages.category.post',$params);
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
			'name' => 'required',
			'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'		
		]);
		 
		$dir = "images/category";
		$image = CommonHelper::imageCatUpload($request->image,$dir);

		$category = Category::create([
			'name' => $request->name,
			'image' => $image
		]);

		// redirect
		return redirect()->route('admin.category.index')->with('success','Category created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$params['pageTittle'] = "View Category" ;
		$params['category'] = Category::find($id);
		$params['backUrl'] = route('admin.category.index');
		return view('admin.pages.category.view',$params);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$params['pageTittle'] = "Edit Category";
		$params['category'] = Category::find($id);
		$params['backUrl'] = route('admin.category.index');
		return view('admin.pages.category.put',$params);
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
	
		$request->validate([
			'name' => 'required',
			
		]);
		if(isset($request->image) && $request->image != ''){
			$dir = "images/category";
			$image = CommonHelper::imageCatUpload($request->image,$dir);
			$category['image'] = $image;
		}

		$category['name'] = $request->name;
	   
	   
	
		Category::whereId($id)->update($category);
	
		return redirect()->route('admin.category.index')
						->with('success','Category updated successfully');

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		// Category::whereId($id)->delete();
		$products = Product::whereCategoryId($id)->get();		
		if (isset($products) && !empty($products) && count($products) > 0) {
            $data['status'] = false;
			$data['message'] = 'You can not delete as Product(s) already exist for this category';        
		} else
		{
			Category::whereId($id)->delete();            
			$data['status'] = TRUE;        
			$data['message'] = 'Deleted';
		}
        echo json_encode($data);
		// return redirect()->route('admin.category.index')->with('success','Category deleted successfully');

	}

	
}
