<?php

namespace Modules\Notification\App\Http\resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Sort\App\Http\Resources\TransactionResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'=>$this->id,
            'is_read'=>(boolean)$this->read,
            'type'=>$this->type,
            'title'=>$this->title,
            'body'=>$this->body,
            'image'=>$this->image,
            'type_data'=>new TransactionResource($this->type_data),
            'created_at'=>$this->created_at ? $this->created_at->diffForHumans():'',
        ];
    }
}
