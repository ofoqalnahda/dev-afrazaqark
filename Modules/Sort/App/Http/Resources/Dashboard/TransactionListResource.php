<?php

namespace Modules\Sort\App\Http\Resources\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;
use function Symfony\Component\String\b;

class TransactionListResource extends JsonResource
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
            'image'=>$this->first_img,
            'invoice_number'=>$this->invoice_number,
            'date'=>$this->created_at ? $this->created_at->diffForHumans():'',
            'status'=>$this->transaction_status?->title ,
            'description'=>$description ,
            'is_waiting_pay'=>$is_waiting_pay ,
            'payment_id'=>$payment_id ,
            'amount'=>(float)$amount ,

        ];
    }
}
