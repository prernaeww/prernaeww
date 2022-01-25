<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StoresProduct;
use App\Models\UsersGroup;
use App\Models\AppleDetail;
use App\Models\Devices;
use DataTables;
use CommonHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Auth;
use Mail;
use App\Mail\RegistrationMail;

class StoreController extends Controller
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
            $data = User::select('users.*')->join('users_group', 'users.id', '=', 'users_group.user_id')->orderBy('id','DESC')->where('users_group.group_id',3)->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('store_name', function ($row)
            {
                
                return $row['first_name'] . " " . $row["last_name"];

               
            })
            ->editColumn('profile_picture', function ($row) {
                if($row['profile_picture'] == ""){
                    $profile_picture = "/images/default.png";
                }else{
                    $profile_picture =$row['profile_picture'];
                }
                return '<img class="border rounded p-0"  src="'.$profile_picture.'" onerror="this.src=/images/default.png" alt="your image" style="height: 70px; object-fit: scale-down;" id="blah1"/>';
            })
            ->editColumn('action', function ($row)
            {
               $btn = '<a href="'.route('admin.store.edit',$row['id']).'" class="mr-2"><i class="fa fa-edit"></i></a>';

               $btn .= '<a href="'.route('admin.store.show',$row['id']).'" class="mr-2"><i class="fa fa-eye"></i></a>';
               // $btn .= '<a href="'.route('admin.store.destroy', $row['id']).'" data-url="store" data-id="'.$row["id"].'" data-popup="tooltip" onclick="delete_notiflix(this);return false;" data-token="'.csrf_token().'" ><i class="fa fa-trash"></i></a>';
               return $btn;
            })
            ->editColumn('status', function ($row)
            {
                if($row['status'] == 0){
                    return '<span class="badge bg-soft-warning text-warning">Pending</span>';
                }elseif($row['status'] == 1){
                    return '<button onclick="active_deactive_store_board(this);" data-id="' . $row['id'] . '" data-token="' . csrf_token() . '"   class="btn btn-success btn-xs waves-effect waves-light" data-table="users" data-table_user="store" data-status="' . $row['status']. '">Active</button>';
                }else{
                    return '<button onclick="active_deactive_store_board(this);" data-id="' . $row['id'] . '" data-token="' . csrf_token() . '"  class="btn btn-danger btn-xs waves-effect waves-light" data-table="users" data-table_user="store" data-status="' . $row['status']. '">Inactive</button>';
                }
            })
            ->rawColumns(['profile_picture','store_name', 'action','status'])
            ->make(true);
       }
       else
       {
           $columns = [
               ['data' => 'id','name' => 'users.id','title' => "Id"], 
               ['data' => 'profile_picture','name' => 'users.profile_picture', 'title' => __("Profile Picture"),'searchable'=>true],
               ['data' => 'store_name', 'name' => 'store_name','title' => __("Store Name") ,'searchable'=>true],
               ['data' => 'email', 'name' => 'email','title' => __("Email"),'searchable'=>true],
               ['data' => 'phone_formatted', 'name' => 'phone','title' => __("Phone"),'searchable'=>true],
               ['data' => 'status','title' => __("Status"),'searchable'=>false],
               ['data' => 'action', 'name' => 'action','title' => "Action",'searchable'=>false,'orderable'=>false]];
           $params['dateTableFields'] = $columns;
           $params['dateTableUrl'] = route('admin.store.index');
           $params['dateTableTitle'] = "Store Management";
           $params['dataTableId'] = time();
           $params['addUrl'] = route('admin.store.create');
           return view('admin.pages.store.index',$params);
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
        $params['pageTittle'] = "Add Store";
        $params['backUrl'] = route('admin.store.index');
        $mapkey = CommonHelper::ConfigGet('map_key');
        // dd($mapkey);
        $data = User::select('users.*')->join('users_group', 'users.id', '=', 'users_group.user_id')->where('users_group.group_id',2)->get();
        // dd($data);
        return view('admin.pages.store.post',compact('data','mapkey'),$params);
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
        // dd($request->all());
        $request->validate([
            'first_name' => 'required',
            'email' => 'email|required|unique:users,email',
            'parent_id' => 'required',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'phone' => 'required|unique:users,phone|min:14|max:14',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $dir = "images/users";
        $image = CommonHelper::imageUpload($request->image,$dir);
        
        // $phone = CommonHelper::RemovePhoneFormat($request->phone);
        $delivery_type_data = $request->delivery_type;
        if(count($delivery_type_data) == 2)
        {
            $delivery_type = 3;
        }else
        {
            $delivery_type = $delivery_type_data[0];
        }

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
            'phone' =>$m_number,
            'address' =>$request->address,
            'zipcode' =>$request->zipcode,
            'latitude' =>$request->latitude,
            'longitude' =>$request->longitude,
            'parent_id' => $request->parent_id,
            'start_time' => date('h:i A', strtotime($request->start_time)),
            'end_time' => date('h:i A', strtotime($request->end_time)),
            'delivery_type' => $delivery_type,
            'remember_token' => $randomString,
            'profile_picture' => $image
        ]);

        Mail::to($request->email)->send(new RegistrationMail('Account Activation', $remember_token, config('app.address1'), config('app.address2')));

        $user['status'] = 1;
        $user['password'] = Hash::make('12345678');
        $user['email_verified_at'] = Carbon::now();

        UsersGroup::create([
            'user_id' => $user->id,
            'group_id' => 3,
        ]);

        // $user->sendEmailVerificationNotification();
        // redirect
        return redirect()->route('admin.store.index')->with('success','Store created successfully.');
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
        $params['user'] = User::find($id);
        // dd($params['user']);
        // $login_id =  Auth::user()->id;
        // dd($login_id);

        $params['storeproducts']=StoresProduct::where('user_id',$id)->with('product')->get();
        // dd($params['storeproducts']);
        $params['backUrl'] = route('admin.store.index');
        return view('admin.pages.store.view',$params);
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
        $params['pageTittle'] = "Edit Store";
        $params['user'] = User::find($id);
        
        $params['backUrl'] = route('admin.store.index');
        $data = User::select('users.*')->join('users_group', 'users.id', '=', 'users_group.user_id')->where('users_group.group_id',2)->get();
        return view('admin.pages.store.put',compact('data'),$params);
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
        $request->validate([
            'first_name' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);        

        if(isset($request->image) && $request->image != ''){
            $dir ="images/users";
            $image = CommonHelper::imageUpload($request->image,$dir);
            $user['profile_picture'] = $image;
        }
        $user['first_name'] = $request->first_name;
        $user['last_name'] = $request->last_name;
        $user['address'] = $request->address;
        $user['zipcode'] = $request->zipcode;
        $user['latitude'] = $request->latitude;
        $user['longitude'] = $request->longitude;
        $user['start_time'] = date('h:i A', strtotime($request->start_time));
        $user['end_time'] = date('h:i A', strtotime($request->end_time));
        $user['parent_id'] = $request->parent_id;

        $delivery_type_data = $request->delivery_type;
        if(count($delivery_type_data) == 2)
        {
            $delivery_type = 3;
        }else
        {
            $delivery_type = $delivery_type_data[0];
        }
        $user['delivery_type'] = $delivery_type;

        User::whereId($id)->update($user);
    
        return redirect()->route('admin.store.index')
                        ->with('success','Store updated successfully');
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
        $user = User::where('id', $id)->first();
        AppleDetail::whereEmail($user->email)->delete();
        Devices::whereUserId($id)->delete();

        User::whereId($id)->delete();
        return redirect()->route('admin.store.index')
                        ->with('success','Store deleted successfully');
    }
}
