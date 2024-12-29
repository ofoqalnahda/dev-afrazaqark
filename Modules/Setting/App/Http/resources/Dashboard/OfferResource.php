<?php

namespace Modules\Setting\App\Http\resources\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
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
            'name'=>$this->name,
            'status'=>(boolean)$this->status,
            'sort'=>$this->sort,
            'created_at'=>$this->created_at?->format('d-m-Y h:i A'),
            'updated_at'=>$this->updated_at?->format('d-m-Y h:i A'),
        ];
    }
}
