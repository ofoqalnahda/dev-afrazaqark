<?php

namespace Modules\Sort\App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use function Symfony\Component\String\b;

class TransactionPaymentResource extends JsonResource
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
            'type'=>$this->type,
            'type_pay'=>$this->type_pay,
            'status'=>$this->status,
            'date'=>$this->created_at ? $this->created_at->diffForHumans():'',
            'amount'=>(float)$this->amount ,


        ];
    }
}
