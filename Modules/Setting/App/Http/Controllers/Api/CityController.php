<?php

namespace Modules\Setting\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Setting\App\Http\resources\Api\CityResource;
use Modules\Setting\App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    protected $count_paginate = 10;

    public  function index(Request $request){
        $cities=City::Active();
        if($request->has('area_id')){
            $cities=$cities->where('area_id',$request->area_id);
        }
        $cities= $cities->get();
        return responseApi(200, translate('return_data_success'), CityResource::collection($cities));

    }
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'area_id' => 'required|exists:areas,id',
            'ar' => 'required|array',
            'ar.title' => 'required|string',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());
        DB::beginTransaction();

        try {
            $data = $request->all();

            $city = City::create($data);


            DB::commit();
            return responseApi(200, translate('return_data_success'), new  CityResource($city));

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public  function update ($id,Request $request){
        $validator = Validator::make($request->all(), [
            'area_id' => 'required|exists:areas,id',
            'ar' => 'required|array',
            'ar.title' => 'required|string',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        try {
            DB::beginTransaction();

            $city = City::find($id);
            $data = $request->all();

           if(!$city){
                return responseApiFalse(500, __('site.city not found'));
            }

            $city->update($data);


            DB::commit();
            return responseApi(200, translate('return_data_success'), new CityResource($city));

        }catch (\Exception $e){
            DB::rollback();
            return responseApiFalse(500, __('site.same_error'));
        }
    }


    public function destroy($id)
    {

        $city = City::find($id);
        if(!$city){
            return responseApiFalse(500, __('site.city not found'));
        }
        $city->clearMediaCollection('images');


        $city->delete();
        return responseApi(200);

    }
}
