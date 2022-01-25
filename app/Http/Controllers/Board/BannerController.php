<?php

namespace App\Http\Controllers\board;

use App\Http\Controllers\Controller;
use App\Helpers\CommonHelper;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\User;
use DataTables;
use Illuminate\Support\Facades\Auth;


class BannerController extends Controller
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
            
             $login_id =  Auth::user()->id;
            $data = Banner::select('*')->where('user_id',$login_id)->orderBy('id','desc')->with('user_name')->get();
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
                return '<img class="border rounded p-0" src="'.$row['image'].'" onerror="this.src=/images/default.png" alt="your image" style="height: 70px;width: 100px; object-fit: cover;" id="blah1"/>';
            })
           
            ->editColumn('action', function ($row)
            {
               $btn = '<a href="'.route('board.banner.show',$row['id']).'" class="mr-2"><i class="fa fa-eye"></i></a>';
               // $btn .= '<a href="'.route('board.banner.destroy', $row['id']).'" data-url="banner" data-id="'.$row["id"].'" data-popup="tooltip" onclick="delete_notiflix(this);return false;" data-token="'.csrf_token().'" ><i class="fa fa-trash"></i></a>';
               return $btn;
            })
            ->rawColumns(['image','user_data', 'action'])
            ->make(true);
       }
       else
       {
           $columns = [
            //    ['data' => 'sequence', 'name' => 'sequence','title' => __("Sequence")],
               ['data' => 'id','name' => 'id','title' => "Id"], 
               ['data' => 'image','name' => 'image', 'title' => __("Image"),'searchable'=>false],
               ['data' => 'start_date','name' => 'start_date', 'title' => __("Start Date"),'searchable'=>true],
               ['data' => 'end_date','name' => 'end_date', 'title' => __("End Date"),'searchable'=>true],
               ['data' => 'action', 'name' => 'action','title' => "Action",'searchable'=>false,'orderable'=>false]];
           $params['dateTableFields'] = $columns;
           $params['dateTableUrl'] = route('board.banner.index');
           $params['dateTableTitle'] = "Banner";
           $params['dataTableId'] = time();
           $params['addUrl'] = route('board.banner.create');
           return view('board.pages.banner.index',$params);
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
        //
        $params['pageTittle'] = "View Banner";
        $banner =Banner::find($id);
        // dd($banner);
        $params['banner'] = $banner->toArray();
        $params['backUrl'] = route('board.banner.index');
        return view('board.pages.banner.view',compact('banner'),$params);
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
