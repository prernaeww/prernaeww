<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\CommonHelper;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\User;
use Carbon\Carbon;
use DataTables;

class BannerController extends Controller
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
            $data = Banner::select('*')->orderBy('id','desc')->with('user_name')->get();
            return Datatables::of($data)
            ->addIndexColumn()

            ->addColumn('user_data', function($row) {
                    
                    $user_data = $row['user_id'];
                    return  $user_data;
                })

             ->addColumn('start_date', function($row) {
                
                $start_date= date('m-d-Y', strtotime($row['start_date']));
                return $start_date;
            })

             ->addColumn('end_date', function($row) {
                
                $end_date= date('m-d-Y', strtotime($row['end_date']));
                return $end_date;
            })
            
            ->editColumn('image', function ($row) {
                return '<img class="border rounded p-0" src="'.$row['image'].'" onerror="this.src=/images/default.png" alt="your image" style="height: 70px;width: 100px; object-fit: contain;" id="blah1"/>';
            })
            ->editColumn('action', function ($row)
            {
               $btn = '<a href="'.route('admin.banner.edit',$row['id']).'" class="mr-2"><i class="fa fa-edit"></i></a>';
               $btn .= '<a href="'.route('admin.banner.destroy', $row['id']).'" data-url="banner" data-id="'.$row["id"].'" data-popup="tooltip" onclick="delete_notiflix(this);return false;" data-token="'.csrf_token().'" ><i class="fa fa-trash"></i></a>';
               return $btn;
            })
            ->rawColumns(['image','user_data','date_diff' ,'action'])
            ->make(true);
       }
       else
       {
           $columns = [
            //    ['data' => 'sequence', 'name' => 'sequence','title' => __("Sequence")],
               ['data' => 'id','name' => 'id','title' => "Id"], 
               ['data' => 'image','name' => 'image', 'title' => __("Image"),'searchable'=>false],
               ['data' => 'user_name.first_name','name' => 'user_data.full_name', 'title' => __("Board Name"),'searchable'=>true],
               ['data' => 'start_date','name' => 'start_date', 'title' => __("Start Date"),'searchable'=>true],
               ['data' => 'end_date','name' => 'end_date', 'title' => __("End Date"),'searchable'=>true],
               ['data' => 'date_diff','name' => 'date_diff', 'title' => __("Status"),'searchable'=>true],
               ['data' => 'action', 'name' => 'action','title' => "Action",'searchable'=>false,'orderable'=>false]];
           $params['dateTableFields'] = $columns;
           $params['dateTableUrl'] = route('admin.banner.index');
           $params['dateTableTitle'] = "Banner Management";
           $params['dataTableId'] = time();
           $params['addUrl'] = route('admin.banner.create');
           return view('admin.pages.banner.index',$params);
       }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $params['pageTittle'] = "Add Banner";
        $params['backUrl'] = route('admin.banner.index');
        $data = User::select('users.*')->join('users_group', 'users.id', '=', 'users_group.user_id')->where('users_group.group_id',2)->get();
        return view('admin.pages.banner.post',compact('data'),$params);
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
            'start_date' => 'required',
            'end_date' => 'date_format:m-d-Y|after_or_equal:start_date',
            'image'=>'required|image|mimes:jpeg,png,jpg,gif,svg'
            
        ]);

        $dir = "images/banner";
        $image = CommonHelper::imageUpload($request->image,$dir);
        $banner = Banner::create([
            'image' => $image,
            'start_date' => Carbon::createFromFormat('m-d-Y', $request->start_date)->format('Y-m-d'),
            'end_date' => Carbon::createFromFormat('m-d-Y', $request->end_date)->format('Y-m-d'),
            'user_id' => $request->user_id,

        ]);
        // dd($banner);
        // redirect
        return redirect()->route('admin.banner.index')->with('success','Banner created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $params['pageTittle'] = "View Banner";
        $banner = Banner::all();
        $params['banner'] = $banner->toArray();
        $params['backUrl'] = route('admin.banner.index');
        return view('admin.pages.banner.view',$params);
    }
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $params['pageTittle'] = "Edit Banner";
        $params['banner'] = Banner::find($id);
        $params['backUrl'] = route('admin.banner.index');
        $data = User::select('users.*')->join('users_group', 'users.id', '=', 'users_group.user_id')->where('users_group.group_id',2)->get();

        return view('admin.pages.banner.put',compact('data'),$params);
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
            'start_date' => 'required',
            'end_date' => 'date_format:m-d-Y|after_or_equal:start_date',
            
        ]);

        if(isset($request->image) && $request->image != ''){
            $dir = "images/banner";
            $image = CommonHelper::imageUpload($request->image,$dir);
            $banner['image'] = $image;
        }
        $banner['start_date'] =Carbon::createFromFormat('m-d-Y', $request->start_date)->format('Y-m-d');
        $banner['end_date'] = Carbon::createFromFormat('m-d-Y', $request->end_date)->format('Y-m-d');
        $banner['user_id']=$request->user_id;
        Banner::whereId($id)->update($banner);
        return redirect()->route('admin.banner.index')
                        ->with('success','Banner updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Banner::whereId($id)->delete();
        return redirect()->route('admin.banner.index')
                        ->with('success','Banner deleted successfully');
    }

}
