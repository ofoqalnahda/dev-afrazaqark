<?php

namespace Modules\Home\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'=>$this->id,
            'type'=>$this->type,
            'url'=>$this->url,
            'image'=>$this->image_url,
        ];
    }
}
