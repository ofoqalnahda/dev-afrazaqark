<?php

namespace Modules\Info\App\Http\resources\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class FaqResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'=>$this->id,
            'sort'=>$this->sort,
            'title'=>$this->title,
            'description'=>$this->description,
            'translations'=>$this->translations,
            'created_at'=>$this->created_at?->format('d-m-Y h:i A'),
            'updated_at'=>$this->updated_at?->format('d-m-Y h:i A'),
        ];
    }
}
