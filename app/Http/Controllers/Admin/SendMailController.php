<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Issue;
use DataTables;
use App\Models\OrdersDate;
use App\Models\User;
use Mail;
use NotificationHelper;

class SendMailController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'title' => 'required',
        //     'description' => 'required',
        //     'user_ids'=>'required'            
        // ]);
        $title = request()->input('title');
        $user_type = request()->input('user_type');
        $description = request()->input('description');
        $user_ids = request()->input('user_ids');

        if (isset($user_type) &&  isset($title)) {
            $title = request()->input('title');
            $description = request()->input('description');
            if ($user_type == 'select') {
                $data = User::whereIn('id', $user_ids)->get();
            } else {
                $data = User::select('users.*', 'users_group.group_id')->join('users_group', 'users.id', '=', 'users_group.user_id')->where('users_group.group_id', '=', '3')->get();
            }

            Mail::to($data)->send(new SendMail($title, $description, config('app.address1'), config('app.address2')));

            return redirect()->route('admin.mail.store')->with('success', 'Email send successfully.');
        }

        $params['type'] = "Store";
        $params['pageTittle'] = "Send Bulk Email";
        // $params['backUrl'] = route('admin.canteen.index');
        $params['breadcrumb_name'] = 'all';
        $params['users'] = User::select('users.*', 'users_group.group_id')->join('users_group', 'users.id', '=', 'users_group.user_id')->where('users_group.group_id', '=', '3')->get();
        return view('admin.pages.mail.index', $params);
    }

    public function board(Request $request)
    {
        // $request->validate([
        //     'title' => 'required',
        //     'description' => 'required',
        //     'user_ids'=>'required'            
        // ]);        
        $title = request()->input('title');
        $description = request()->input('description');
        $user_ids = request()->input('user_ids');
        if (isset($user_type) &&  isset($title)) {
            $title = request()->input('title');
            $description = request()->input('description');
            if ($user_type == 'select') {
                $data = User::whereIn('id', $user_ids)->get();
            } else {
                $data = User::select('users.*', 'users_group.group_id')->join('users_group', 'users.id', '=', 'users_group.user_id')->where('users_group.group_id', '=', '2')->get();
            }

            Mail::to($data)->send(new SendMail($title, $description, config('app.address1'), config('app.address2')));

            return redirect()->route('admin.mail.board')->with('success', 'Email send successfully.');
        }

        $params['type'] = "Board";
        $params['pageTittle'] = "Send Bulk Email";
        // $params['backUrl'] = route('admin.canteen.index');
        $params['breadcrumb_name'] = 'all';
        $params['users'] = User::select('users.*', 'users_group.group_id')->join('users_group', 'users.id', '=', 'users_group.user_id')->where('users_group.group_id', '=', '2')->get();
        return view('admin.pages.mail.index', $params);
    }

    public function customer(Request $request)
    {
        // $request->validate([
        //     'title' => 'required',
        //     'description' => 'required',
        //     'user_ids'=>'required'            
        // ]);
        $title = request()->input('title');
        $description = request()->input('description');
        $user_ids = request()->input('user_ids');
        if (isset($user_type) &&  isset($title)) {
            $title = request()->input('title');
            $description = request()->input('description');
            if ($user_type == 'select') {
                $data = User::whereIn('id', $user_ids)->get();
            } else {
                $data = User::select('users.*', 'users_group.group_id')->join('users_group', 'users.id', '=', 'users_group.user_id')->where('users_group.group_id', '=', '4')->get();
            }

            Mail::to($data)->send(new SendMail($title, $description, config('app.address1'), config('app.address2')));

            return redirect()->route('admin.mail.customer')->with('success', 'Email send successfully.');
        }

        $params['type'] = "Customer";
        $params['pageTittle'] = "Send Bulk Email";
        // $params['backUrl'] = route('admin.canteen.index');
        $params['breadcrumb_name'] = 'all';
        $params['users'] = User::select('users.*', 'users_group.group_id')->join('users_group', 'users.id', '=', 'users_group.user_id')->where('users_group.group_id', '=', '4')->get();
        return view('admin.pages.mail.index', $params);
    }
}