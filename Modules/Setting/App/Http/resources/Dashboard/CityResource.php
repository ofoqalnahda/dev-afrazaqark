<?php

namespace Modules\Setting\App\Http\resources\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
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
            'status'=>(boolean)$this->status,
            'title'=>$this->title,
            'area_id'=>$this->area?->id,
            'area'=>$this->area?->title,
            'translations'=>$this->translations,
            'created_at'=>$this->created_at?->format('d-m-Y h:i A'),
            'updated_at'=>$this->updated_at?->format('d-m-Y h:i A'),
        ];
    }
}
