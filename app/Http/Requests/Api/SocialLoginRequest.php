<?php



namespace App\Http\Requests\Api;



use Illuminate\Foundation\Http\FormRequest;



class SocialLoginRequest extends FormRequest

{

    /**

     * Determine if the user is authorized to make this request.

     *

     * @return bool

     */

    public function authorize()

    {

        return true;

    }



    /**

     * Get the validation rules that apply to the request.

     *

     * @return array

     */

    public function rules()

    {

        return [

            // 'profile_picture'    => 'nullable|image',

            'email' => 'required|email|unique:users,email',

            'first_name' => 'required',

            'last_name' => 'required',

            'phone' => 'required|unique:users,phone',

            'user_type' => 'required|in:0,1',

            'device_type' => 'required|in:ios,android',

            'device_token' => 'required',

            'social_type'  => 'required|string|in:google,facebook,apple',

            'social_id'    => 'required|string',

        ];

    }

}

