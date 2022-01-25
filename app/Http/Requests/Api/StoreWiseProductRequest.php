<?php



namespace App\Http\Requests\Api;



use Illuminate\Contracts\Validation\Validator;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Http\JsonResponse;



class StoreWiseProductRequest extends FormRequest

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

            'store_id' => 'required',
            'user_id' => '',
            'text' => ''

        ];

    }

}

