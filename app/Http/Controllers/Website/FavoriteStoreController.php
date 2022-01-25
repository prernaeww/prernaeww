<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\ApiWebsite;
use Session;
use App\Models\FavoriteProduct;


class FavoriteStoreController extends Controller
{
    use ApiWebsite;

    public function index()
    {   
        $api = 'favorite_product';    
        $method = 1;
        $response = $this->api_call($api, $method);  
        return view('website.pages.saved', $response);
    }

    public function add_remove_fav_store() 
    {        
	
	    $auth = Auth::user();
		if(empty($auth))
		{
			Session::flash('error', 'To proceed further please Sign in.');  
			return json_encode(array('status'=>false,'redirect'=>'login'));
		}
		else if($auth->phone_verified == 0){
			Session::flash('error', 'To proceed further please enter Mobile Number.');  
			return json_encode(array('status'=>false,'redirect'=>'account'));
		}
	
        $variables['user_id'] = '';
        if (!Auth::guest()){
            $user_id = Auth::user()->id;
            $request = $_POST;

            $variables['store_id'] = $request['store_id'];
            $method = 2;
            if($request['add_remove'] == 0){

                $api = 'favorite_store/remove';
                $data = $this->api_call($api, $method, $variables);
            }else{
                $api = 'favorite_store/add';
                $data = $this->api_call($api, $method, $variables);
            }
        }else{
            $data['status'] = FALSE;
            $data['message'] = "Unauthenticated";
            $data['redirect_login'] = TRUE;
        }
        echo json_encode($data); 
        
    }

}
