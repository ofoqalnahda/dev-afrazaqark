<?php

namespace Modules\Sort\Services;

use Illuminate\Http\Request;
use Modules\Auth\App\Models\User;
use Modules\Sort\App\Http\Resources\TransactionListResource;
use Modules\Sort\App\Models\Transaction;
use App\Traits\FCMNotificationTrait;
use Modules\Sort\App\Models\TransactionPayment;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class TransactionService
{
    use FCMNotificationTrait;

    /**
     * Adding a New Operation: Phase One - Request for Sorting or Merging
     *
     * @param Request $request
     * @param User $user
     * @return Transaction
     */
    public function stepOne( Request $request , User $user): Transaction
    {
     $trans=Transaction::create([

            'user_id' => $user->id,
            'operation_type_id' => $request->operation_type_id,
            'property_type_id' =>  $request->property_type_id,
            'count_unit' =>  $request->count_unit,
            'is_matching' =>  $request->is_matching,
            'area_id' => $request->area_id,
            'city_id' => $request->city_id,
            'status_id' =>  1,
            'sub_status_id' =>  5,
        ]);

        $trans->invoice_number=$this->CreateInvoiceNumber($trans->id,$user->id);
        $trans->save();
        if ($request->hasFile('instrument_images')) {
            foreach ($request->file('instrument_images') as $image) {
                $trans->addMedia($image)->toMediaCollection('instrument_images');
            }
        }


        uploadFile($request,'license_image',$trans);

        return $trans;
    }

    /**
     * Adding a New Operation: Phase One - Request for Sorting or Merging
     *
     * @param Request $request
     * @param Transaction $transaction
     * @return Transaction
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function stepTwo( Request $request ,Transaction $transaction): Transaction
    {

        $transaction->update([
            'status_id'=>2,
            'sub_status_id'=>9,
            'address'=>$request->address,
            'lat'=>$request->lat,
            'lng'=>$request->lng,
            'route_type_id'=>$request->route_type_id
        ]);

        uploadFile($request,'drawing_building',$transaction);
        uploadFile($request,'image_id',$transaction);
        uploadFile($request,'building_facade_image',$transaction);
        uploadFile($request,'ground_floor_yard_one_image',$transaction);

        uploadFile($request,'ground_floor_yard_two_image',$transaction);
        uploadFile($request,'upper_courtyard_one_image',$transaction);
        uploadFile($request,'upper_courtyard_two_image',$transaction);
        uploadFile($request,'brighten_one_image',$transaction);
        uploadFile($request,'brighten_two_image',$transaction);
        uploadFile($request,'electricity_meter_image',$transaction);

        if ($request->hasFile('other_images')) {
            foreach ($request->file('other_images') as $image) {
                $transaction->addMedia($image)->toMediaCollection('instrument_images');
            }
        }


        return $transaction;
    }

    /**
     * Pay invoice for transaction
     *
     * @param Request $request
     * @param Transaction $transaction
     * @param TransactionPayment $payment
     * @param User $user
     * @return array
     */
    public function PayTransaction( Request $request ,Transaction $transaction ,TransactionPayment $payment,User $user): array
    {
        $data['success']=false;
        $data['message']=trans('error in payment type');

        if ($request->type_pay == 'Transfer'){

            $transaction->update([
                'sub_status_id'=>11,
            ]);

            $payment->update([
                'status'=>'check',
            ]);
            $data['success']=true;
        }elseif ($request->type_pay == 'Online'){

            ///after payment

            $sub_status_id=$payment->type=='App' ? 7:8;
            $transaction->update([
                'status_id'=>1,
                'sub_status_id'=>$sub_status_id,
            ]);
            $payment->update([
                'status'=>'paid',
            ]);

            $data['success']=true;

        }
        return $data;
    }

    /**
     * create invoice_number by transaction id
     *
     * @param int $transaction_id
     * @param int $user_id
     * @return string
     */
    public function CreateInvoiceNumber( int $transaction_id,int $user_id): string
    {

        $datePart = date('d');
        return sprintf('%s%s%04d',  $user_id,$datePart, $transaction_id);
    }


    /**
     * create Payment for transaction
     *
     * @param float $price
     * @param string $type
     * @param Transaction $transaction
     * @return TransactionPayment
     */
    public function CreatePayment(  float $price ,string $type,Transaction $transaction): TransactionPayment
    {
        return TransactionPayment::create([
            'transaction_id'=>$transaction->id,
            'amount'=>$price,
            'type'=>$type,
        ]);
    }



}
