<?php

namespace Modules\Customer\App\resources\Dashboard;

use DateTime;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'=>$this->id,
            'image'=>$this->getFirstMedia('images') != null ? $this->getFirstMedia('images')->getUrl() : null,
            'name'=>$this->name,
            'phone'=>$this->phone,
            'email'=>$this->email,
            'status'=>(boolean)$this->status,
            'is_activation'=>$this->activation_at != null,
            'activation_at'=>isset($this->activation_at)
        ? (new DateTime($this->activation_at))->format('d-m-Y h:i A')
        : null,
            'created_at'=>$this->created_at?->format('d-m-Y h:i A'),
            'updated_at'=>$this->updated_at?->format('d-m-Y h:i A'),
            'deleted_at'=>$this->deleted_at?->format('d-m-Y h:i A'),

        ];
    }
}
