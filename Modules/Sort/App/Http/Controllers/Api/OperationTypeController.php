<?php

namespace Modules\Sort\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Sort\App\Http\Resources\OperationTypeResource;
use Modules\Sort\App\Models\OperationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OperationTypeController extends Controller
{

    public  function index(Request $request){
        $operation_types=OperationType::Active()->orderBy('sort')->get();
        return responseApi(200, translate('return_data_success'), OperationTypeResource::collection($operation_types));

    }
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'ar' => 'required|array',
            'ar.title' => 'required|string',
            'status' => 'nullable|in:1,0',
            'sort' => 'nullable|string',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());
        DB::beginTransaction();

        try {
            $data = $request->all();

            $operation_type = OperationType::create($data);


            DB::commit();
            return responseApi(200, translate('return_data_success'), new  OperationTypeResource($operation_type));

        } catch (\Exception $e) {
            DB::rollBack();
            return responseApiFalse(500, translate('same_error'));
        }
    }



    public  function update ($id,Request $request){
        $validator = Validator::make($request->all(), [
            'ar' => 'required|array',
            'ar.title' => 'required|string',
            'status' => 'nullable|in:1,0',
            'sort' => 'nullable|string',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        try {
            DB::beginTransaction();

            $operation_type = OperationType::find($id);
            $data = $request->all();

           if(!$operation_type){
                return responseApiFalse(500, translate('operation_type not found'));
            }
            $operation_type->update($data);
            DB::commit();
            return responseApi(200, translate('return_data_success'), new OperationTypeResource($operation_type));

        }catch (\Exception $e){
            DB::rollback();
            return responseApiFalse(500, translate('same_error'));
        }
    }


    public function destroy($id)
    {

        $operation_type = OperationType::find($id);
        if(!$operation_type){
            return responseApiFalse(500, __('site.operation_type not found'));
        }
        $operation_type->delete();
        return responseApi(200);

    }
}
