<?php

namespace Modules\Info\App\Http\resources\Dashboard;

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
            'translations'=>$this->translations
        ];
    }
}
