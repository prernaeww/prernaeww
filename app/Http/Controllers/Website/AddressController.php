<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\ApiWebsite;
use App\Models\Address;
use CommonHelper;
use Session;

class AddressController extends Controller
{

    use ApiWebsite;

    public function index()
    {
        // $api = 'address';
        // $method = 1;
        // $response = $this->api_call($api, $method);
        $user_id = Auth::user()->id;
        $response['data'] = Address::whereUserId($user_id)->whereDeletedAt(NULL)->paginate(10);
        // dd($response);
        $mapkey = CommonHelper::ConfigGet('map_key');
        $response['mapkey'] = $mapkey;
        return view('website.pages.address.index', $response);
    }

    public function add(Request $request)
    {
        if (isset($_POST) && !empty($_POST)) {
            $api = 'address/add';
            $method = 2;
            if (!Auth::guest()) {
                $variables['user_id'] = Auth::user()->id;
            }
            $variables['complete_address'] = $_POST['address'];
            $variables['zipcode'] = $_POST['zipcode'];
            $variables['state'] = $_POST['state'];
            $variables['city'] = $_POST['city'];
            $variables['lat'] = $_POST['latitude'];
            $variables['log'] = $_POST['longitude'];
            $variables['address_type'] = $_POST['address_type'];
            $response = $this->api_call($api, $method, $variables);
            if (isset($response['status']) && $response['status']) {
                return redirect()->route('address.index')->with('success', $response['message']);
            } else {
                return redirect()->route('add_address')->with('error', 'Please select the proper street address from google suggestion.');
                return redirect()->route('add_address')->with('error', $response['message']);
            }
            return view('website.pages.address.index', $response);
        }

        $api = 'address';
        $method = 1;
        $response = $this->api_call($api, $method);
        $mapkey = CommonHelper::ConfigGet('map_key');
        $response['mapkey'] = $mapkey;

        return view('website.pages.address.add', $response);
    }

    public function edit($id)
    {
        $mapkey = CommonHelper::ConfigGet('map_key');
        $response['mapkey'] = $mapkey;
        $response['address'] = Address::whereId($id)->first();
        // $response['address'] = Address::first();
        if ($response['address']) {
            return view('website.pages.address.edit', $response);
        }
        return redirect()->route('address.index');
    }

    public function update(Request $request, $id)
    {
        if (isset($_POST) && !empty($_POST)) {
            $api = 'address/edit/' . $_POST['address_id'];
            $method = 2;
            if (!Auth::guest()) {
                $variables['user_id'] = Auth::user()->id;
            }
            $variables['complete_address'] = $_POST['address'];
            $variables['zipcode'] = $_POST['zipcode'];
            $variables['state'] = $_POST['state'];
            $variables['city'] = $_POST['city'];
            $variables['lat'] = $_POST['latitude'];
            $variables['log'] = $_POST['longitude'];
            $variables['address_type'] = $_POST['address_type'];
            $variables['address_id'] = $_POST['address_id'];
            $response = $this->api_call($api, $method, $variables);
            if (isset($response['status']) && $response['status']) {
                return redirect()->route('address.index')->with('success', $response['message']);
            } else {
                return redirect()->route('add_address')->with('error', 'Please select the proper street address from google suggestion.');
                return redirect()->route('address.edit', $id)->with('error', $response['message']);
            }
        }
        $mapkey = CommonHelper::ConfigGet('map_key');
        $response['mapkey'] = $mapkey;
        // $response['address'] = Address::whereId(114)->toSql();
        $response['address'] = Address::first();
        // dd($response['address']->toArray());
        if ($response['address']) {
            return view('website.pages.address.edit', $response);
        }
        return redirect()->route('address.index');
    }

    public function delete(Request $request)
    {
        $api = 'address/delete/' . $_POST['address_id'];
        $method = 1;
        if (!Auth::guest()) {
            $variables['user_id'] = Auth::user()->id;
        }
        $response = $this->api_call($api, $method, $variables);

        $api = 'address';
        $method = 1;
        $response = $this->api_call($api, $method);
        // dd($response);
        echo json_encode($response);
    }
}