<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\FavoriteProduct;
use Illuminate\Http\Request;
use App\Traits\ApiWebsite;
use Session;


class CartController extends Controller
{
    use ApiWebsite;

    public function index()
    {
        $api = 'cart';
        $method = 1;
        $response = $this->api_call($api, $method);

        return view('website.pages.cart', $response);
    }

    public function checkout()
    {
        $api = 'cart';
        $method = 1;
        $response = $this->api_call($api, $method);
        if ($response['status']) {
            return view('website.pages.checkout', $response);
        }
        return redirect()->route('home')->with('error', $response['message']);
    }

    public function place_order(Request $request)
    {

        $api = 'order';
        $method = 2;
        $variables = array(
            'cart_id' => $request->cart_id,
            'name' => $request->name,
            'number' => $request->number,
            'pickup_notes' => $request->pickup_notes,
            'pickup_method' => $request->pickup_method,
            'vehicle_description' => $request->vehicle_description,
            'transaction_id' => 1,
            'gateway_trans_id' => 1
        );

        $response = $this->api_call($api, $method, $variables);
        return redirect()->route('home', $response)->with('success', 'Order placed successfully');
    }

    public function qty_update()
    {
        $api = 'cart/edit';
        $method = 2;
        $variables['cart_product_id'] = $_POST['cart_product_id'];
        $variables['update'] = $_POST['update_qty'];
        $response = $this->api_call($api, $method, $variables);
        return json_encode($response);
    }

    public function cart_verify()
    {

        $api = 'cart/verify';
        $method = 1;
        $response = $this->api_call($api, $method);

        if ($response['status'] == false && $response['product_deleted'] == True) {

            Session::flash('success', 'All data available');
        }

        return json_encode($response);
    }


    public function destroy($id)
    {
        $api = 'cart/remove_product';
        $method = 2;
        $variables['cart_product_id'] = $_POST['cart_product_id'];
        $response = $this->api_call($api, $method, $variables);
        return json_encode($response);
    }

    public function add_to_cart(Request $request)
    {
        $auth = Auth::user();
        if (empty($auth)) {
            Session::flash('error', 'To proceed further please Sign in.');
            return json_encode(array('status' => false, 'redirect' => 'login'));
        } else if ($auth->phone_verified == 0) {
            Session::flash('error', 'To proceed further please enter Mobile Number.');
            return json_encode(array('status' => false, 'redirect' => 'account'));
        }

        if ($request->clear == 'clear') {
            $api = 'cart/clear';
            $method = 1;
            $response = $this->api_call($api, $method);
        }
        $api = 'cart/add';
        $method = 2;
        $variables = $request->all();
        $response = $this->api_call($api, $method, $variables);

        /*get cart count*/
        $cart_products_count = 0;
        if (!Auth::guest()) {
            $api = 'cart';
            $method = 1;
            $response = $this->api_call($api, $method);
            if ($response['status'] == TRUE) {
                $cart_products_count = $response['data']['cart_products_count'];
            }
        }
        $response['cart_products_count'] = $cart_products_count;
        return json_encode($response);
    }
}