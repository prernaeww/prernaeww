<?php

namespace App\Http\Controllers\Api;



use App\Http\Controllers\Controller;

use App\Models\AppVersion;

use App\Models\Devices;

use App\Models\Banner;

use App\Models\Orders;

use App\Models\User;

use App\Models\Cart;

use App\Models\UsersGroup;

use App\Traits\ApiResponser;

use CommonHelper;

use Illuminate\Auth\Events\Registered;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Password;

use Illuminate\Support\Facades\Validator;

use Carbon\Carbon;

use App\Http\Resources\UserResource;

use Mail;
use App\Mail\RegistrationMail;
use NotificationHelper;

class AuthController extends Controller {

    use ApiResponser;



    public function login(Request $request) {

        $validator = Validator::make(request()->all(), [

            'email' => 'required',

            'password' => 'required',

            'device_name' => 'required',

            'device_token' => 'required',

            'device_type' => 'required',

        ]);

        if (!$validator->fails()) {

            // Added for email-phone login  Start
            $login = request()->input('email');

            if(is_numeric($login)){
                $field = 'phone';
            } elseif (filter_var($login, FILTER_VALIDATE_EMAIL)) {
                $field = 'email';
            } else{
                return response([
                    'status' => false,
                    'message' => 'Email/Phone number incorrect format.',
                ]);
            }

            // Added for email-phone login  End

            $user_data = array($field => $request->email, 'password' => $request->password);

            if (!auth()->attempt($user_data)) {

                return $this->errorResponse(__('Entered email address or phone number does not match with the records'));

            }

            $user = auth()->user();

           /*  $user->tokens->each(function($token, $key) {

                $token->delete();

            }); */

            if($user->hasVerifiedEmail()){

                if($user->isActive()){

                    if (in_array($user->hasRole(), [4])) {

                        $user->revokeAllTokens();

                        $accessToken = auth()->user()->createToken('authToken')->accessToken;

                        $cart_products_count = 0;
                        $cart = Cart::whereUserId($user->id)->whereOrderId('0')->with(['cart_products'])->first();                        
                        if($cart){
                            $cart_products_count = count($cart->cart_products);
                        }

                        $user->token = $accessToken;
                        $user->cart_total = $cart_products_count;

                        $devices = Devices::where('user_id', $user->id)->update([

                            'name' => $request->device_name,

                            'token' => $request->device_token,

                            'type' => $request->device_type,

                        ]);


                        // return $this->successResponse($user, __('Login Successfully'));
                        return (new UserResource($user))->additional([
                            'status' => true,
                            // 'token' => $accessToken,
                            'message' => 'Login successfully'
                        ]);

                    }else {

                        return $this->errorResponse(__('Entered email address or phone number does not match with the records'));

                    }

                }else{

                    return $this->errorResponse(__('Sorry! Your account has been inactive by the system'));

                }

            } else {

                return $this->errorResponse(__('Please verify your account, verification link sent in entered email while registration'));

                // return $this->errorResponse(__('Sorry! Your account has been inactive by the system'));

            }

        }

        return $this->errorResponse($validator->messages(), true);

    }



