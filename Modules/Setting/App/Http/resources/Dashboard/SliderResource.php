<?php

namespace Modules\Setting\App\Http\resources\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
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
            'image_url'=>$this->image_url,
            'type'=>$this->type,
            'url'=>$this->url,
            'status'=>(boolean)$this->status,
            'is_active'=>(boolean)$this->is_active,
            'start_at'=>$this->start_at,
            'end_at'=>$this->end_at,
            'created_at'=>$this->created_at?->format('d-m-Y h:i A'),
            'updated_at'=>$this->updated_at?->format('d-m-Y h:i A'),
        ];
    }
}
