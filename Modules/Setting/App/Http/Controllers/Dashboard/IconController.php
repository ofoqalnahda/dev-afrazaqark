<?php

namespace Modules\Setting\App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Modules\Admin\App\Models\Admin;
use Modules\Setting\App\Http\resources\Dashboard\IconResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Setting\App\Models\Icon;

class IconController extends Controller
{
    protected $count_paginate = 10;
    public function __construct()
    {
        Config::set( 'jwt.user', 'App\Models\Provider' );
        Config::set( 'auth.providers.users.model', Admin::class );
        $this->middleware('auth.gard:admin');
    }
    public  function index(Request $request){
        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $icons=Icon::paginate($count_paginate);
        $data=[
            'icons'=>IconResource::collection($icons),
            'count'=>$icons->total(),
            'current_page'=>$icons->currentPage(),
            'last_page'=>$icons->lastPage(),
        ];

        return responseApi(200, translate('return_data_success'),$data );

    }
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'link' => 'required|url',
            'status' => 'required|in:1,0',
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());


        DB::beginTransaction();
        try {
            $data = [
                 "title" => $request->title,
                  "link" => $request->link,
                  "status" => $request->status
            ];

            $icon = Icon::create($data);

            $icon->addMediaFromRequest('image')->toMediaCollection('images');

            DB::commit();
            return responseApi(200, translate('return_data_success'), new  IconResource($icon));

        } catch (\Exception $e) {
            DB::rollBack();
           return responseApiFalse(500, __('site.same_error'));
        }
    }


    public  function show ($id ){
        $icon = Icon::whereId($id)->first();
        if(!$icon){
            return responseApiFalse(500, translate('icon not found'));
        }
        return responseApi(200, translate('return_data_success'), new  IconResource($icon));
    }
    public  function update ($id,Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'link' => 'required|url',
            'status' => 'required|in:1,0',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());


        $icon = Icon::find($id);
        if(!$icon){
            return responseApiFalse(500, translate('icon not found'));
        }

        DB::beginTransaction();
        try {


            $data = [
                "title" => $request->title,
                "link" => $request->link,
                "status" => $request->status
            ];
            $icon->update($data);

            if ($request->hasFile('image')) {
                $icon->clearMediaCollection('images');
                $icon->addMediaFromRequest('image')->toMediaCollection('images');
            }

            DB::commit();
            return responseApi(200, translate('return_data_success'), new IconResource($icon));

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
        $icon=  Icon::where('id', $id)->first();
        if(!$icon){
            return responseApiFalse(404, translate('Icon not found'));
        }

        try {
            DB::beginTransaction();
                $icon->status = ($icon->status - 1) * -1;
                $icon->save();
            DB::commit();
            return responseApi(200, translate('update Icon success'));
        }catch (\Exception $exception){
            DB::rollBack();
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }
    }
    public function destroy($id)
    {

        $icon = Icon::find($id);
        if(!$icon){
            return responseApiFalse(500, translate('icon not found'));
        }
        $icon->clearMediaCollection('images');
        $icon->delete();
        return responseApi(200);

    }
}
