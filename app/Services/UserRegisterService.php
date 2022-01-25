<?php
namespace App\Services;
use App\Models\User;
class  UserRegisterService
{
    /**
     *  creates a user
     */

    public function run($first_name = null, $last_name = null, $email = null, $password, $phone = null, $address = null, $fullName = null, $deviceType = null, $deviceToken = null, $socialId = null, $socialType = null, $user_type = '0', $business_name = null, $phone_verified = 1)
    {    
        $user = User::create([            
            // 'username' => $username,            
            'full_name' => $first_name.' '.$last_name,            
            'first_name' => $first_name,            
            'last_name' => $last_name,            
            'address' => $address,            
            'phone' => $phone,            
            'email' => $email,            
            'password' => bcrypt($password),            
            'device_type' => $deviceType,            
            'device_token' => $deviceToken,            
            'social_id' => $socialId,
            'social_type' => $socialType,            
            'status' => isset($socialType) ? '1' : '0',
            'user_type' => $user_type,
            //'phone_verified' => isset($phone_verified) ? $phone_verified : '1',
        ]);                
        return $user;
    }
}

