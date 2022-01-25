<?php

namespace App\Http\Controllers\Canteen;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Meal;
use App\Models\MealCategory;
use App\Models\Category;
use DataTables;
use Illuminate\Support\Facades\Auth;
use CommonHelper;

class MealController extends Controller
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
            $data = Meal::select('*')->where('canteen_id',$login_id);
            return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('action', function ($row){
                $btn = '<a href="'.route('canteen.meal.edit',$row['id']).'" class="mr-2"><i class="fa fa-edit"></i></a>';
                $btn .= '<a href="'.route('canteen.meal.show',$row['id']).'" class="mr-2"><i class="fa fa-eye"></i></a>';
                $btn .= '<a href="'.route('canteen.meal.destroy', $row['id']).'" data-url="meal" data-id="'.$row["id"].'" data-popup="tooltip" onclick="delete_notiflix(this);return false;" data-token="'.csrf_token().'" ><i class="fa fa-trash"></i></a>';
                return $btn;
            })
             ->editColumn('status', function ($row)
            {
                if($row['status'] == 0){
                    return '<button onclick="active_deactive_meal(this);" data-id="' . $row['id'] . '" data-token="' . csrf_token() . '"  class="btn btn-danger btn-xs waves-effect waves-light" data-table="meals" data-status="' . $row['status']. '">In Active</button>';
                }else{
                    return '<button onclick="active_deactive_meal(this);" data-id="' . $row['id'] . '" data-token="' . csrf_token() . '"   class="btn btn-success btn-xs waves-effect waves-light" data-table="meals" data-status="' . $row['status']. '">Active</button>';
                }
            })
             ->rawColumns(['action','status'])
            ->make(true);
       }
       else
       {
           $columns = [
               ['data' => 'id','name' => 'id','title' => "Id"], 
               ['data' => 'name','name' => 'name', 'title' => __("Name")],
                ['data' => 'status','title' => __("Status"),'searchable'=>false],
               ['data' => 'action','name' => 'action', 'title' => "Action",'searchable'=>false,'orderable'=>false]];
           $params['dateTableFields'] = $columns;
           $params['dateTableUrl'] = route('canteen.meal.index');
           $params['dateTableTitle'] = "Meal Management";
           $params['dataTableId'] = time();
           $params['addUrl'] = route('canteen.meal.create');
           return view('canteen.pages.meal.index',$params);
       }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $login_id =  Auth::user()->id;
        $params['pageTittle'] = "Add Meal" ;
        $params['category'] = Category::whereHas('products')->where('canteen_id',$login_id)->get();
        $params['backUrl'] = route('canteen.meal.index');
        return view('canteen.pages.meal.post',$params);
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
            'price' => 'required',
            'category' => 'required',
            'description' => 'required',
            'items_number' => 'required',
            'kitchen_id' => 'required'
        ]);

        $dir = "images/meal";
        
        $image = CommonHelper::imageUpload($request->image,$dir);

        $meal = Meal::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'kitchen_id' => $request->kitchen_id,
            'canteen_id' => $login_id,
            'image' => $image
        ]);

        $insert_data = [];

        foreach ($request->category as $key => $value) {
            $insert = array(
                'meal_id' => $meal->id,
                'category_id' => $value,
                'items_number' => $request->items_number[$key]
            );
            array_push($insert_data, $insert);
        }

        $meal_category = MealCategory::insert($insert_data);

        // redirect
        return redirect()->route('canteen.meal.index')->with('success','Meal created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $params['pageTittle'] = "View Meal" ;
        $meal = Meal::with(['category'])->find($id);
        if (isset($meal)) {
            $meal = $meal->toArray();
            $params['meal'] = $meal;
        }
        $params['backUrl'] = route('canteen.meal.index');
        return view('canteen.pages.meal.view',$params);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $params['pageTittle'] = "Edit Meal";
        $meal = Meal::with(['category'])->find($id);
        if (isset($meal)) {
            $meal = $meal->toArray();
            $params['meal'] = $meal;
        }
        $login_id =  Auth::user()->id;
        $params['category'] = Category::whereHas('products')->where('canteen_id',$login_id)->get();
        $params['backUrl'] = route('canteen.meal.index');
        return view('canteen.pages.meal.put',$params);
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
            'price' => 'required',
            'category' => 'required',
            'description' => 'required',
            'items_number' => 'required',
            'kitchen_id' => 'required'
        ]);

        if(isset($request->image) && $request->image != ''){
            $dir = "images/meal";
            $image = CommonHelper::imageUpload($request->image,$dir);
            $meal_update['image'] = $image;
        }

        $meal_update['name'] = $request->name;
        $meal_update['price'] = $request->price;
        $meal_update['description'] = $request->description;
        $meal_update['kitchen_id'] = $request->kitchen_id;
        $meal_update['canteen_id'] = $login_id;

        Meal::whereId($id)->update($meal_update);
        MealCategory::where('meal_id',$id)->delete();
        $insert_data = [];

        foreach ($request->category as $key => $value) {
            $insert = array(
                'meal_id' => $id,
                'category_id' => $value,
                'items_number' => $request->items_number[$key]
            );
            array_push($insert_data, $insert);
        }

        $meal_category = MealCategory::insert($insert_data);
    
        return redirect()->route('canteen.meal.index')
                        ->with('success','Meal updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Meal::whereId($id)->delete();
        return redirect()->route('canteen.meal.index')
                        ->with('success','Meal deleted successfully');

    }

     public function active_deactive_meal()
    {
        if($_POST['table'] == 'meals'){
            if($_POST['status'] == 0){
                $status = 1;
            }else{
                $status = 0;
            }
            Meal::where('id', $_POST['id'])->update(['status' => $status]);
        }
        echo $status;
    }
}
