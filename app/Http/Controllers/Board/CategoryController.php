<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use App\Helpers\CommonHelper;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
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
            
            $data = Category::select('*');
            return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('image', function ($row) {
                return '<img class="border rounded p-0" src="'.$row['image'].'" onerror="this.src=/images/default.png" alt="your image" style="height: 70px;width: 70px; object-fit: contain;" id="blah1"/>';
            })
            ->editColumn('action', function ($row){
                $btn = '<a href="'.route('board.category.show',$row['id']).'" class="mr-2"><i class="fa fa-eye"></i></a>';
                // $btn .= '<a href="'.route('canteen.category.show',$row['id']).'" class="mr-2"><i class="fa fa-eye"></i></a>';
                // $btn .= '<a href="'.route('board.category.destroy', $row['id']).'" data-url="category" data-id="'.$row["id"].'" data-popup="tooltip" onclick="delete_notiflix(this);return false;" data-token="'.csrf_token().'" ><i class="fa fa-trash"></i></a>';
                return $btn;
            })
            
            ->rawColumns(['image', 'action','status'])
            ->make(true);
       }
       else
       {
           $columns = [
               ['data' => 'id','name' => 'id','title' => "Id"], 
               ['data' => 'image','name' => 'image', 'title' => __("Image"),'searchable'=>false],
               ['data' => 'name','name' => 'name', 'title' => __("Name"),'searchable'=>true],
               ['data' => 'action','name' => 'action', 'title' => "Action",'searchable'=>false,'orderable'=>false]];
           $params['dateTableFields'] = $columns;
           $params['dateTableUrl'] = route('board.category.index');
           $params['dateTableTitle'] = "Category Management";
           $params['dataTableId'] = time();
           $params['addUrl'] = route('board.category.create');
           return view('board.pages.category.index',$params);
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
        $params['backUrl'] = route('canteen.category.index');
        return view('board.pages.category.post',$params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $login_id =  Auth::user()->id;
        
        $request->validate([
            'name' => 'required',
            'kitchen_id' => 'required',
        ]);
        $dir = env('AWS_S3_MODE')."/category";
        $image = CommonHelper::s3Upload($request->image,$dir);

        $category = Category::create([
            'name' => $request->name,
            'kitchen_id' => $request->kitchen_id,
            'canteen_id' => $login_id,
            'image' => $image
        ]);

        // redirect
        return redirect()->route('board.category.index')->with('success','Category created successfully.');
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
        // dd($params['category']);

        $params['backUrl'] = route('board.category.index');
        return view('board.pages.category.view',$params);
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
        $params['backUrl'] = route('canteen.category.index');
        return view('board.pages.category.put',$params);
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
        $login_id =  Auth::user()->id;
        $request->validate([
            'name' => 'required',
            'kitchen_id' => 'required',
        ]);
        if(isset($request->image) && $request->image != ''){
            $dir = env('AWS_S3_MODE')."/category";
            $image = CommonHelper::s3Upload($request->image,$dir);
            $category['image'] = $image;
        }

        $category['name'] = $request->name;
        $category['kitchen_id'] = $request->kitchen_id;
        $category['canteen_id'] = $login_id;
    
        Category::whereId($id)->update($category);
    
        return redirect()->route('canteen.category.index')
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
        Category::whereId($id)->delete();
        return redirect()->route('canteen.category.index')
                        ->with('success','Category deleted successfully');

    }

    public function active_deactive_category()
    {
        if($_POST['table'] == 'category'){
			if($_POST['status'] == 0){
				$status = 1;
			}else{
				$status = 0;
			}
            Category::where('id', $_POST['id'])->update(['status' => $status]);
		}
		echo $status;
    }
}
