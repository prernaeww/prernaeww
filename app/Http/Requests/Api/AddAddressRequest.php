<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class AddAddressRequest extends FormRequest
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
            'lat' => 'required',
            'log' => 'required',
            'zipcode' => 'required',
            'city' => 'required',
            'state' => 'required',
            'address_type' => 'required|in:1,2,3,4',
            'complete_address' => 'required',
        ];

    }
}
