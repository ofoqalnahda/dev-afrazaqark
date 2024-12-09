<?php

namespace Modules\Sort\App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BankResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray(Request $request): array|\JsonSerializable|Arrayable
    {
        return [
            'id'=>$this->id,
            'title'=>$this->title,
            'name_user'=>$this->name_user,
            'number_account'=>$this->number_account,
            'icon'=>$this->first_icon,
        ];
    }
}
