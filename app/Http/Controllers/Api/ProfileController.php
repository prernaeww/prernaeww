<?php

namespace App\Http\Controllers\Api;



use App\Http\Controllers\Controller;

use App\Models\Devices;

use App\Models\Orders;

use App\Models\User;

use App\Traits\ApiResponser;

use App\Models\Notification;

use CommonHelper;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;

use App\Http\Requests\Api\ChangePasswordRequest;

use App\Http\Resources\UserResource;



class ProfileController extends Controller {

    use ApiResponser;



    public function profile_view_edit(Request $request) {

        $validator = Validator::make(request()->all(), [

            // 'user_id' => 'required',

            'profile_picture' => 'image|mimes:jpeg,png,jpg,heic',

            'first_name' => 'min:2|max:15',

            'last_name' => 'min:2|max:15',            
            'business_name' => '',            

            // 'phone' => 'min:10|max:11|unique:users,phone,' . request()->user()->id,

        ], [

            'profile_picture.image' => 'Accepted file formats: jpg, jpeg, png,heic'

            // 'profile_picture.max' => 'Maximum file size should be 5 MB',

        ]);

        if (!$validator->fails()) {

            $user = User::where('id', request()->user()->id)->first();

            if (isset($user)) {

                if (in_array($user->hasRole(), [ 4])) {

                    if ($request->hasFile('profile_picture')) {

                        // $dir = env('AWS_S3_MODE')."/users";

                        // $profile_picture = CommonHelper::s3Upload($request->file('profile_picture'), $dir);

                        $dir = "images/users";

                        $profile_picture = CommonHelper::imageUpload($request->profile_picture, $dir);

                        $update_data['profile_picture'] = $profile_picture;

                    }

                    if (isset($request->first_name) && $request->first_name != '') {

                        $update_data['first_name'] = $request->first_name;

                    }

                    if (isset($request->last_name) && $request->last_name != '') {

                        $update_data['last_name'] = $request->last_name;

                    }

                    if (isset($request->phone) && $request->phone != '') {

                        $update_data['phone'] = $request->phone;
                        $update_data['phone_verified'] = '1';

                    }

                    if (isset($request->dob) && $request->dob != '') {

                        $update_data['dob'] = $request->dob;

                    }

                    if(auth()->user()->user_type == '1'){
                        if (isset($request->business_name) && $request->business_name != ''){
                            $update_data['business_name'] = $request->business_name;
                        }
                    }

                    if (isset($update_data) && !empty($update_data)) {

                        User::whereId(request()->user()->id)->update($update_data);

                        $user = User::where('id', request()->user()->id)->first();

                        $accessToken = $user->createToken('authToken')->accessToken;

                        $user->token = $accessToken;

                    }

                    // return $this->successResponse($user, __('Profile Updated Successfully'));
                    return (new UserResource($user))->additional([
                        'status' => true,
                        // 'token' => $accessToken,
                        'message' => 'Profile Updated Successfully'
                    ]);

                } else {

                    return $this->errorResponse(__('User not found'));

                }

            } else {

                return $this->errorResponse(__('User not found'));

            }

        }

        return $this->errorResponse($validator->messages(), true);

    }



    /**

     *  changes the password of the user

     */

    public function change_password(Request $request)
    {

        $validator = Validator::make(request()->all(), [
            'current_password' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ]);

        if (!$validator->fails()) {

            $user = request()->user();



            if (!(Hash::check($request->get('current_password'), $user->password))) {

                // The passwords dont match


                return response()->json([

                    'status' => false,

                    'message' =>  __('Your current password does not matches with the password you provided. Please try again.')

                ]);

            }

            $user->password = bcrypt($request->password);

            $user->update();

            return response()->json([

                'status' => true,

                'message' => __('Password changed successfully')

            ]);
        }
        
        return $this->errorResponse($validator->messages(), true);

    }

