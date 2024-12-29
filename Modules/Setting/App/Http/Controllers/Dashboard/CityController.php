<?php

namespace Modules\Setting\App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Modules\Setting\App\Http\resources\Dashboard\CityResource;
use Modules\Setting\App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    protected $count_paginate = 10;

    public  function index(Request $request){
        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $cities=City::when($request->search , function ($q) use ($request){
            $q->whereTranslationLike('title','%'. $request->search .'%');
        })->when($request->area_id , function ($q) use ($request)   {
            $q->where('area_id',$request->area_id);
        })->with('translations')->paginate($count_paginate);
        $data=[
            'cities'=>CityResource::collection($cities),
            'count'=>$cities->count(),
            'current_page'=>$cities->currentPage(),
            'last_page'=>$cities->lastPage(),
        ];

        return responseApi(200, translate('return_data_success'),$data );

    }
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'ar' => 'required|array',
            'area_id' => 'required|exists:areas,id',
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


    public  function show ($id ){
        $city = City::whereId($id)->with('translations')->first();
        if(!$city){
            return responseApiFalse(500, translate('city not found'));
        }
        return responseApi(200, translate('return_data_success'), new  CityResource($city));
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

            $city = City::whereId($id)->first();
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

    /**
     * Update status.
     * @param $id
     * @return Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
     */
    public function updateStatus( $id): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        $city=  City::where('id', $id)->first();
        if(!$city){
            return responseApiFalse(404, translate('City not found'));
        }

        try {
            DB::beginTransaction();
            $city->status = ($city->status - 1) * -1;
            $city->save();
            DB::commit();
            return responseApi(200, translate('update City success'));
        }catch (\Exception $exception){
            DB::rollBack();
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }
    }
    public function destroy($id)
    {

        $city = City::whereId($id)->first();
        if(!$city){
            return responseApiFalse(500, translate('city not found'));
        }
        $city->delete();
        return responseApi(200);

    }
}
