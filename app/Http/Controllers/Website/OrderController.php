<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\ApiWebsite;
use Session;
use App\Models\Orders;

class OrderController extends Controller
{
    use ApiWebsite;

    public function index($type)
    {

        $user_id = "";
        if (!Auth::guest()) {
            $user_id = Auth::user()->id;
        }

        if ($type == 'process') {
            $orders = Orders::where('user_id', $user_id)->whereIn('status', [1, 2, 4, 5])->with(['order_products.product', 'store'])->orderBy('id', 'DESC')->paginate(10);

            foreach ($orders as $key => $value) {
                foreach ($value->order_products as $p_key => $p_value) {
                    $product_total = $p_value->price * $p_value->qty;
                    $orders[$key]['order_products'][$p_key]['product_total'] = number_format((float) $product_total, 2, '.', '');
                }
            }
            $response['inprocess'] = $orders;
        } else {
            $orders = Orders::where('user_id', $user_id)->whereIn('status', [3, 6, 7])->with(['order_products.product', 'store'])->orderBy('id', 'DESC')->paginate(10);

            foreach ($orders as $key => $value) {
                foreach ($value->order_products as $p_key => $p_value) {
                    $product_total = $p_value->price * $p_value->qty;
                    $orders[$key]['order_products'][$p_key]['product_total'] = number_format((float) $product_total, 2, '.', '');
                }
            }
            $response['history'] = $orders;
        }
        $response['type'] = $type;
        return view('website.pages.orders', $response);
    }

    public function detail($id)
    {
        $api = 'order/detail';
        $method = 2;
        $variables = array('order_id' => $id);

        $response = $this->api_call($api, $method, $variables);
        // dd($response);
        if (isset($response['status']) && $response['status']) {
            return view('website.pages.order_detail', $response);
        } else {
            return redirect()->route('home')->with('error', $response['message']);
        }
    }

    public function i_am_here($id)
    {
        $api = 'order/reached';
        $method = 2;
        $variables = array('order_id' => $id);

        $response = $this->api_call($api, $method, $variables);
        if ($response['status'] == TRUE) {
            return redirect()->to('order/detail/' . $id)->with('success', $response['message']);
        } else {
            return redirect()->to('order/detail/' . $id)->with('error', $response['message']);
        }
    }
}