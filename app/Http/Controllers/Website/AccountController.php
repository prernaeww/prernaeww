<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\ApiWebsite;
use App\Models\User;
use Carbon\Carbon;
use Session;
use \Crypt;
use CommonHelper;

class AccountController extends Controller
{
    use ApiWebsite;
    public function index()
    {
        $data['education'] = CommonHelper::ConfigGet('education_out_reach');
        $data['interest'] = CommonHelper::ConfigGet('interest_bases_ads');
        return view('website.pages.account',$data);
    }

    public function update(Request $request)
    {
        // dd($request->all());
        if(isset($request->image) && $request->image != ''){
            //dd("SDf");
            $dir ="images/users";
            $image = CommonHelper::imageUpload($request->image, $dir);
            $user['profile_picture'] = $image;
        }

        
        //$user['business_name'] = $request->business_name;
        $user['first_name'] = $request->first_name;
        $user['last_name'] = $request->last_name;
        if(isset($request->dob) && $request->dob != ''){
            $user['dob'] = Carbon::createFromFormat('m-d-Y', $request->dob)->format('Y-m-d');
        }
        
        User::whereId(Auth::user()->id)->update($user);

        return redirect()->route('account')->with('success','Profile updated successfully');
    
        
    }

    public function add_phone_number($phone)
    {
        $user['phone'] = Crypt::decrypt($phone);
        $user['phone_verified'] = '1';
        
        User::whereId(Auth::user()->id)->update($user);

        return redirect()->route('account')->with('success','Phone number added');
    
        
    }

    public function notification_toggle()
    {
        
        $request = $_POST;
        $status = ($request['status'] == '1')?'0':'1';
        $api = 'notifications_status_change';    
        $method = 2;
        $variables['status'] = $status;
        $response = $this->api_call($api, $method,$variables);
        return json_encode($response);
    }

    public function notification(Request $request)
    {        
        $api = 'notifications';
        $method = 2;                
        $response = $this->api_call($api, $method);
        return json_encode($response);
    }
}