    public function register(Request $request) {

        $validator = Validator::make(request()->all(), [

            'first_name' => 'required',

            'last_name' => 'required',

            'class' => 'min:1|max:15',

            'email' => 'email|unique:users,email,NULL,id,deleted_at,NULL',

            'phone' => 'unique:users,phone,NULL,id,deleted_at,NULL|min:10|max:10',            

            // 'group_id' => 'required',

            'profile_picture' => 'image|mimes:jpeg,png,jpg|max:5120',

            'device_name' => 'required',

            'device_token' => 'required',

            'device_type' => 'required',

            'user_type' => 'required',

        ],[

            'profile_picture.image' => 'Accepted file formats: jpg, jpeg, png',

            'profile_picture.max' => 'Maximum file size should be 5 MB',

        ]);

        if (!$validator->fails()) {

            $user_data = [];

            $user_data['password'] = Hash::make($request->password);

            if (isset($request->profile_picture)) {

                $dir = "images/users";

                $profile_picture = CommonHelper::imageUpload($request->profile_picture, $dir);

                $user_data['profile_picture'] = $profile_picture;

            }

            $user_data['first_name'] = $request->first_name;

            $user_data['last_name'] = $request->last_name;

            if (isset($request->email) && $request->email != '') {

                $user_data['email'] = $request->email;

            }

            if (isset($request->phone) && $request->phone != '') {

                $user_data['phone'] = $request->phone;

            }            

            

            if (isset($request->dob) && $request->dob != '') {

                $user_data['dob'] = $request->dob;

            }



            if (isset($request->user_type) && $request->user_type != '') {

                $user_data['user_type'] = $request->user_type;

            }



            if (isset($request->business_name) && $request->business_name != '') {

                $user_data['business_name'] = $request->business_name;

            }



            // if(isset($request->group_id) && $request->group_id == 6){

                // $user_data['status'] = 1;

                // $user_data['email_verified_at'] = Carbon::now();

            // }

            $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomString = substr(str_shuffle(str_repeat($pool, 5)), 0, 64);
            $remember_token = config('app.url').'account-activation/'.$randomString;
            $user_data['remember_token'] = $randomString;
            $user_id = User::insertGetId($user_data);            
            Mail::to($request->email)->send(new RegistrationMail('Account Activation', $remember_token, config('app.address1'), config('app.address2')));

            UsersGroup::create([

                'user_id' => $user_id,

                'group_id' => '4',

            ]);

            

            Devices::create([

                'user_id' => $user_id,

                'name' => $request->device_name,

                'token' => $request->device_token,

                'type' => $request->device_type,

            ]);

            $user = User::find($user_id);

            $accessToken = $user->createToken('authToken')->accessToken;

            $user->token = $accessToken;

            $user->is_order = false;

            event(new Registered($user));

            // $user->sendEmailVerificationNotification();

            // return $this->successResponse($user, __("Please verify your account from your registered email address"));

            // return $this->successResponse($user, __("Registered successfully"));
            return (new UserResource($user))->additional([
                'status' => true,
                // 'token' => $accessToken,
                'message' => 'Register successfully! Please verify your account, verification link sent in entered email.'
            ]);

        }

        return $this->errorResponse($validator->messages(), true);

    }



    public function forgot_password(Request $request) {

        $validator = Validator::make(request()->all(), [

            'email' => 'required',

        ]);



        if (!$validator->fails()) {

            $login = request()->input('email');

            if(is_numeric($login)){
                $field = 'phone';
                $user = User::wherePhone($request->email)->first();

                if($user){
                    $data['otp'] = rand(100000,999999);
                    $data['user_id'] = $user->id;
                    return response([
                        'status' => true,
                        'message' => 'Otp sent successfully',
                        'data' => $data
                    ]);
                }else{
                    return response([
                        'status' => false,
                        'message' => 'Phone number is not registered',
                    ]);
                }
                
            } elseif (filter_var($login, FILTER_VALIDATE_EMAIL)) {
                $field = 'email';
                $user = User::whereEmail($request->email)->first();

                if($user){
                    $status = Password::sendResetLink(
                        $request->only('email')
                    );

                    if ($status == Password::RESET_LINK_SENT) {
                        return response([
                            'status' => true,
                            'message' => 'Change password link sent in entered email'
                        ]);
                    }else{
                        return $this->errorResponse(__("Email address is not registered"));
                    }
                }else{
                    return response([
                        'status' => false,
                        'message' => 'Email address is not registered',
                    ]);
                }
                

            } else{
                return response([
                    'status' => false,
                    'message' => 'Email/Phone number incorrect format.',
                ]);
            }

            

            //  $user = User::where( 'email', $request->email )->first();

            //   $passwordReset = PasswordReset::updateOrCreate(

            //    [ 'email' => $user->email ],

            //    [

            //       'email' => $user->email,

            //       'token' => str_random( 60 )

            //    ]

            // );



            // echo $status.'--'. Password::RESET_LINK_SENT;exit;

            
            // return $this->successResponse([], __("Reset Password link sent to your register email address."));

            // return $this->errorResponse(__("Sorry we can't able to sent reset password link to this email address"));

            

        }

        return $this->errorResponse($validator->messages(), true);

    }


