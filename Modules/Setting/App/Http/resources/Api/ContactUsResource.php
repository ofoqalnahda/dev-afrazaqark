<?php

namespace Modules\Setting\App\Http\resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactUsResource extends JsonResource
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
            'id'=>$this->id,
            'message'=>$this->message,
            'is_show'=>$this->is_show,
            'email'=>$this->email,
            'phone'=>$this->phone,
            'name'=>$this->name,


        ];
    }
}
