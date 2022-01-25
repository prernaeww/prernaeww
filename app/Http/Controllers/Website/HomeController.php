<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\ApiWebsite;
use App\Models\User;
use App\Models\Category;
use App\Models\StoresProduct;
use App\Models\Product;
use App\Models\FavoriteProduct;
use CommonHelper;
use Session;
use \Crypt;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Providers\RouteServiceProvider;
use Socialite;


class HomeController extends Controller
{
    use ApiWebsite;

    public function index()
    {

        // init 
        $api = 'init/ios/0.0.0';
        $method = 1;
        $response = $this->api_call($api, $method);

        $mapkey = CommonHelper::ConfigGet('map_key');
        $response['mapkey'] = $mapkey;

        return view('website.pages.home', $response);
    }

    public function signup()
    {
        return view('auth.customer_register');
    }

    public function send_otp()
    {
        return view('auth.send_otp');
    }

    public function send_otp_mobile()
    {
        $request = $_POST;
        $api = 'send_otp';
        $method = 2;

        // $variables['email'] = $request['email'];

        $m_number = str_replace("(","",$request['phone']);
        $m_number = str_replace(")","",$m_number);
        $m_number = str_replace("-","",$m_number);
        $m_number = ltrim($m_number , '1');
        $variables['phone'] = $m_number;

        $response = $this->api_call($api, $method, $variables);
        if($response['status'] == TRUE){
            if(isset($response['data']['otp'])){
                $response['data']['crypt_phone'] = Crypt::encrypt($m_number);
            }
        }
        return json_encode($response);
    }

    public function register_customer(Request $request)
    {

        //$form_data = array();
        // foreach ($request->form_data as $key => $value) {
        //     $form_data[$value['name']] = $form_data[$value['value']];
        // }
        //dd($form_data);


        $device = $request->header('User-Agent');

        $api = 'register';
        $method = 2;

        $m_number = str_replace("(","",$request->phone);
        $m_number = str_replace(")","",$m_number);
        $m_number = str_replace("-","",$m_number);
        $m_number = ltrim($m_number , '1');
        // dd($m_number);
        $variables = array('email' => $request->email, 'password' => $request->password, 'device_type' => 'web', 'device_token' => 'website', 'first_name' => $request->first_name, 'last_name' => $request->last_name, 'phone' => $m_number, 'device_name' => $device, 'user_type' => $request->user_type);

        if ($request->user_type == '1') {
            $variables['business_name'] = $request->business_name;
        } else {
            $variables['business_name'] = '';
        }
        $response = $this->api_call($api, $method, $variables);
        if (isset($request->profile_picture) && $request->profile_picture != '' && $request->profile_picture != 'undefined' && $response['status']) {
            $user_id = $response['data']['id'];
            $dir = "images/users";
            $profile_picture = CommonHelper::imageUpload($request->profile_picture, $dir);
            $user_data['profile_picture'] = $profile_picture;
            User::whereId($user_id)->update($user_data);
        }
        return json_encode($response);
    }

    public function deals()
    {

        $api = 'store/deals';
        $method = 2;
        $variables = array('latitude' => '27.55222', 'longitude' => '-72.66565');
        if (!Auth::guest()) {
            $variables['user_id'] = Auth::user()->id;
        }

        $response = $this->api_call($api, $method, $variables);
        return view('website.pages.deals', $response);
    }

    public function product($store_id, $product_id)
    {
        try {
            $store_id = Crypt::decrypt($store_id);
        } catch (DecryptException $e) {
            return redirect()->route('home')->with('error', 'No store found');
        }

        // $product_id = Crypt::decrypt($product_id);     
        // try {

        // } catch (DecryptException $e) {
        //     return redirect()->route('home')->with('error', 'No store found');            
        // }

        $api = 'store/details';
        $method = 2;
        $variables = array('store_id' => $store_id, 'product_id' => $product_id);
        if (!Auth::guest()) {
            $variables['user_id'] = Auth::user()->id;
        }
        $response = $this->api_call($api, $method, $variables);
        if ($response['status'] == false) {
            if ($response['product_deleted'] == true) {
                return redirect('/');
            }
        }
        $response['store_id'] = $store_id;
        return view('website.pages.product_detail', $response);
    }

