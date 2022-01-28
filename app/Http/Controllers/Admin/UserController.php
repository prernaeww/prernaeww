<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str; 
use App\Models\UsersGroup;
use App\Models\Devices;
use App\Models\Group;
use App\Models\User;
use App\Models\Family;
use App\Models\Category;
use App\Models\Product;
use App\Models\AppleDetail;
use App\Models\Cart;
use App\Models\CartProducts;
use App\Models\StoresProduct;
use App\Models\FavoriteProduct;
use App\Models\FavoriteStore;
use Carbon\Carbon;
use CommonHelper;
use DataTables;

// use App\Traits\DatatableTraits;

class UserController extends Controller {
    // use DatatableTraits;

    public function index(Request $request) 
    {

        // event(new UserNotify(User::find(1)));
        if ($request->ajax()) {
            //    $data = User::select('*')->where('id','!=',1)->get();
            $data = User::select('users.*', 'users_group.group_id')->join('users_group', 'users.id', '=', 'users_group.user_id')->where('users_group.group_id',4)->get();
            $datat = Datatables::of($data);

            if ($request->has('groups')) {
                $datat->filter(function ($instance) use ($request) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        if ($request->get('groups') == "all") {
                            return true;
                        }
                        return $row['group_id'] == $request->get('groups') ? true : false;
                    });
                    
                });
            }

            return $datat->addIndexColumn()
            ->editColumn('action', function ($row)
            {
               
               $btn = '<a href="'.route('admin.user.show', $row['id']) . '" class="mr-2"><i class="fa fa-eye"></i></a>';

               
               $btn .= '<a href="' . route('admin.user.edit', $row['id']) . '" class="mr-2"><i class="fa fa-edit"></i></a>';
              

               $btn .= '<a href="'.route('admin.user.destroy', $row['id']).'" data-url="store" data-id="'.$row["id"].'" data-popup="tooltip" onclick="delete_notiflix(this);return false;" data-token="'.csrf_token().'" ><i class="fa fa-trash"></i></a>';

               return $btn;
            })

            ->editColumn('user_type', function ($row)
            {
               if($row['user_type']== 0)
               {

                    return 'Retailer';
               }elseif($row['user_type']== 1)
               {

                   return 'Business';
               }else
               {

                    return '-';
               }

               
            })

            ->editColumn('status', function ($row) {
                    if ($row['group'] == 6) {
                        if ($row['status'] == 0) {
                            return '<button onclick="active_deactive(this);" data-id="' . $row['id'] . '" data-token="' . csrf_token() . '"   class="btn btn-danger btn-xs waves-effect waves-light" data-table="users" data-status="2">In Active</button>';
                        }
                    }
                    if ($row['status'] == 0) {
                        return '<span class="badge bg-soft-warning text-warning">Pending</span>';
                    } elseif ($row['status'] == 1) {
                        return '<button onclick="active_deactive(this);" data-id="' . $row['id'] . '" data-token="' . csrf_token() . '"   class="btn btn-success btn-xs waves-effect waves-light" data-table="users" data-status="' . $row['status'] . '">Active</button>';
                    } else {
                        return '<button onclick="active_deactive(this);" data-id="' . $row['id'] . '" data-token="' . csrf_token() . '"  class="btn btn-danger btn-xs waves-effect waves-light" data-table="users" data-status="' . $row['status'] . '">Inactive</button>';
                    }
                })
                ->rawColumns(['status','user_type' ,'action', 'group'])
                ->make(true);

        } else {
            $columns = [
                ['data' => 'id', 'name' => 'id', 'title' => "Id"],
                ['data' => 'user_type', 'name' => 'user_type', 'title' => __("User Type")],
                ['data' => 'first_name', 'name' => 'first_name', 'title' => __("First Name")],
                ['data' => 'last_name', 'name' => 'last_name', 'title' => __("Last Name")],
                ['data' => 'email', 'name' => 'email', 'title' => __("Email")],
                ['data' => 'phone_formatted', 'name' => 'phone_formatted', 'title' => __("Phone"),'searchable'=>false],
                ['data' => 'status', 'title' => __("Status"), 'searchable' => false],
                ['data' => 'action', 'name' => 'action', 'title' => "Action", 'searchable' => false, 'orderable' => false]];
            $groups = Group::get();
            $params['dateTableFields'] = $columns;
            $params['dateTableUrl'] = route('admin.users.index');
            $params['dateTableTitle'] = "User Management";
            $params['dataTableId'] = time();
            $params['groups'] = $groups;
            return view('admin.pages.users.index', $params);
        }
    }

    public function show($id) {
        $params['pageTittle'] = "View User";

        $user = User::where('id', $id)->first();
        // dd($user);     
        $user_group = UsersGroup::where('user_id', $id)->first();

        $params['user'] = $user->toArray();
        $params['user_group'] = $user_group->toArray();
        $params['backUrl'] = route('admin.users.index');
        return view('admin.pages.users.view', $params);
    }

    public function edit($id)
    {
        
        $params['pageTittle'] = "Edit User";
        $params['user'] = User::find($id);
        $params['backUrl'] = route('admin.users.index');
        return view('admin.pages.users.put',$params);
    }

    public function update(Request $request, $id)
    {
        // dd('he');
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'dob'=>'required',
            
            // 'email' => 'required|email|unique:users,email,'.$id,
            // 'phone' => 'required|min:14|max:16|unique:users,phone,'.$id,
        ]);        

        if(isset($request->image) && $request->image != ''){
            $dir ="images/users";
            $image = CommonHelper::imageUpload($request->image,$dir);
            $user['profile_picture'] = $image;
        }
        
       
        // dd($user['user_type']);
        $user['user_type'] = $request->user_type;
        $user['business_name'] = $request->business_name;
        $user['first_name'] = $request->first_name;
        $user['last_name'] = $request->last_name;
        // $user['email'] = $request->email;
        // $user['phone'] = $request->phone;
        $user['dob'] = Carbon::createFromFormat('m-d-Y', $request->dob)->format('Y-m-d');

        User::whereId($id)->update($user);
    
        return redirect()->route('admin.users.index')
                        ->with('success','Store updated successfully');
    }


    public function destroy($id)
    {
        $user = User::where('id', $id)->first();                
        if (isset($user)) {
            $user->revokeAllTokens();
        }
        User::whereId($id)->delete();
        AppleDetail::whereEmail($user->email)->delete();
        return redirect()->route('admin.store.index')
                        ->with('success','Store deleted successfully');
    }



    public function active_deactive() {
        if ($_POST['table'] == 'users') {
            if ($_POST['status'] == 1) {
                $status = 2;
                // if ($_POST['table_user'] == 'store') {
                    $cart_ids = Cart::whereStoreId($_POST['id'])->pluck('id');
                    Cart::whereStoreId($_POST['id'])->delete();
                    CartProducts::whereIn('cart_id', $cart_ids)->delete();

                    FavoriteProduct::whereStoreId($_POST['id'])->delete();
                    FavoriteStore::whereStoreId($_POST['id'])->delete();
                // }
            } else {                
                $status = 1;
            }
            $user = User::where('id', $_POST['id'])->first();
            if (isset($user)) {
                if($status == '2'){
                    $user->revokeAllTokens();
                }
                User::where('id', $_POST['id'])->update(['status' => $status]);
            }
        } 

        if($_POST['table'] == 'family'){
            if($_POST['status'] == 0){
                $status = 1;
            }else{
                $status = 0;
            }
            Family::where('id', $_POST['id'])->update(['status' => $status]);
        }

        if($_POST['table'] == 'category'){
            if($_POST['status'] == 0){
                $status = 1;
            }else{
                $status = 0;
            }
            Category::where('id', $_POST['id'])->update(['status' => $status]);
        }

         if($_POST['table'] == 'products'){
            if($_POST['status'] == 0){
                $status = 1;
            }else{
                $status = 0;

                FavoriteProduct::whereProductId($_POST['id'])->delete();
                StoresProduct::whereProductId($_POST['id'])->delete();

                // $cart_products = CartProducts::whereProductId($_POST['id'])->get();
                // if(isset($cart_products) && !empty($cart_products)){

                //     foreach ($cart_products as $key => $value) {
                //         $c_d = CartProducts::where('product_id', '!=',$_POST['id'])->where('cart_id', $value->cart_id)->get();
                //         $count = $c_d->count();
                //         if($count == 0){
                //             Cart::where('id', $value->cart_id)->delete();
                //         }
                //         CartProducts::where('id', $value->id)->delete();
                //     }
                    
                // }
            }
            Product::where('id', $_POST['id'])->update(['status' => $status]);
        }
        echo $status;
    }

    public function active_deactive_store_board() {        
        $data['status'] = TRUE;
        $data['message'] = 'Updated';
        if ($_POST['table'] == 'users') {
            if ($_POST['table_user'] == 'store') {
                if ($_POST['status'] == 1) {
                    $status = 2;                    
                    // $cart_ids = Cart::whereStoreId($_POST['id'])->pluck('id');
                    // Cart::whereStoreId($_POST['id'])->delete();
                    // CartProducts::whereIn('cart_id', $cart_ids)->delete();

                    FavoriteProduct::whereStoreId($_POST['id'])->delete();
                    FavoriteStore::whereStoreId($_POST['id'])->delete();                    
                } else
                {
                    $board = User::whereId($_POST['id'])->first();
                    $parent_id = User::whereId($_POST['id'])->value('parent_id');
                    $board_status = User::whereId($parent_id)->value('status');
                    if (isset($board_status) && !empty($board_status) && $board_status != 2) {
                        $status = 1;
                        $data['status'] = TRUE;
                        $data['message'] = 'Updated';
                    } else
                    {                     
                        $status = 2;   
                        $data['status'] = false;
                        $data['message'] = 'Board '.$board->first_name.' is deactivated. So, you can not active this store. Please first active Board '.$board->last_name;
                    }
                }                
            } else {
                if ($_POST['table_user'] == 'board') {                    
                    if ($_POST['status'] == 1) {
                        $board = User::whereId($_POST['id'])->first();
                        $store_status = User::whereParentId($_POST['id'])->whereStatus(1)->value('status');
                        if (isset($store_status) && $store_status != 2) {
                            $status = 1;
                            $data['status'] = false;
                            $data['message'] = 'Please deactivate all stores of '.$board->first_name.' '.$board->last_name.' board then you can deactivate this board';
                        } else
                        {                
                            $status = 2;
                            $data['status'] = TRUE;
                            $data['message'] = 'Updated';
                        }
                    } else
                    {   
                        $status = 1;
                        $data['status'] = TRUE;
                        $data['message'] = 'Updated';
                    }
                }
            }            
        }
        User::where('id', $_POST['id'])->update(['status' => $status]);
        echo json_encode($data);
        // echo $status;
    }

}
