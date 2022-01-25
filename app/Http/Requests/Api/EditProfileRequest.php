<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class EditProfileRequest extends FormRequest
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
            // 'username' => 'nullable|unique:users,username,' . request()->user()->id,
            'email' => 'nullable|unique:users,email,' . request()->user()->id,
            // 'full_name' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|unique:users,phone,' . request()->user()->id,
            // 'address' => 'nullable',
            'profile_picture' => 'nullable|image|max:5000',
            'remove_image' => 'nullable|in:0,1',
        ];
    }
}
