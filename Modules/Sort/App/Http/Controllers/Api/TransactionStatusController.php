<?php

namespace Modules\Sort\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Sort\App\Http\Resources\TransactionStatusResource;
use Modules\Sort\App\Models\TransactionStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionStatusController extends Controller
{

    public  function index(Request $request){
        $status=TransactionStatus::whereNull('parent_id')->get();
        return responseApi(200, translate('return_data_success'), TransactionStatusResource::collection($status));

    }
    public  function indexSub(Request $request){

        $status=TransactionStatus::whereNotNull('parent_id');
        if ($request->has('parent_id')) {
            $status=$status->where('parent_id',$request->get('parent_id'));

        }
        return responseApi(200, translate('return_data_success'), TransactionStatusResource::collection($status->get()));

    }
}
