<?php



namespace App\Http\Controllers\Api;



use App\Http\Controllers\Controller;

use App\Http\Requests\Api\LoginRequest;

use App\Http\Requests\Api\SocialLoginRequest;

use App\Http\Resources\Api\AppleDetailResource;

use App\Http\Resources\UserResource;

use App\Models\AppleDetail;

use App\Models\User;

use CommonHelper;

use App\Models\Devices;
use App\Models\UsersGroup;
use App\Traits\ApiResponser;
use App\Services\UserRegisterService;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Str;
use Validator;

use App\Mail\BulkMail;

use Mail;

use App\Models\Cart;

use NotificationHelper;
use Carbon\Carbon;

class LoginController extends Controller

{
    use ApiResponser;
    protected $userRegisterService;



    public function __construct(UserRegisterService $userRegisterService)

    {

        $this->userRegisterService = $userRegisterService;

    }

    /**

     *  logs in user with email or password

     */

    public function login(LoginRequest $request)

    {

        if (!Auth::attempt(['email' => $request->username, 'password' => $request->password]) && !Auth::attempt(['username' => $request->username, 'password' => $request->password])) {

            return response()->json([

                'status' => false,

                'message' => 'Invalid login details'

            ], 200);

        }



        // $user = User::where('email', $request->email)->firstOrFail();

        // $user->expireAllTokens();

        // $user->device_token = $request->device_token;

        // $user->update();

        // $token = $user->createToken('authToken')->accessToken;

        $user = User::find($user_id);

        $token = $user->createToken('authToken')->accessToken;




        return (new UserResource($user))->additional([

            'status' => 'true',

            'bearer_token' => $token,

            'message' => 'Logged In successfully'

        ]);

    }



    /**

     *  logs in social login user

     */

    // public function socialLogin(SocialLoginRequest $request)
    public function socialLogin(Request $request)
    
    {       

        $validator = Validator::make($request->all(),[
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'email' => 'required|email',
            'device_type' => 'required',
            'device_token' => 'required',
            'social_id' => 'required',
            'social_type' => 'required',
            //'phone' => 'required',
        ], [
            'profile_picture.image' => 'Accepted file formats: jpg, jpeg, png, heic',
            'profile_picture.max' => 'Maximum file size should be 5 MB',
        ]);

        if($validator->fails()){
           return response([
                'success' => false,
                'message' => $validator->errors()->all(),
            ], 200);
        }

        $firstName = request()->input('first_name');
        $lastName = request()->input('last_name');
        $email = request()->input('email');        
        $deviceType = request()->input('device_type');
        $deviceToken = request()->input('device_token');
        $socialId = request()->input('social_id');        
        $socialType = request()->input('social_type');
        //$phone = request()->input('phone');
        $profile_picture = $request->profile_picture;


        
        $user = User::where('email', $email)->first();        
        //if the user already exists
        
        if ($user) {

            if($user->status == '0'){
                return response()->json([                
                    'status' => false,                    
                    'message' => __('Sorry! Your account is inactive. Please activate it from the link which we have sent on your registered email')                    
                ]);
            }else if($user->status == '2'){
                return response()->json([                
                    'status' => false,                    
                    'message' => __('Sorry! Your account deactivated. Contact admin for any query.')                    
                ]);
            }else{                
                $devices = Devices::where('user_id', $user->id)->update([
                    'name' => '',
                    'token' => $deviceToken,
                    'type' => $deviceType
                ]);
            }

        }else{

            //if no user is found then creating new
            $user = $this->userRegisterService->run($firstName, $lastName, $email, $email, '', null, null, $deviceType, $deviceToken, $socialId, $socialType, 0, '', '0');
            User::where('id', $user->id)->update([
                'email_verified_at' => Carbon::now(),
                'status' => 1,
                'phone_verified' => 0
            ]);

            Devices::create([
                'user_id' => $user->id,
                'name' => '',
                'token' => $deviceToken,
                'type' => $deviceType,
            ]);
            // send mail
            Mail::to($email)->send(new BulkMail(config('app.name'), $firstName.' '.$lastName, $email, config('app.address1'), config('app.address2')));
            
            // if (isset($profile_picture)) {
            //     $dir = "images/users";
            //     $profile_picture = CommonHelper::imageUpload($profile_picture, $dir);
            //     $devices = User::where('id', $user->id)->update(['profile_picture' => $profile_picture, 'email_verified_at' => Carbon::now()]);
            // }

            if(isset($request->profile_picture) && $request->profile_picture != ''){
                $dir = "images/users/";
                $upload = CommonHelper::imageUploadByLink($request->profile_picture, $dir);
                if($upload['status'] == TRUE){
                    $profile_picture = $upload['image_name'];
                    User::where('id', $user->id)->update(['profile_picture' => $profile_picture]);
                    $user->profile_picture = $profile_picture;
                }
                
            }

            UsersGroup::create([
                'user_id' => $user->id,
                'group_id' => '4',
            ]);


        }
        
        if ($socialType == "apple") {
            //if it is apple the registering the apple user
            $this->registerAppleUser($firstName, $lastName, $email, $socialId);
            
        }

        $user->revokeAllTokens();
        
        $user->token = $user->createToken('auth_token')->accessToken;
        
        $cart = Cart::whereUserId($user->id)->whereOrderId('0')->with(['cart_products'])->first();
        $cart_products_count = 0;
        if($cart){
            $cart_products_count = count($cart->cart_products);
        }
        $user->cart_total = $cart_products_count;

        return (new UserResource($user))->additional([
            'status' => true,
            'message' => 'Logged In successfully',

        ]);

    }
    
    /**

     *  registers apple user or updates it

     */

    public function registerAppleUser($firstName = null, $lastName = null, $email, $appleId)

    {

        $appleUser = AppleDetail::where('apple_id', $appleId)->first();



        if ($appleUser) {

            //if user exists then return this

            return $appleUser;

        }



        return AppleDetail::create([

            'first_name' => $firstName,

            'last_name' => $lastName,

            'email' => $email,

            'apple_id' => $appleId,

        ]);



        //else return the new apple user

    }





    /**

     *  returns the apple account details

     */

    public function appleDetails(Request $request, $appleId)
    {
        $accountDetails =  AppleDetail::where('apple_id', $appleId)->first();
        if (!$accountDetails) {

            return response()->json([

                'status' =>  false,

                'message' => __('There was an error connecting to the Apple ID server. Go to Setting -> Password & Security->App using your Apple ID->'.env("APP_NAME").'->Stop using apple ID')

            ]);

        }



        return (new AppleDetailResource($accountDetails))->additional([

            'status' =>  true,

            'message' => 'successfully'

        ]);

    }



    /**

     *  logs user out

     */

    public function logOut()

    {

        $user = request()->user();

        $user->device_token = null;

        $user->update();

        //remove the device details



        $user->currentAccessToken()->delete();

        //revoke current token



        return response()->json([

            'status' =>  true,

            'message' => __('messages.api.logged_out')

        ]);

    }

}