    public function change_password_otp(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'user_id' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ]);

        if (!$validator->fails()) {
            if (User::where('id', $request->user_id)->first()){
                
                User::where('id', $request->user_id)->update([
                    'password' => bcrypt($request->password)
                ]);

                return response()->json([
                    'status' => true,
                    'message' => __('Password changed successfully')
                ]);
            } else
            {
                return response()->json([
                    'status' => false,
                    'message' => __('User not found')
                ]);
            }
        }
        return $this->errorResponse($validator->messages(), true);
    }


    public function change_password_(Request $request) {

        $validator = Validator::make(request()->all(), [

            // 'user_id' => 'required',

            'password' => 'required',

        ]);

        if (!$validator->fails()) {

            $user = User::where('id', request()->user()->id)->first();

            if (isset($user)) {

                if (in_array($user->hasRole(), [4])) {

                    $update_data['password'] = Hash::make($request->password);

                    User::whereId(request()->user()->id)->update($update_data);

                    return $this->successResponse($user, __('Password Changed Successfully'));

                } else {

                    return $this->errorResponse(__('User not found'));

                }

            } else {

                return $this->errorResponse(__('User not found'));

            }

        }

        return $this->errorResponse($validator->messages(), true);

    }



    public function children_list(Request $request) {

        $validator = Validator::make(request()->all(), [

            // 'user_id' => 'required',

        ]);

        if (!$validator->fails()) {

            $childrens = User::where('parent_id', request()->user()->id)->where('status', 1)->get();



            if (count($childrens) > 0) {

                $i = 0;

                foreach ($childrens as $value) {

                    $orders = Orders::whereCustomerId($value->id)->whereStatus(1)->first();

                    $childrens[$i]->delete = 1;

                    if (!empty($orders)) {

                        $childrens[$i]->delete = 0;

                    }

                    $i++;

                }

                return $this->successResponse($childrens, __('List of Childrens'));

            } else {

                return $this->errorResponse(__('Children not found'));

            }

        }

        return $this->errorResponse($validator->messages(), true);

    }



    public function delete_children(Request $request) {

        $validator = Validator::make(request()->all(), [

            // 'user_id' => 'required',

        ]);

        if (!$validator->fails()) {

            $children = User::where('id', request()->user()->id)->where('parent_id', '!=', 0)->first();

            if (isset($children)) {

                User::where('id', request()->user()->id)->where('parent_id', '!=', 0)->delete();

                return $this->successResponse([], __('Children Deleted Successfully'));

            } else {

                return $this->errorResponse(__('Children not found'));

            }

        }

        return $this->errorResponse($validator->messages(), true);

    }



    public function logout() {

        $user_id = request()->user()->id;
        $update_data = array('name' => '', 'token' => '');
        Devices::where('user_id', $user_id)->update($update_data);
        $user = User::find($user_id);
        $user->revokeAllTokens();

        //remove the device details
        // $user = request()->user();
        // $user->currentAccessToken()->delete();

        //revoke current token

        return response()->json([

            'status' =>  true,

            'message' => __('Logged out successfully')

        ]);

    }



    public function notifications(Request $request) {

        $validator = Validator::make(request()->all(), [

            // 'user_id' => 'required',

        ]);

        if (!$validator->fails()) {



            $notifications = Notification::whereUserId($request->user()->id)->orderBy('id','desc')->get()->toArray();

            if(empty($notifications))

            {
                
                return $this->successResponse($notifications, __('No notifications found'));    

            }
           
            return $this->successResponse($notifications, __(''));

        } else {

            return $this->errorResponse(__('User not found'));

        }

    }



    public function notifications_status_change(Request $request) {

        $validator = Validator::make(request()->all(), [

            'status' => 'required',

        ]);

        if (!$validator->fails()) {

            $post['notification'] = $request->status;

            User::whereId($request->user()->id)->update($post);            

            return $this->successResponse([],__('notifications status changes successfully'));

        } 

        return $this->errorResponse($validator->messages(), true);

    }



    public function notifications_delete(Request $request)

    {

        $validator = Validator::make(request()->all(), [

            // 'user_id' => 'required',

        ]);

        if (!$validator->fails()) {

            Notification::whereUserId(request()->user()->id)->delete();

            return $this->successResponse([], __('Notifications deleted successfully'));

        } else {

            return $this->errorResponse(__('User not found'));

        }

    }



}

