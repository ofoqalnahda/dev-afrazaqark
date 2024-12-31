<?php

namespace Modules\Sort\App\Http\Resources\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Customer\App\resources\CustomerResource;
use Modules\Setting\App\Http\resources\Api\AreaResource;
use Modules\Setting\App\Http\resources\Api\CityResource;
use Modules\Sort\App\Http\Resources\OperationTypeResource;
use Modules\Sort\App\Http\Resources\PropertyTypeResource;
use Modules\Sort\App\Http\Resources\RouteTypeResource;
use Modules\Sort\App\Http\Resources\TransactionPaymentResource;
use Modules\Sort\App\Http\Resources\TransactionStatusResource;
use Modules\Sort\App\Models\TransactionStatus;
use function Symfony\Component\String\b;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $amount=$this->payments()->whereNotin('status',['unpaid','failed'])->sum('amount');
        return [
            'id'=>$this->id,
            'image'=>$this->first_img,
            'invoice_number'=>$this->invoice_number,
            'status'=>new TransactionStatusResource($this->transaction_status) ,
            'transaction_sub_status'=>new TransactionStatusResource($this->transaction_sub_status) ,
            'amount'=>(float)$amount ,
            'count_unit'=>$this->count_unit,
            'is_matching'=>$this->is_matching,
            'user_id'=>$this->user?->id,
            'user_name'=>$this->user?->name,
            'city'=>new CityResource($this->city),
            'area'=>new AreaResource($this->area),
            'address'=>$this->address,
            'lat'=>$this->lat,
            'lng'=>$this->lng,
            'property_type'=>new PropertyTypeResource($this->property_type),
            'operation_type'=>new OperationTypeResource($this->operation_type),
            'route_type'=>new RouteTypeResource($this->route_type),
            'cancelBy_type'=>$this->cancel_by,
            'cancelBy'=>$this->cancelBy ? [
                'id'=>$this->cancelBy?->id,
                'name'=>$this->cancelBy?->name
        ] : null,

            'updatedBy'=>$this->updatedBy ? [
                'id'=>$this->updatedBy?->id,
                'name'=>$this->updatedBy?->name
            ] : null,
            'cancellation_reason'=>$this->cancellation_reason?->title,
            'payments'=>TransactionPaymentResource::collection($this->payments),
            'created_at'=>$this->created_at?->format('d-m-Y h:i A'),
            'updated_at'=>$this->updated_at?->format('d-m-Y h:i A'),
            'deleted_at'=>$this->deleted_at?->format('d-m-Y h:i A'),

        ];
    }
}
