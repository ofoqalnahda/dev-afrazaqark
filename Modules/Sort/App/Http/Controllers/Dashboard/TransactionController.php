<?php

namespace Modules\Sort\App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Modules\Admin\App\Models\Admin;
use Modules\Setting\App\Http\resources\Dashboard\AreaResource;
use Modules\Sort\App\Http\Resources\Dashboard\TransactionListResource;
use Modules\Sort\App\Http\Resources\Dashboard\TransactionResource;
use Modules\Sort\App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Sort\Services\TransactionService;

class TransactionController extends Controller
{
    protected $count_paginate = 10;

    protected TransactionService $transactionSer;
    public function __construct( TransactionService $transactionSer)
    {
        $this->transactionSer = $transactionSer;
        Config::set( 'jwt.user', 'App\Models\Provider' );
        Config::set( 'auth.providers.users.model', Admin::class );
        $this->middleware('auth.gard:admin');
    }

    public  function index(Request $request): Application|Response|ResponseFactory{
        $count_paginate=$request->count_paginate?:$this->count_paginate;

        $transactions=Transaction::filterAds($request->query())->latest()->paginate($count_paginate);
        $data=[
            'transactions'=>TransactionListResource::collection($transactions),
            'count'=>$transactions->total(),
            'current_page'=>$transactions->currentPage(),
            'last_page'=>$transactions->lastPage(),
        ];
        return responseApi(200, translate('return_data_success'),$data);

    }

    public  function show(int $transaction_id): Application|Response|ResponseFactory{
        $transaction=Transaction::whereId($transaction_id)->first();
        if(!$transaction){
            return responseApiFalse(405, translate('This transaction is not found'));
        }
        return responseApi(200, translate('return_data_success'), new TransactionResource($transaction));

    }
    Public function AcceptTransaction(Request $request): Application|Response|ResponseFactory{

        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|exists:transactions,id',
            'price' => 'required|numeric',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());


        $transaction=Transaction::where('id',$request->transaction_id)->first();
        if(!$transaction){
            return responseApiFalse(405, translate('This transaction is not found'));
        }


        DB::beginTransaction();
        try {

            $transaction->update([
                'sub_status_id'=>6,
            ]);
            $transaction->updated_by = auth('admin')->id();
            $transaction->save();
            $this->transactionSer->CreatePayment($request->price,'App',$transaction);

            DB::commit();
            return responseApi(200, translate('has been successfully created'));
        } catch (\Exception $e) {
            DB::rollBack();
            return responseApiFalse(500, translate('same_error'));
        }
    }



    Public function AuthorityPaymentReceipt(Request $request): Application|Response|ResponseFactory{

        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|exists:transactions,id',
            'amount' => 'required|numeric',
            'authority_payment_receipt' => 'required|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());


        $transaction=Transaction::where('id',$request->transaction_id)->first();
        if(!$transaction){
            return responseApiFalse(405, translate('This transaction is not found'));
        }

        if($transaction->status_id == 2 && $transaction->sub_status_id == 10){
            return responseApiFalse(405, translate('The invoice has already been added'));

        }


        DB::beginTransaction();
        try {

            $transaction->update([
                'status_id'=>2,
                'sub_status_id'=>10,
            ]);
            $transaction->updated_by = auth('admin')->id();
            $transaction->save();
            $this->transactionSer->CreatePayment($request->amount,'AuthorityInvoice',$transaction);
            if($request->file('authority_payment_receipt')){
               $transaction->addMediaFromRequest('authority_payment_receipt')->toMediaCollection('authority_invoice');
            }
            DB::commit();
            return responseApi(200, translate('has been successfully created'));
        } catch (\Exception $e) {
            DB::rollBack();
            return responseApiFalse(500, translate('same_error'));
        }
    }
    Public function PaymentConfirmation(Request $request): Application|Response|ResponseFactory{

        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|exists:transactions,id'
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());


        $transaction=Transaction::where('id',$request->transaction_id)->first();
        if(!$transaction){
            return responseApiFalse(405, translate('This transaction is not found'));
        }
        $payment=$transaction->payments()->where('status','check')->first();
        if(!$payment){
            return responseApiFalse(405, translate('There is no invoice for this transaction'));
        }


        DB::beginTransaction();
        try {
            $payment->update([
                'status'=>'paid'
            ]);


            if($payment->type == "App"){
                $transaction->update([
                    'status_id'=>7,
                    'sub_status_id'=>1
                ]);
            }else{
                $transaction->update([
                    'status_id'=>8,
                    'sub_status_id'=>1
                ]);
            }
            $transaction->updated_by = auth('admin')->id();
            $transaction->save();
            DB::commit();
            return responseApi(200, translate('Payment has been successfully confirmed'));
        } catch (\Exception $e) {
            DB::rollBack();
            return responseApiFalse(500, translate('same_error'));
        }
    }

}