    public function init($type = "", $app_version = "") {

        if ($app_version != "" && $type != "") {

            $system_version = AppVersion::whereType($type)->first();

            $current_version = (Int) str_replace('.', '', $system_version->version);

            $app_version = (Int) str_replace('.', '', $app_version);

            $url['education_outreach'] = config('app.url').'education-outreach';
            $url['about_us'] = config('app.url').'about-us';
            $url['contact_us'] = config('app.url').'contact-us';
            $url['internet_based_ads'] = config('app.url').'internet-based-ads';
            $url['privacy_notice'] = config('app.url').'privacy-notice';
            $url['term_of_service'] = config('app.url').'term-of-service';
            $data['force_update'] = false;
            if ($system_version->maintenance == 1) {

                $data['status'] = false;

                $data['update'] = false;

                $data['maintenance'] = true;

                $data['message'] = __('Server under maintenance, please try again after some time');

                $data['url'] = $url;

                return response($data);

            } else if ($app_version < $current_version && $system_version->force_update == 0) {

                $data['status'] = false;

                $data['update'] = true;
                $data['force_update'] = false;

                $data['maintenance'] = false;

                $data['message'] = __('App new version available , please upgrade your application');

                $data['url'] = $url;

                return response($data);

            } else if ($app_version < $current_version && $system_version->force_update == 1) {

                $data['status'] = false;

                $data['update'] = true;
                $data['force_update'] = true;

                $data['maintenance'] = false;

                $data['message'] = __('App new version available , please upgrade your application');

                $data['url'] = $url;

                return response($data);

            } else {

                $data['status'] = true;

                $data['update'] = false;

                $data['maintenance'] = false;

                $data['message'] = __('Success');

                $data['url'] = $url;

                return response($data);

            }

        }

        return $this->errorResponse(__('Missing App version & Type...'));

    }

    public function send_otp(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'email' => 'email:rfc,dns',
            'phone' => 'required|numeric'
        ]);

        if (!$validator->fails()) {

            if(isset($request->email)){
                $user_email = User::whereEmail($request->email)->first();

                if(!empty($user_email))
                {
                    return $this->errorResponse(__("This email address is already registered"));
                }
            }

           
           $user_phone = User::wherePhone($request->phone)->first();
           if(!empty($user_phone))
           {
             return $this->errorResponse(__("This mobile number is already registered"));
           }

           $data['otp'] = rand(100000,999999);
           $message = 'Your ABCTOGO OTP is '.$data['otp'];
           $result = NotificationHelper::send_sms($message, $request->phone);
           if($result['status'] == TRUE){
                //$data['result'] = $result;

                return response([
                    'status' => true,
                    'message' => 'Otp sent successfully',
                    'data' => $data
                ]);
           }else{
                return response([
                    'status' => FALSE,
                    'message' => 'Invalid phone number'
                    // 'message' => $result['error']
                ]);
           }
           

        }

        return $this->errorResponse($validator->messages(), true);

    }



    public function check_email(Request $request)

    {

        $validator = Validator::make(request()->all(), [

            'email' => 'required',

            // 'phone' => 'required',

        ]);



        if (!$validator->fails()) {

           $user_email = User::whereEmail($request->email)->first();

           if(!empty($user_email))

           {

             return $this->errorResponse(__("This email address is already registered"));

           }

           $user_phone = User::wherePhone($request->phone)->first();

           if(!empty($user_phone))

           {

             return $this->errorResponse(__("This mobile number is already registered"));

           }

             return $this->successResponse([], __('success'));

        }

        return $this->errorResponse($validator->messages(), true);

    }



    public function check_phone(Request $request)

    {

        $validator = Validator::make(request()->all(), [            

            'phone' => 'required',

        ]);



        if (!$validator->fails()) {

           // $user_email = User::whereEmail($request->email)->first();

           // if(!empty($user_email))

           // {

           //   return $this->errorResponse(__("This email address is already registered"));

           // }

           $user_phone = User::wherePhone($request->phone)->first();

           if(!empty($user_phone))

           {

             return $this->errorResponse(__("This mobile number is already registered"));

           }

             return $this->successResponse([], __('success'));

        }

        return $this->errorResponse($validator->messages(), true);

    }



    public function test_upload(Request $request) {



        if ($request->hasFile('profile_picture')) {

                        $dir = "images/users";

                        $profile_picture = CommonHelper::s3Upload($request->file('profile_picture'), $dir);

                        $update_data['profile_picture'] = $profile_picture;

                    }

    }

}

