<?php

namespace Modules\Admin\App\Http\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'phone'=>$this->phone,
            'email'=>$this->email,
            'status'=>(boolean)$this->is_active,
            'image'=>$this->getFirstMedia('images') != null ? $this->getFirstMedia('images')->getUrl() : null,
            'created_at'=>$this->created_at?->format('d-m-Y h:i A'),
            'updated_at'=>$this->updated_at?->format('d-m-Y h:i A'),
        ];
    }
}
