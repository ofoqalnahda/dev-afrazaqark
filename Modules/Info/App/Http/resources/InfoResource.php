<?php

namespace Modules\Info\App\Http\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'=>$this->id,
            'slug'=>$this->slug,
            'title'=>$this->title,
            'description'=>$this->description
        ];
    }
}
