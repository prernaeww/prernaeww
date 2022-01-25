<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\ApiWebsite;
use Session;

class ContactUsController extends Controller
{
    use ApiWebsite;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // dd('helo');
        return view('website.pages.contactus');
    }


    public function store(Request $request)
    {
        //
        // dd($request->all());
        $request->validate([
            'email' => 'required',
            'message' => 'required',
            'document'=>'image|mimes:jpeg,png,jpg,gif,svg'
            
        ]);
        $api = 'contact_us';
        $method = 2;
        // dd($method);
        $variables = array('email' => $request->email, 'message' => $request->message);
        if (!Auth::guest()){
            $variables['user_id'] = Auth::user()->id;
        }

        $response = $this->api_call($api, $method, $variables);
        // dd($response);
        // return view('website.pages.contactus');
        return back()->with('success',$response['message']);
    }

}