    public function store_list(Request $request)
    {
        $api = 'store/store_list';
        $method = 2;
        if (!Auth::guest()) {
            $variables['user_id'] = Auth::user()->id;
        }
        $variables['latitude'] = $_POST['latitude'];
        $variables['longitude'] = $_POST['longitude'];

        $response['near_by_store'] = $this->api_call($api, $method, $variables);
        // dd($response);

        $variables['favorite'] = 1;

        $response['favorite_store'] = $this->api_call($api, $method, $variables);
        if (!Auth::guest()) {
            $response['user_logged_in'] = TRUE;
        }else{
            $response['user_logged_in'] = FALSE;
        }
        return json_encode($response);
    }

    public function store_detail(Request $request, $id)
    {
        $api = 'store/product';
        $method = 2;
        if (!Auth::guest()) {
            $variables['user_id'] = Auth::user()->id;
        }
        $variables['store_id'] = $id;

        $response = $this->api_call($api, $method, $variables);
        $response['store'] = User::whereId($id)->where('status', '1')->first();
        // dd($response);
        return view('website.pages.product_listing', $response);
    }

    public function product_view_all(Request $request, $store_id, $category_id)
    {
        try {
            $store_id = Crypt::decrypt($store_id);
        } catch (DecryptException $e) {
            return redirect()->route('home')->with('error', 'No store found');
        }
        $is_product_on_sale = "";
        if (!Auth::guest()) {
            $user_id = Auth::user()->id;
        }

        if ($category_id == 'new-arrivals') {
            $response['title'] = 'New Arrivals';
        } elseif ($category_id == 'product-on-sale') {

            $is_product_on_sale = 1;
            $response['title'] = 'Products On Sale';
        } elseif ($category_id == 'best-deals') {

            $is_product_on_sale = 1;
            $response['title'] = 'Best Deals';
        } else {
            $category_data = Category::whereId($category_id)->first();
            $response['title'] = isset($category_data) ? $category_data->name : '';
        }

        $response['store'] = User::whereId($store_id)->where('status', '1')->first();


        $store = User::where('id', $store_id)->where('status', 1)->first();
        $store_products = StoresProduct::whereUserId($store_id)->where('stock', '>', 0)->get();
        $product_data = [];
        if (isset($store_products)) {

            $products = $store_products->pluck('product_id')->toArray();

            if (isset($request->text) && $request->text != '') {
                $mathched_category = Category::where('name', 'like', '%' . $request->text . '%')->whereStatus('1')->get()->pluck('id')->toArray();
                if ($mathched_category) {
                    $matched_products = Product::whereNotIn('id', $products)->whereStatus('1')->whereIn('category_id', $mathched_category)->pluck('id')->toArray();
                }
            }

            $category = Category::where('id', $category_id)->whereStatus('1')->first();
            if ($category) {
                $category_condition = ($category_id && $category_id != '') ? $category_id : NULL;
            } else {
                $category_condition = NULL;
            }
            $product = Product::query()->whereStatus("1")->whereIn('id', $products);

            $product->when($category_condition, function ($query) use ($category_id) {
                $query->where('category_id', $category_id)->orderBy('id', 'DESC');
            });

            $onsale_condition = ($is_product_on_sale && $is_product_on_sale == '1') ? $is_product_on_sale : NULL;


            $user_id = isset($user_id) ? $user_id : null;
            $user_type = User::whereId($user_id)->value('user_type');
            $discount = $user_type == 1 ? 'business_discount' : 'retail_discount';

            $product->when($onsale_condition, function ($query) use ($discount) {
                $query->where($discount, '>', '0')->orderBy($discount, 'DESC');
            });

            $text_condition = (isset($request->text) && $request->text != '') ? $request->text : NULL;
            $text = $request->text;

            // $product->when($text_condition, function ($query) use ($matched_products) {
            $product->when($text_condition, function ($query) use ($text) {
                $query->where(function ($query) use ($text) {
                    $query->where('name', 'like', '%' . $text . '%')->orWhereHas('category', function ($query) use ($text) {
                        $query->where('name', 'like', '%' . $text . '%');
                    });
                });
            });

            $product_data = $product->paginate(10);

            if (isset($product_data) && count($product_data) > 0) {

                if (isset($user_id) && $user_id != '') {
                    $favorite_products = FavoriteProduct::whereUserId($user_id)->whereStoreId($store_id)->get()->pluck('product_id')->toArray();
                }

                foreach ($product_data as $key => $value) {
                    $product_data[$key]['favorite'] = FALSE;

                    if (isset($favorite_products) && count($favorite_products) > 0) {
                        if (in_array($value['id'], $favorite_products)) {
                            $product_data[$key]['favorite'] = TRUE;
                        }
                    }
                }
            }
        }

        $response['data'] = $product_data;
        $response['category_id'] = $category_id;


        return view('website.pages.product_view_all', $response);
    }

