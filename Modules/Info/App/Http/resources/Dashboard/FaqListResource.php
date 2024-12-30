<?php

namespace Modules\Info\App\Http\resources\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class FaqListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'=>$this->id,
            'title'=>$this->title,
            'sort'=>$this->sort,
            'created_at'=>$this->created_at?->format('d-m-Y h:i A'),
            'updated_at'=>$this->updated_at?->format('d-m-Y h:i A'),
        ];
    }
}
