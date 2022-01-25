<?php



namespace App\Services;



use App\Models\User;



class UpdateUserService

{

    /**

     *  updates the user

     */

    public function run(User $user, $first_name = null, $last_name = null, $email = null, $phone = null, $address = null, $fullName = null, $removeImage = 0)

    {

        $user->update([

            // 'username' => $username,

            'full_name' => $first_name.' '.$last_name,

            'first_name' => $first_name,

            'last_name' => $last_name,

            'address' => $address,

            'phone' => $phone,

            'email' => $email,

        ]);

        //update the user details



        if ($removeImage) {

            $user->deleteProfilePicture();

            //clearing the old profile

        } elseif (request()->hasFile('profile_picture')) {

            //upload profile picture only if it exists

            $user->deleteProfilePicture();

            // $user->addMediaFromRequest('profile_picture')->toMediaCollection('user/profile-picture')->optimize();

            //upload new image

        }



        return true;

    }

}

