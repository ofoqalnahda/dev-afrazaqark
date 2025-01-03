<?php

namespace Modules\Setting\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Setting\App\Http\resources\Api\AreaResource;
use Modules\Setting\App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{
    protected $count_paginate = 10;

    public  function index(Request $request){
        $areas=Area::Active()->get();
        return responseApi(200, translate('return_data_success'), AreaResource::collection($areas));

    }
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'ar' => 'required|array',
            'ar.title' => 'required|string',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());
        DB::beginTransaction();

        try {
            $data = $request->all();

            $area = Area::create($data);


            DB::commit();
            return responseApi(200, translate('return_data_success'), new  AreaResource($area));

        } catch (\Exception $e) {
            DB::rollBack();
           return responseApiFalse(500, __('site.same_error'));
        }
    }



    public  function update ($id,Request $request){
        $validator = Validator::make($request->all(), [
            'ar' => 'required|array',
            'ar.title' => 'required|string',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        try {
            DB::beginTransaction();

            $area = Area::find($id);
            $data = $request->all();

           if(!$area){
                return responseApiFalse(500, __('site.area not found'));
            }

            $area->update($data);


            DB::commit();
            return responseApi(200, translate('return_data_success'), new AreaResource($area));

        }catch (\Exception $e){
            DB::rollback();
            return responseApiFalse(500, __('site.same_error'));
        }
    }


    public function destroy($id)
    {

        $area = Area::find($id);
        if(!$area){
            return responseApiFalse(500, __('site.area not found'));
        }
        $area->clearMediaCollection('images');


        $area->delete();
        return responseApi(200);

    }
}
