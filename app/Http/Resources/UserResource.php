<?php



namespace App\Http\Resources;



use Illuminate\Http\Resources\Json\JsonResource;



class UserResource extends JsonResource

{

    /**

     * Transform the resource into an array.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable

     */

    public function toArray($request)

    {

        return [

            'id' => $this->id,

            'full_name' => $this->first_name.' '.$this->last_name,

            // 'username' => $this->username,

            'first_name' => $this->first_name,

            'last_name' => $this->last_name,

            'email' => $this->email,

            'address' => isset($this->address) ? $this->address : '',

            'phone' => $this->phone,

            'profile_picture' => $this->profile_picture,

            'social_id' => isset($this->social_id) ? $this->social_id : '',

            'social_type' => isset($this->social_type) ? $this->social_type : '',

            'notification' => $this->notification,

            'user_type' => (int)$this->user_type,

            'business_name' => isset($this->business_name) ? $this->business_name : '',

            'dob' => isset($this->dob) ? $this->dob : '',
            'phone_verified' => (int)$this->phone_verified,

            'token' => isset($this->token) ? $this->token : '',
            'cart_total' => isset($this->cart_total) ? $this->cart_total : 0,
            
        ];

    }

}

