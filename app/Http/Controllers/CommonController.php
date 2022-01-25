<?php



namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\ContactUs;

use App\Models\Devices;
use App\Models\User;
use App\Models\Channel;
use App\Models\Card;

use CommonHelper;
use PaymentHelper;

use App\Models\Cart;
use App\Models\CartProducts;

use App\Mail\BulkMail;
use App\Mail\ForgotMail;
use App\Mail\RegistrationMail;
use Carbon\Carbon;

use Mail;
use Crypt;
use Session;

use Validator;

use NotificationHelper;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiWebsite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class CommonController extends Controller

{
    use ApiWebsite;
    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function account_activation(Request $request, $token)
    {
        // request()->validate([
        //     'email' => 'required'
        // ]);
        // $requestData = request()->only(['email']);
        $user = User::where('remember_token', $token)->first();
        $data['address1'] = config('app.address1');
        $data['address2'] = config('app.address2');
        if ($user) {
            User::where('id', $user->id)->update([
                'email_verified_at' => Carbon::now(),
                'status' => 1,
                'remember_token' => NULL,
            ]);
            $data['message'] = 'Account Activated successfully';
            return view('template.account_activated', $data);
        } else {
            $data['message'] = 'Account Already Activated';
            return view('template.account_activated', $data);
        }
    }

    public function auth_account_activation(Request $request, $token)
    {
        $user = User::where('remember_token', $token)->first();
        $data['address1'] = config('app.address1');
        $data['address2'] = config('app.address2');
        if ($user) {
            $data['user'] = $user;
            $data['token'] = $token;
            return view('template/create-password', $data);
        } else {
            $data['message'] = 'Link has been expired';
            return view('template.account_activated', $data);
        }
    }

    public function craete_password(Request $request, $id, $token)
    {
        $user = User::where('id', $id)->where('remember_token', $token)->first();
        $data['address1'] = config('app.address1');
        $data['address2'] = config('app.address2');
        if ($user) {
            User::where('id', $id)->update([
                'password' => bcrypt($request->password),
                'email_verified_at' => Carbon::now(),
                'status' => 1,
                'remember_token' => NULL,
            ]);

            $data['message'] = 'Your password reset is successful.';
            return view('template.account_activated', $data);
        } else {
            $data['message'] = 'Link has been expired';
            return view('template.account_activated', $data);
        }
    }

    public function forgot_password(Request $request)
    {

        $validator = Validator::make(request()->all(), [
            'email' => 'required',
        ]);

        if (!$validator->fails()) {
            $user = User::whereEmail($request->email)->first();
            if ($user) {
                $status = Password::sendResetLink(
                    $request->only('email')
                );

                if ($status == Password::RESET_LINK_SENT) {
                    if ($request->type == 'store') {
                        return redirect()->route('store.forgot')->with('success', 'Reset Password link sent to your register email address.');
                    } else {
                        return redirect()->route('board.forgot')->with('success', 'Reset Password link sent to your register email address.');
                    }
                } else {
                    if ($request->type == 'store') {
                        return redirect()->route('store.forgot')->with('error', 'Email address is not registered');
                    } else {
                        return redirect()->route('board.forgot')->with('error', 'Email address is not registered');
                    }
                }
            } else {
                if ($request->type == 'store') {
                    return redirect()->route('store.forgot')->with('error', 'Account does not exist');
                } else {
                    return redirect()->route('board.forgot')->with('error', 'Account does not exist');
                }
            }
        }
        return redirect()->route('board.forgot')->with('error', $validator->messages());
        return $this->errorResponse($validator->messages(), true);
    }

    public function password_reset($phone)
    {

        try {
            // $phone = Crypt::decrypt($phone);     
            $params['phone'] = $phone;
            return view('auth.customer_reset_password', $params);
        } catch (DecryptException $e) {
            return redirect()->route('home')->with('error', 'Something went wrong');
        }
    }

    public function password_update(Request $request)
    {
        $phone = Crypt::decrypt($request->phone);
        $user = User::where('phone', $phone)->first();
        $api = 'change_password_otp';
        $method = 2;
        $variables['user_id'] = $user->id;
        $variables['password'] = $request->password;
        $variables['password_confirmation'] = $request->c_password;
        $response = $this->api_call($api, $method, $variables);
        if ($response['status'] == TRUE) {
            return redirect()->route('customer.login')->with('success', $response['message']);
        } else {
            return redirect()->route('customer.login')->with('error', $response['message']);
        }
    }

    public function store_forgot_password(Request $request)
    {
        return view('auth.storeforgot');
    }

    public function board_forgot_password(Request $request)
    {
        return view('auth.boardforgot');
    }

    public function customer_forgot_password()
    {
        return view('auth.customer-forgot-password');
    }

    public function send_email_otp()
    {

        $api = 'forgot_password';
        $method = 2;
        $request = $_POST;
        $variables['email'] = $request['email'];
        $response = $this->api_call($api, $method, $variables);
        if ($response['status'] == TRUE && isset($response['data'])) {
            $response['data']['crypt_phone'] = Crypt::encrypt($request['email']);
        }
        echo json_encode($response);
    }

    public function add_channel_id(Request $request)
    {
        if (isset($request->channel_id) && $request->channel_id != '') {
            $user_id = Auth::user()->id;;
            if (isset($user_id) && !empty($user_id)) {
                $data = Channel::whereUserId($user_id)->whereChannelId($request->channel_id)->first();
                if (isset($data) && !empty($data)) {
                    if ($data->channel_id != $request->channel_id) {
                        Channel::create([
                            'user_id' => $user_id,
                            'channel_id' => $request->channel_id,
                        ]);
                    }
                } else {
                    Channel::create([
                        'user_id' => $user_id,
                        'channel_id' => $request->channel_id,
                    ]);
                }
            }
            echo json_encode(array("is_success" => true, "post" => $request, 'restore' => TRUE));
        } else {
            echo json_encode(array("is_success" => false, "post" => $request));
        }
    }

    public function make_payment(Request $request)
    {

        // $total = 8000;
        // $response = PaymentHelper::makeRequest($request->token, (float)$total);
        // dd($response);

        $cart = Cart::whereUserId($request->user_id)->whereOrderId('0')->first();

        if ($cart) {

            $cart_products = CartProducts::whereCartId($cart->id)->with(['product'])->get();
            $user = User::find($request->user_id);

            $sub_total = '0';
            if ($user->user_type == '1') {
                foreach ($cart_products as $key => $value) {
                    if ($value->stock > 0) {
                        $product_total = $value->qty * $value->product->current_price_business;
                        $sub_total += $product_total;
                    }
                }
            } else {
                foreach ($cart_products as $key => $value) {
                    if ($value->stock > 0) {
                        $product_total = $value->qty * $value->product->current_price_retail;
                        $sub_total += $product_total;
                    }
                }
            }

            $data['sub_total'] = number_format((float) $sub_total, 2, '.', '');
            $tax = CommonHelper::ConfigGet('tax');
            if ($tax > 0) {
                $tax_amount = ($tax / 100) * $data['sub_total'];
                $data['tax'] = number_format((float) $tax_amount, 2, '.', '');
                $total = $data['sub_total'] + $tax_amount;
                $total = number_format((float) $total, 2, '.', '');
            } else {
                $total = $data['sub_total'];
            }

            //dd((float)$total);

            $response = PaymentHelper::makeRequest($request->token, (float) $total);
            //dd($response);
            if ($response->ResponseCode == "00000") {

                $ExpirationDate = str_split($response->responseMessage->ExpirationDate, 2);
                $card_types = config('app.card_type');

                $key = array_search($response->responseMessage->CardType, $card_types);
                if ($key == FALSE) {
                    $key = 6;
                }

                $insert_data = array(
                    'user_id' => $request->user_id,
                    'card_number' => $response->responseMessage->MaskedPan,
                    'expiry_month_year' => $ExpirationDate[0] . '/' . $ExpirationDate[1],
                    'type' => $key,
                    'token' => $request->token
                );

                $card = Card::insert($insert_data);

                $GatewayTransID = $response->responseMessage->GatewayTransID;
                $TransactionID = $response->TransactionID;

                if ($request->payment_type == 'website') {

                    $place_order_deltails = Session::get('place_order');
                    $api = 'order';
                    $method = 2;
                    $variables['cart_id'] = $place_order_deltails['cart_id'];
                    $variables['name'] = $place_order_deltails['name'];
                    $variables['number'] = $place_order_deltails['number'];
                    $variables['pickup_notes'] = $place_order_deltails['pickup_notes'];
                    $variables['pickup_method'] = $place_order_deltails['pickup_method'];
                    $variables['vehicle_description'] = $place_order_deltails['vehicle_description'];
                    $variables['transaction_id'] = $TransactionID;
                    $variables['gateway_trans_id'] = $GatewayTransID;
                    $response = $this->api_call($api, $method, $variables);
                    /*forgot place order Detail session*/
                    Session::forget('place_order');

                    if ($response['status'] == false) {

                        Session::flash('error', $response['message']);
                        return redirect('/cart');
                    } else {
                        Session::flash('success', $response['message']);
                        return redirect('/order/detail/' . $response['data']['order_id']);
                    }
                } else {
                    return redirect('transaction_success/' . $TransactionID . '/' . $GatewayTransID);
                }


                // return redirect('transaction_success/'.$request->order_id);
            } else {

                if ($request->payment_type == 'website'){
                    Session::flash('error', 'Payment transaction failed');
                    return redirect('/cart');
                }else{
                    return redirect('transaction_fail/' . $request->user_id);
                }
                
            }
        } else {

            $params['main_text'] = "FAILED";
            $params['sub_text'] = "No any product in cart.";
            if ($request->payment_type == 'website') {
                Session::flash('error', 'No any product in cart.');
                return redirect('/cart');
            } else {
                return view('transaction_fail', $params);
            }
        }
    }

    public function transaction_fail($id)
    {
        $params['main_text'] = "FAILED";
        $params['sub_text'] = "Payment Transaction Failed.";
        return view('transaction_fail', $params);
    }

    public function transaction_success($TransactionID, $GatewayTransID)
    {
        // public function transaction_success($TransactionID){
        $params['main_text'] = "SUCCESS";
        $params['sub_text'] = "Payment Transaction Completed Successfully.";
        return view('transaction_fail', $params);
    }

    public function generate_token($id)
    {
        $user = User::where('status', '1')->where('id', $id)->first();
        if ($user) {
            $params['user_id'] = $id;
            $params['payment_type'] = (isset($_GET['payment_type'])) ? $_GET['payment_type'] : '';
            return view('payment', $params);
        } else {
            $params['main_text'] = "SOMETHING WENT WRONG";
            $params['sub_text'] = "User not found. Try again later.";
            return view('transaction_fail', $params);
        }

        // 4005 5500 0000 0019
    }

    public function refund($TransactionID, $GatewayTransID)
    {
        //http://127.0.0.1:8000/transaction_success/20211207015446/4047169805
        $response = PaymentHelper::makeRefund($TransactionID, $GatewayTransID);
        dd($response);
        if ($response->ResponseCode == "00000") {
            $GatewayTransID = $response->responseMessage->GatewayTransID;
            $TransactionID = $response->TransactionID;
            return redirect('transaction_success/' . $TransactionID . '/' . $GatewayTransID);
        } else if ($response->ResponseCode == "10042") {
            // Already refunded
            $GatewayTransID = $response->responseMessage->GatewayTransID;
            $TransactionID = $response->TransactionID;
            return redirect('transaction_fail/' . $TransactionID);
        } else {
            return redirect('transaction_fail/' . $TransactionID);
        }
    }

    public function save_card(Request $request)
    {
        $user_id = Auth::user()->id;

        /*put place order Detail session*/
        $request_data = $request->all();

        if (isset($request_data['number']) && $request_data['number'] != '') {
            $m_number = str_replace("(", "", $request_data['number']);
            $m_number = str_replace(")", "", $m_number);
            $m_number = str_replace("-", "", $m_number);
            $m_number = ltrim($m_number, '1');
            $request_data['number'] = $m_number;

            $api = 'cart';
            $method = 1;
            $response = $this->api_call($api, $method);
            if (!$response['status']) {
                return redirect()->route('home')->with('error', $response['message']);
            }

            Session::put('place_order', $request_data);
            $api = 'card';
            $method = 1;
            $response = $this->api_call($api, $method);
            if (count($response['data']) > 0) {
                return view('website.pages.saved_cards', $response);
            } else {
                return redirect('/generate_token/' . $user_id . '?payment_type=website');
            }
        } else {
            return abort(403, 'Invalid request.');
        }
    }

    public function save_card_list(Request $request)
    {
        $api = 'card';
        $method = 1;
        $response = $this->api_call($api, $method);
        return view('website.pages.saved_cards_list', $response);
    }

    public function save_card_delete(Request $request)
    {
        $api = 'card/delete/' . $_POST['card_id'];
        $method = 1;
        $response = $this->api_call($api, $method);
        return json_encode($response);
    }

    public function proceed_to_payment(Request $request)
    {

        $user_id = request()->user()->id;
        if ($user_id) {

            $card_id = Crypt::decrypt($request->card_id);
            $api = 'pay_by_card/' . $card_id;
            $method = 1;
            $response = $this->api_call($api, $method);
            if ($response['status'] == false) {
                Session::flash('error', $response['message']);
                return json_encode($response);
            } else {

                $place_order_deltails = Session::get('place_order');

                $api = 'order';
                $method = 2;
                $variables['cart_id'] = $place_order_deltails['cart_id'];
                $variables['name'] = $place_order_deltails['name'];
                $variables['number'] = $place_order_deltails['number'];
                $variables['pickup_notes'] = $place_order_deltails['pickup_notes'];
                $variables['pickup_method'] = $place_order_deltails['pickup_method'];
                $variables['vehicle_description'] = $place_order_deltails['vehicle_description'];
                $variables['transaction_id'] = $response['data']['TransactionID'];
                $variables['gateway_trans_id'] = $response['data']['GatewayTransID'];

                $response = $this->api_call($api, $method, $variables);
                Session::forget('place_order');
                if ($response['status'] == true) {
                    Session::flash('success', $response['message']);
                } else {
                    Session::flash('error', $response['message']);
                }
                return json_encode($response);
            }
        } else {
            Session::flash('error', 'To proceed further please Sign in.');
            return json_encode(array('status' => false));
        }
    }

    public function test_generate_token($price)
    {
        $response['price'] = $price;
        return view('test_payment', $response);

        // 4005 5500 0000 0019
    }

    public function test_make_payment(Request $request)
    {
        $response = PaymentHelper::makeRequest($request->token, $_POST['price']);
        dd($response);
        if ($response->ResponseCode == "00000") {

            // $ExpirationDate = str_split($response->responseMessage->ExpirationDate, 2);
            // $card_types = config('app.card_type');

            // $key = array_search($response->responseMessage->CardType, $card_types);
            // if ($key == FALSE) {
            //     $key = 6;
            // }

            // $insert_data = array(
            //     'user_id' => $request->user_id,
            //     'card_number' => $response->responseMessage->MaskedPan,
            //     'expiry_month_year' => $ExpirationDate[0] . '/' . $ExpirationDate[1],
            //     'type' => $key,
            //     'token' => $request->token
            // );

            // $card = Card::insert($insert_data);

            $GatewayTransID = $response->responseMessage->GatewayTransID;
            $TransactionID = $response->TransactionID;
            var_dump($GatewayTransID);
            // var_dump($response->responseMessage->ReferenceNumber);
            exit;
            dd($response);
            return redirect('transaction_success/' . $TransactionID . '/' . $GatewayTransID);
            // return redirect('transaction_success/'.$request->order_id);
        } else {
            echo 'payment failed';
            dd($response);
            return redirect('transaction_fail/123');
        }
    }
}