    public function search_product(Request $request)
    {
        $request = $_POST;
        try {
            $store_id = Crypt::decrypt($request['store_id']);
        } catch (DecryptException $e) {
            return redirect()->route('home')->with('error', 'No store found');
        }
        $api = 'store/search';
        $method = 2;
        if (!Auth::guest()) {
            $variables['user_id'] = Auth::user()->id;
        }
        $variables['store_id'] = $store_id;
        if (isset($request['category_id']) && $request['category_id'] != '') {
            $variables['category_id'] = $request['category_id'];
        }
        $variables['text'] = isset($request['text']) ? $request['text'] : '';

        $response = $this->api_call($api, $method, $variables);
        return json_encode($response);
    }

    public function facebook_login()
    {

        $fb_client_id = CommonHelper::ConfigGet('fb_client_id');
        $fb_client_secret = CommonHelper::ConfigGet('fb_client_secret');
        $redirect_uri = route('facebook-redirect');

        if (isset($_GET['code']) && !empty($_GET['code'])) {
            $code = $_GET['code'];
            $resp =  $this->get_fb_contents("https://graph.facebook.com/oauth/access_token?client_id=" . $fb_client_id . "&redirect_uri=" . $redirect_uri . "&client_secret=" . $fb_client_secret . "&code=" . urlencode($code));

            if (!empty($resp->access_token)) {

                $fb_user_info = $this->get_fb_contents("https://graph.facebook.com/me?fields=name,email,gender,first_name,last_name,picture&access_token=" . $resp->access_token);

                $api = 'social-login';
                $method = 2;

                $variables['email'] = $fb_user_info->email;
                $variables['device_type'] = 'web';
                $variables['device_token'] = 'web';
                $variables['social_id'] = $fb_user_info->id;
                $variables['social_type'] = 'facebook';
                $variables['first_name'] = $fb_user_info->first_name;
                $variables['last_name'] = $fb_user_info->last_name;

                $response = $this->api_call($api, $method, $variables);

                if ($response['status'] == TRUE) {
                    $user = Auth::loginUsingId($response['data']['id'], TRUE);
                    Session::put('token', $response['data']['token']);
                    return redirect()->intended(RouteServiceProvider::CUSTOMER)->with('success', 'You have been successfully logged in.');
                } else {
                    return redirect('/login')->with('error', $response['message']);
                }
            }
        }
    }

    public function google_login()
    {


        $user = Socialite::driver('google')->user();
        //dd($user);
        $api = 'social-login';
        $method = 2;
        $variables = array(
            'email' => $user->email,
            'device_type' => 'web',
            'device_token' => 'web',
            'social_id' => $user->id,
            'social_type' => 'facebook',
            'first_name' => $user->user['given_name'],
            'last_name' => $user->user['family_name']
        );
        $response = $this->api_call($api, $method, $variables);

        if ($response['status'] == TRUE) {
            $credentials['email'] = $response['data']['email'];
            $credentials['password'] = $response['data']['email'];
            if (Auth::attempt($credentials)) {
                Session::put('user', $response['data']);
                $accessToken = Auth::user()->createToken('authToken')->accessToken;
                Session::put('token', $accessToken);
                return redirect()->intended(RouteServiceProvider::CUSTOMER)->with('success', 'You have been successfully logged in.');
            } else {
                return redirect('/login')->with('error', 'Incorrect email or password!');
            }
        } else {
            return redirect()->route('login')->with('error', $response['message']);
        }
    }

    function get_fb_contents($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }


    public function appleredirect()
    {
        return Socialite::driver('apple')->redirect();

        // return Socialite::driver("sign-in-with-apple")
        //          ->scopes(["name", "email"])
        //        ->redirect();
    }
}