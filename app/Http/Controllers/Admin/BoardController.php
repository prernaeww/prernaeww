<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Banner;
use App\Models\UsersGroup;
use DataTables;
use CommonHelper;

use Mail;
use App\Mail\RegistrationMail;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         // event(new UserNotify(User::find(1)));
       if ($request->ajax())
       {
            $data = User::select('users.*')->join('users_group', 'users.id', '=', 'users_group.user_id')->where('users_group.group_id',2);
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
               $btn = '<a href="'.route('admin.board.edit',$row['id']).'" class="mr-2"><i class="fa fa-edit"></i></a>';
               $btn .= '<a href="'.route('admin.board.show',$row['id']).'" class="mr-2"><i class="fa fa-eye"></i></a>';
               // $btn .= '<a href="'.route('admin.board.destroy', $row['id']).'" data-url="board" data-id="'.$row["id"].'" data-popup="tooltip" onclick="delete_notiflix(this);return false;" data-token="'.csrf_token().'" ><i class="fa fa-trash"></i></a>';
               return $btn;
            })
            ->editColumn('status', function ($row)
            {
                if($row['status'] == 0){
                    return '<span class="badge bg-soft-warning text-warning">Pending</span>';
                }elseif($row['status'] == 1){
                    return '<button onclick="active_deactive_store_board(this);" data-id="' . $row['id'] . '" data-token="' . csrf_token() . '"   class="btn btn-success btn-xs waves-effect waves-light" data-table="users" data-table_user="board" data-status="' . $row['status']. '">Active</button>';
                }else{
                    return '<button onclick="active_deactive_store_board(this);" data-id="' . $row['id'] . '" data-token="' . csrf_token() . '"  class="btn btn-danger btn-xs waves-effect waves-light" data-table="users" data-table_user="board" data-status="' . $row['status']. '">Inactive</button>';
                }
            })
            ->rawColumns(['profile_picture', 'action','status'])
            ->make(true);
       }
       else
       {
           $columns = [
               ['data' => 'id','name' => 'users.id','title' => "Id"], 
               ['data' => 'profile_picture','name' => 'users.profile_picture', 'title' => __("Profile Picture"),'searchable'=>false],
               ['data' => 'first_name', 'name' => 'first_name','title' => __("Board Name")],
               ['data' => 'email', 'name' => 'users.email','title' => __("Email")],
               ['data' => 'phone_formatted', 'name' => 'users.phone','title' => __("Phone"),'searchable'=>true],
               ['data' => 'status','title' => __("Status"),'searchable'=>false],
               ['data' => 'action', 'name' => 'action','title' => "Action",'searchable'=>false,'orderable'=>false]];
           $params['dateTableFields'] = $columns;
           $params['dateTableUrl'] = route('admin.board.index');
           $params['dateTableTitle'] = "Board Management";
           $params['dataTableId'] = time();
           $params['addUrl'] = route('admin.board.create');
           return view('admin.pages.board.index',$params);
       }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $params['pageTittle'] = "Add Board";
        $params['backUrl'] = route('admin.board.index');
        return view('admin.pages.board.post',$params);
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
            'first_name' => 'required',
            'email' => 'email|required|unique:users,email',
            'phone' => 'required|unique:users,phone|min:14|max:14',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $dir = "images/users";
        $image = CommonHelper::imageUpload($request->image,$dir);
        
        // $phone = CommonHelper::RemovePhoneFormat($request->phone);
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = substr(str_shuffle(str_repeat($pool, 5)), 0, 64);
        $remember_token = config('app.url').'auth-account-activation/'.$randomString;


        $m_number = str_replace("(","",$request->phone);
        $m_number = str_replace(")","",$m_number);
        $m_number = str_replace("-","",$m_number);
        $m_number = ltrim($m_number , '1');

        $user = User::create([
            'first_name' => $request->first_name,
            'email' => strtolower($request->email),
            'phone' => $m_number,
            'remember_token' => $randomString,
            'profile_picture' => $image
        ]);

       
        
        UsersGroup::create([
            'user_id' => $user->id,
            'group_id' => 2,
        ]);

        Mail::to($request->email)->send(new RegistrationMail('Account Activation', $remember_token, config('app.address1'), config('app.address2')));

        // $user->sendEmailVerificationNotification();
        // redirect
        return redirect()->route('admin.board.index')->with('success','Board created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $params['pageTittle'] = "View Board";
        $user = User::where('id',$id)->first();
        $params['banner'] = Banner::where('user_id',$id)->get();
        // dd($params['banner']);
        $params['user'] = $user->toArray();
        $params['backUrl'] = route('admin.board.index');
        return view('admin.pages.board.view',$params);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $params['pageTittle'] = "Edit Board";
        $params['user'] = User::find($id);

        $params['backUrl'] = route('admin.board.index');
        return view('admin.pages.board.put',$params);
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
            'first_name' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            // 'email' => 'required|email|unique:users,email,'.$id,
            // 'phone' => 'required|min:14|max:16|unique:users,phone,'.$id,
        ]);        

        if(isset($request->image) && $request->image != ''){
            $dir ="images/users";
            $image = CommonHelper::imageUpload($request->image,$dir);
            $user['profile_picture'] = $image;
        }


        $user['first_name'] = $request->first_name;
        // $user['email'] = $request->email;
        // $user['phone'] = $request->phone;
        $user['is_api'] = isset($request->is_api) ? '1' : '0';
    
        User::whereId($id)->update($user);
    
        return redirect()->route('admin.board.index')
                        ->with('success','Board updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::whereId($id)->delete();
        return redirect()->route('admin.board.index')
                        ->with('success','Canteen deleted successfully');

    }
}
