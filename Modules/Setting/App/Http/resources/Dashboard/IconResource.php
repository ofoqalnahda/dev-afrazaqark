<?php

namespace Modules\Setting\App\Http\resources\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class IconResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [//status	title	link
            'id'=>$this->id,
            'image_url'=>$this->first_img,
            'title'=>$this->title,
            'status'=>(boolean)$this->status,
            'link'=>$this->link,
            'created_at'=>$this->created_at?->format('d-m-Y h:i A'),
            'updated_at'=>$this->updated_at?->format('d-m-Y h:i A'),
        ];
    }
}
