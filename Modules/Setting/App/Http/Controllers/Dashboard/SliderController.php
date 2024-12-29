<?php

namespace Modules\Setting\App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Modules\Home\App\Models\Slider;
use Modules\Setting\App\Http\resources\Dashboard\SliderResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    protected $count_paginate = 10;

    public  function index(Request $request){
        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $sliders=Slider::paginate($count_paginate);
        $data=[
            'sliders'=>SliderResource::collection($sliders),
            'count'=>$sliders->count(),
            'current_page'=>$sliders->currentPage(),
            'last_page'=>$sliders->lastPage(),
        ];

        return responseApi(200, translate('return_data_success'),$data );

    }
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:None,ULR,Sort,Merge',
            'url' => 'required_if:type,==,ULR|url',
            'start_at' => 'required|date|date_format:Y-m-d',
            'end_at' => 'nullable|date|date_format:Y-m-d',
            'status' => 'required|in:1,0',
            'sort' => 'required|numeric|min:0',
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());


        DB::beginTransaction();
        try {
            $data = [
                 "type" => $request->type,
                  "url" => $request->url,
                  "start_at" => $request->start_at,
                  "end_at" => $request->end_at,
                  "status" => $request->status,
                  "sort" => $request->sort,
            ];

            $slider = Slider::create($data);
            $slider->addMediaFromRequest('image')->toMediaCollection('images');

            DB::commit();
            return responseApi(200, translate('return_data_success'), new  SliderResource($slider));

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public  function show ($id ){
        $slider = Slider::whereId($id)->first();
        if(!$slider){
            return responseApiFalse(500, translate('slider not found'));
        }
        return responseApi(200, translate('return_data_success'), new  SliderResource($slider));
    }
    public  function update ($id,Request $request){
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:None,ULR,Sort,Merge',
            'url' => 'required_if:type,==,ULR|url',
            'start_at' => 'required|date|date_format:Y-m-d',
            'end_at' => 'nullable|date|date_format:Y-m-d',
            'status' => 'required|in:1,0',
            'sort' => 'required|numeric|min:0',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());


        $slider = Slider::find($id);
        if(!$slider){
            return responseApiFalse(500, translate('slider not found'));
        }

        DB::beginTransaction();
        try {


            $data = [
                "type" => $request->type,
                "url" => $request->url,
                "start_at" => $request->start_at,
                "end_at" => $request->end_at,
                "status" => $request->status,
                "sort" => $request->sort,
            ];
            $slider->update($data);

            if ($request->hasFile('image')) {
                $slider->clearMediaCollection('images');
                $slider->addMediaFromRequest('image')->toMediaCollection('images');
            }

            DB::commit();
            return responseApi(200, translate('return_data_success'), new SliderResource($slider));

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
        $slider=  Slider::where('id', $id)->first();
        if(!$slider){
            return responseApiFalse(404, translate('Slider not found'));
        }

        try {
            DB::beginTransaction();
                $slider->status = ($slider->status - 1) * -1;
                $slider->save();
            DB::commit();
            return responseApi(200, translate('update Slider success'));
        }catch (\Exception $exception){
            DB::rollBack();
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }
    }
    public function destroy($id)
    {

        $slider = Slider::find($id);
        if(!$slider){
            return responseApiFalse(500, translate('slider not found'));
        }
        $slider->clearMediaCollection('images');
        $slider->delete();
        return responseApi(200);

    }
}
