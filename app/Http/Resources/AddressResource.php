<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'user_id' => $this->user_id,
            'complete_address' => $this->complete_address,
            'address_type' => $this->address_type,
            'city' => $this->city,
            'state' => $this->state,
            'zipcode' => $this->zipcode,
            'lat' => $this->lat,
            'log' => $this->log,            
        ];
    }
}
