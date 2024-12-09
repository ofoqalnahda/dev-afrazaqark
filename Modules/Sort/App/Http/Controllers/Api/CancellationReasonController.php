<?php

namespace Modules\Sort\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Sort\App\Http\Resources\CancellationReasonResource;
use Modules\Sort\App\Models\CancellationReason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CancellationReasonController extends Controller
{

    public  function index(Request $request){
        $reasons=CancellationReason::Active()->orderBy('sort')->get();
        return responseApi(200, translate('return_data_success'), CancellationReasonResource::collection($reasons));

    }
}
