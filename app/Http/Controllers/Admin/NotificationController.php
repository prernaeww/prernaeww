<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Issue;
use DataTables;
use App\Models\OrdersDate;
use App\Models\User;
use App\Mail\BulkMail;
use App\Models\School;
use Mail;
use NotificationHelper;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $params['pageTittle'] = "Send Bulk Notification";
        // $params['backUrl'] = route('admin.canteen.index');
        $params['breadcrumb_name'] = 'all';
        $params['school'] = School::select('*')->where('status', 1)->get();
        return view('admin.pages.mail.index', $params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $title = request()->input('title');
        $description = request()->input('description');
        $user_type = request()->input('user_type');
        $school_id = request()->input('school_id');
        if ($user_type == '2') {
            $data = User::select('users.*', 'users_group.group_id')->join('users_group', 'users.id', '=', 'users_group.user_id')->where('users_group.group_id', '=', $user_type)->get();

            Mail::to($data)->send(new BulkMail($title, $description));

            return redirect()->route('admin.bulk.index')->with('success', 'Email send successfully.');
        } else if ($user_type == '3') {
            // echo "under development...";exit;
            // $school_data = User::select('*')->whereIn('users.school',$school_id)->where('parent_id', '0')->pluck('parent_id');
            $parents = User::select('users.*', 'users_group.group_id')->join('users_group', 'users.id', '=', 'users_group.user_id')->where('users_group.group_id', '=', $user_type)->pluck('users.id');

            $data = User::select('users.*', 'users_group.group_id')->with(['devices'])->join('users_group', 'users.id', '=', 'users_group.user_id')->whereIn('users.school', $school_id)->whereIn('users.parent_id', $parents)->get();
            $token = array();
            $push_type = 'bulk';

            foreach ($data as $row) {
                if (isset($row->devices->token) && !empty($row->devices->token)) {
                    array_push($token, $row->devices->token);
                }
            }
            $tokens = [];
            $success_count = '0';
            $failure_count = '0';
            if (!empty($token)) {
                $token = array_unique($token);
                $tokens = array_chunk($token, 900);
                foreach ($tokens as $val) {
                    $response = NotificationHelper::send_bulk($val, $title, $description, $push_type);
                    $success_count += $response['success'];
                    $failure_count += $response['failure'];
                }
            }
            return redirect()->route('admin.bulk.index')->with('success', 'Notification send successfully. success (' . $success_count . ') | failure (' . $failure_count . ')');
        } else {
            $data = User::select('users.*', 'users_group.group_id')->with(['devices'])->join('users_group', 'users.id', '=', 'users_group.user_id')->whereIn('users.school', $school_id)
                ->where('users_group.group_id', $user_type)->get();

            $token = array();
            $push_type = 'bulk';

            foreach ($data as $row) {
                if (isset($row->devices->token) && !empty($row->devices->token)) {
                    array_push($token, $row->devices->token);
                }
            }

            $tokens = [];
            $success_count = '0';
            $failure_count = '0';
            if (!empty($token)) {
                $token = array_unique($token);
                $tokens = array_chunk($token, 900);
                foreach ($tokens as $val) {
                    $response = NotificationHelper::send_bulk($val, $title, $description, $push_type);
                    $success_count += $response['success'];
                    $failure_count += $response['failure'];
                }
            }

            return redirect()->route('admin.bulk.index')->with('success', 'Notification send successfully. success (' . $success_count . ') | failure (' . $failure_count . ')');
        }
    }
}