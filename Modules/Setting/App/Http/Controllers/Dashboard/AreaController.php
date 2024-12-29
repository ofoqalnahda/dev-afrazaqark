<?php

namespace Modules\Setting\App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Modules\Setting\App\Http\resources\Dashboard\AreaResource;
use Modules\Setting\App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{
    protected $count_paginate = 10;

    public  function index(Request $request){
        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $areas=Area::when($request->search , function ($q) use ($request){
            $q->whereTranslationLike('title','%'. $request->search .'%');
        })->with('translations')->paginate($count_paginate);
        $data=[
            'areas'=>AreaResource::collection($areas),
            'count'=>$areas->count(),
            'current_page'=>$areas->currentPage(),
            'last_page'=>$areas->lastPage(),
        ];

        return responseApi(200, translate('return_data_success'),$data );

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
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public  function show ($id ){
        $area = Area::whereId($id)->with('translations')->first();
        if(!$area){
            return responseApiFalse(500, translate('area not found'));
        }
        return responseApi(200, translate('return_data_success'), new  AreaResource($area));
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

            $area = Area::whereId($id)->first();
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

    /**
     * Update status.
     * @param $id
     * @return Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
     */
    public function updateStatus( $id): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        $area=  Area::where('id', $id)->first();
        if(!$area){
            return responseApiFalse(404, translate('Area not found'));
        }

        try {
            DB::beginTransaction();
            $area->status = ($area->status - 1) * -1;
            $area->save();
            DB::commit();
            return responseApi(200, translate('update Area success'));
        }catch (\Exception $exception){
            DB::rollBack();
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }
    }
    public function destroy($id)
    {

        $area = Area::whereId($id)->first();
        if(!$area){
            return responseApiFalse(500, translate('area not found'));
        }
        $area->delete();
        return responseApi(200);

    }
}
