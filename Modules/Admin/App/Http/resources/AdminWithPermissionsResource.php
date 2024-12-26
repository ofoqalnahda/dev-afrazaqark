<?php

namespace Modules\Admin\App\Http\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminWithPermissionsResource extends JsonResource
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
            'created_at'=>$this->created_at ? $this->created_at->diffForHumans():'',
            'updated_at'=>$this->updated_at ? $this->updated_at->diffForHumans():'',
            'permissions'=>PermissionsResource::collection($this-> getAllPermissions()),
        ];
    }
}
