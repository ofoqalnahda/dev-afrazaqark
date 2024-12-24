<?php

namespace Modules\Sort\App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Sort\App\Models\PropertyType;
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

        $payment_id=null;
        $is_waiting_pay=false;
        $amount=0;
        switch ($this->status_id){
            case 4:
                    $description=$this->cancellation_reason?->title;
                break;
            case 2  :
                $payment=$this->payments()->wherein('status',['unpaid','failed'])->first();
                if($payment){
                    $is_waiting_pay=true;
                    $amount=$payment->amount;
                    $payment_id=$payment->id;
                }

                $description=$this->transaction_sub_status?->title;
                break;
            default:
                $description=$this->transaction_sub_status?->title;
                break;
        }
        return [
            'id'=>$this->id,
            'invoice_number'=>$this->invoice_number,
            'date'=>$this->created_at ? $this->created_at->diffForHumans():'',
            'status'=>$this->transaction_status?->title ,
            'description'=>$description ,
            'is_waiting_pay'=>$is_waiting_pay ,
            'payment_id'=>$payment_id ,
            'amount'=>(float)$amount ,
            'address'=>$this->address,
            'lat'=>$this->lat,
            'lng'=>$this->lng,
            'authority_invoice'=>$this->getFirstMediaUrl('authority_invoice')?:'',
            'drawing_building'=>$this->getFirstMediaUrl('drawing_building')?:'',
            'image_id'=>$this->getFirstMediaUrl('image_id')?:'',
            'building_facade_image'=>$this->getFirstMediaUrl('building_facade_image')?:'',
            'ground_floor_yard_one_image'=>$this->getFirstMediaUrl('ground_floor_yard_one_image')?:'',
            'ground_floor_yard_two_image'=>$this->getFirstMediaUrl('ground_floor_yard_two_image')?:'',
            'upper_courtyard_one_image'=>$this->getFirstMediaUrl('upper_courtyard_one_image')?:'',
            'upper_courtyard_two_image'=>$this->getFirstMediaUrl('upper_courtyard_two_image')?:'',
            'brighten_one_image'=>$this->getFirstMediaUrl('brighten_one_image')?:'',
            'brighten_two_image'=>$this->getFirstMediaUrl('brighten_two_image')?:'',
            'electricity_meter_image'=>$this->getFirstMediaUrl('electricity_meter_image')?:'',
            'other_images'=> $this->getMedia('other_images')->map(function ($media) {
                                return $media->getUrl();
                            })->toArray(),
            'sorting_report'=>$this->getFirstMediaUrl('sorting_report')?:'',
            'property_type'=> new PropertyTypeResource($this->property_type),
            'operation_type'=>new OperationTypeResource($this->operation_type),
            'payments'=> TransactionPaymentResource::collection($this->payments)

        ];
    }


}
