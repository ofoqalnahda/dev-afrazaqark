<?php

namespace Modules\Sort\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Sort\App\Http\Resources\BankResource;
use Modules\Sort\App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BankController extends Controller
{

    public  function index(Request $request){
        $banks=Bank::Active()->orderBy('sort')->get();
        return responseApi(200, translate('return_data_success'), BankResource::collection($banks));

    }
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'name_user' => 'required|string',
            'number_account' => 'required|string',
            'status' => 'nullable|in:1,0',
            'sort' => 'nullable|string',
            'icon' => 'required|image',

        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());
        DB::beginTransaction();

        try {
            $data = $request->except(['icon']);

            $bank = Bank::create($data);

            $bank->addMediaFromRequest('icon')->toMediaCollection('icon');

            DB::commit();
            return responseApi(200, translate('return_data_success'), new  BankResource($bank));

        } catch (\Exception $e) {
            DB::rollBack();
            return responseApiFalse(500, translate('same_error'));
        }
    }



    public  function update ($id,Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'name_user' => 'required|string',
            'number_account' => 'required|string',
            'status' => 'nullable|in:1,0',
            'sort' => 'nullable|string',
            'icon' => 'nullable|image',

        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        try {
            DB::beginTransaction();

            $bank = Bank::find($id);
            $data = $request->all();

           if(!$bank){
                return responseApiFalse(500, translate('bank not found'));
            }
            $bank->update($data);

            if($request->hasFile('icon')){
                $bank->clearMediaCollection('icon');
                $bank->addMediaFromRequest('icon')->toMediaCollection('icon');
            }
            DB::commit();
            return responseApi(200, translate('return_data_success'), new BankResource($bank));

        }catch (\Exception $e){
            DB::rollback();
            return responseApiFalse(500, translate('same_error'));
        }
    }


    public function destroy($id)
    {

        $bank = Bank::find($id);
        if(!$bank){
            return responseApiFalse(500, __('site.bank not found'));
        }
        $bank->clearMediaCollection('icon');


        $bank->delete();
        return responseApi(200);

    }
}
