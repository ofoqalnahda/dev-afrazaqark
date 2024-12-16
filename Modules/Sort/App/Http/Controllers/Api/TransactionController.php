<?php

namespace Modules\Sort\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Response;
use Modules\Sort\App\Http\Resources\BankResource;
use Modules\Sort\App\Http\Resources\TransactionCountStatusResource;
use Modules\Sort\App\Http\Resources\TransactionListResource;
use Modules\Sort\App\Http\Resources\TransactionResource;
use Modules\Sort\App\Models\Bank;
use Modules\Sort\App\Models\Transaction;
use Modules\Sort\App\Models\TransactionPayment;
use Modules\Sort\App\Models\TransactionStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Sort\Services\TransactionService;

class TransactionController extends Controller
{
    protected TransactionService $transactionSer;
    public function __construct( TransactionService $transactionSer)
    {
        $this->transactionSer = $transactionSer;
        $this->middleware('auth.gard:api');
    }

    public  function index(Request $request): Application|Response|ResponseFactory{
        $transactions=Transaction::where('user_id',auth('api')->id())->filterAds($request->query())->get();
        return responseApi(200, translate('return_data_success'), TransactionListResource::collection($transactions));

    }
    public  function show($transaction_id): Application|Response|ResponseFactory{
        $transaction=Transaction::where('user_id',auth('api')->id())->where('id',$transaction_id)->first();
        if(!$transaction){
            return responseApiFalse(405, translate('This transaction is not found'));
        }

        return responseApi(200, translate('return_data_success'), new TransactionResource($transaction));

    }
    /**
     * Get the count of records for each unique status_id in the table.
     *
     * This function retrieves the count of rows grouped by 'status_id' from the 'YourModel' table.
     * It uses the 'groupBy' method to group the records by 'status_id' and then counts how many records exist
     * for each unique status_id. The result is returned as a collection of objects where each object contains
     * the 'status_id' and the 'total' count of records with that 'status_id'.
     *
     * Example of output:
     * Status ID: 1 - Count: 10
     * Status ID: 2 - Count: 5
     *
     * @return Application|ResponseFactory|Response A collection of objects containing the 'status_id' and 'total' count for each status_id.
     */
    public  function GetCount(Request $request): Application|Response|ResponseFactory
    {
        $counts = TransactionStatus::whereNull('parent_id')
            ->withCount(['transactions' => function($query) {
                $query->where('user_id', auth('api')->id());
            }])->get();
        return responseApi(200, translate('return_data_success'), TransactionCountStatusResource::collection($counts));

    }

    Public function StepOne(Request $request): Application|Response|ResponseFactory
    {

        $validator = Validator::make($request->all(), [
            'operation_type_id' => 'required|exists:operation_types,id',
            'property_type_id' => 'required|exists:property_types,id',
            'count_unit' => 'required|numeric|min:2',
            'instrument_images' => [
                'required',
                'array',
                'min:' . $request->count_unit,
                'max:' . $request->count_unit,
            ],
            'instrument_images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'license_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_matching' => 'required|in:1,0',
            'area_id' => 'required|exists:areas,id',
            'city_id' => 'required|exists:cities,id'
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        DB::beginTransaction();
        try {

            $this->transactionSer->stepOne($request,auth('api')->user());
            DB::commit();
            return responseApi(200, translate('Your request has been successfully created. We are processing your request. Thank you!'));
        } catch (\Exception $e) {
            DB::rollBack();
            return responseApiFalse(500, translate('same_error'));
        }
    }

    Public function StepTwo(Request $request): Application|Response|ResponseFactory
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|exists:transactions,id',
            'drawing_building' => 'required|mimes:dwg,dxf,pdf|max:10240',
            'image_id' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'route_type_id' => 'required|exists:route_types,id',
            'lat' => [
                'required',
                'regex:/^(\+|-)?(?:90(?:\.0+)?|(?:[0-9]|[1-8][0-9])(?:\.[0-9]+)?)$/'
            ],
            'lng' => [
                'required',
                'regex:/^(\+|-)?(?:180(?:\.0+)?|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:\.[0-9]+)?)$/'
            ],
            'address' => 'required|string|max:255',

            'building_facade_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ground_floor_yard_one_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ground_floor_yard_two_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'upper_courtyard_one_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'upper_courtyard_two_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'brighten_one_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'brighten_two_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'electricity_meter_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            'other_images' => 'required|array',
            'other_images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());


        $transaction=Transaction::where('id',$request->transaction_id)->first();
        if(!$transaction){
            return responseApiFalse(405, translate('This transaction is not found'));
        }


        DB::beginTransaction();
        try {
            $this->transactionSer->stepTwo($request,$transaction);
            DB::commit();
            return responseApi(200, translate('has been successfully created'));
        } catch (\Exception $e) {
            DB::rollBack();
            return responseApiFalse(500, translate('same_error'));
        }
    }


    Public function PaymentTransaction(Request $request): Application|Response|ResponseFactory{
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|exists:transactions,id',
            'transaction_payment_id' => 'required|exists:transaction_payments,id',
            'type_pay' => 'required|in:Online,Transfer',
            'image_invoice' => 'required_if:type_pay,Transfer|mimes:peg,png,jpg,gif,svg,pdf|max:10240',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());


        $transaction=Transaction::where('id',$request->transaction_id)->first();
        $payment=TransactionPayment::where('transaction_id',$request->transaction_id)
                    ->where('id',$request->transaction_payment_id)->first();
        if(!$transaction || !$payment){
            return responseApiFalse(405, translate('This transaction is not found'));
        }


        if( in_array($payment->status,['paid','check'])){
            return responseApiFalse(405, translate('This transaction has already been paid'));

        }

        DB::beginTransaction();
        try {
            $data =$this->transactionSer->PayTransaction($request,$transaction,$payment,auth('api')->user());
            if($data['success']){
                DB::commit();
                return responseApi(200, translate('has been successfully paid'));
            }else{
                DB::rollBack();
                return responseApiFalse(405,$data['message']);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return responseApiFalse(500, translate('same_error'));
        }
    }


    public function GetDataForPayment (Request $request): Application|Response|ResponseFactory
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|exists:transactions,id',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());



        $transaction=Transaction::where('id',$request->transaction_id)->first();
        if(!$transaction){
            return responseApiFalse(405, translate('This transaction is not found'));
        }
        $payment=$transaction->payments()->wherein('status',['unpaid','failed'])->first();
        if(!$payment){
            return responseApiFalse(405, translate('There is no invoice for this transaction. Please contact the administration. Thank you.'));
        }
        $data['amount']=$payment->amount ;
        $data['authority_invoice']=$transaction->getFirstMediaUrl('authority_invoice')?:'';
        \Settings::set('message_payment_'.app()->getLocale() , 'هذا التطبيق موثق من وزارة التجارة والاستثمار ووسائل الدفع به امنه ومعتمدة يرجي التاكد من التفاصيل المتعلقة بالدفع علي تطبيق افرز عقارك . يرجي التاكد من الرقم المحول الي والبنك والتاكد من رفع ايصال التحويل ');
        $data['message_payment']=\Settings::get('message_payment_'.app()->getLocale() , 'هذا التطبيق موثق من وزارة التجارة والاستثمار ووسائل الدفع به امنه ومعتمدة يرجي التاكد من التفاصيل المتعلقة بالدفع علي تطبيق افرز عقارك . يرجي التاكد من الرقم المحول الي والبنك والتاكد من رفع ايصال التحويل ');
        $data['banks']=BankResource::collection(Bank::Active()->orderBy('sort')->get());
        return responseApi(200, translate('return_data_success'), $data);

    }

}
