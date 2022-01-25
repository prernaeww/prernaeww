<?php

namespace App\Http\Controllers\board;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UsersGroup;
use DataTables;
use CommonHelper;

class StoreController extends Controller
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
            $data = User::select('users.*')->join('users_group', 'users.id', '=', 'users_group.user_id')->where('users_group.group_id',3);
            return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('profile_picture', function ($row) {
                if($row['profile_picture'] == ""){
                    $profile_picture = "/images/default.png";
                }else{
                    $profile_picture =$row['profile_picture'];
                }
                return '<img class="border rounded p-0"  src="'.$profile_picture.'" onerror="this.src=/images/default.png" alt="your image" style="height: 70px;width: 70px; object-fit: cover;" id="blah1"/>';
            })
            ->editColumn('action', function ($row)
            {               
               $btn = '<a href="'.route('board.store.show',$row['id']).'" class="mr-2"><i class="fa fa-eye"></i></a>';
             
               return $btn;
            })
            ->rawColumns(['profile_picture','action','status'])
            ->make(true);
       }
       else
       {
           $columns = [
               ['data' => 'id','name' => 'users.id','title' => "Id"], 
               ['data' => 'profile_picture','name' => 'users.profile_picture', 'title' => __("Profile Picture"),'searchable'=>false],
               ['data' => 'first_name', 'name' => 'users.first_name','title' => __("First Name")],
               ['data' => 'last_name', 'name' => 'users.last_name','title' => __("Last Name")],
               ['data' => 'email', 'name' => 'users.email','title' => __("Email")],
               ['data' => 'phone_formatted', 'name' => 'users.phone','title' => __("Phone"),'searchable'=>false],
               ['data' => 'action', 'name' => 'action','title' => "Action",'searchable'=>false,'orderable'=>false]];
           $params['dateTableFields'] = $columns;
           $params['dateTableUrl'] = route('board.store.index');
           $params['dateTableTitle'] = "Store Management";
           $params['dataTableId'] = time();
           $params['addUrl'] = route('board.store.create');
           return view('board.pages.store.index',$params);
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
        $params['pageTittle'] = "View Store";
        $user = User::find($id);
        $params['user'] = $user->toArray();
        $params['backUrl'] = route('board.store.index');
        return view('board.pages.store.view',$params);
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
