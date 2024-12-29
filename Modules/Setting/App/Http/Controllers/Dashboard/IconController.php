<?php

namespace Modules\Setting\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Setting\App\Http\resources\Api\IconResource;
use Modules\Setting\App\Models\Icon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class IconController extends Controller
{


    public function index()
    {
        $icons = Icon::get();
        return responseApi(200, translate('return_data_success'), IconResource::collection($icons));
    }


    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            'title' => 'required|string',
            'link' => 'required',
            'image' => 'required|image',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        DB::beginTransaction();

        try {
            $icon = Icon::create([
                'link' => $request->get("link"),
                'title' => $request->get("title"),
            ]);
            $icon->addMediaFromRequest('image')->toMediaCollection('images');
            DB::commit();
            return responseApi(200, translate('return_data_success'), new  IconResource($icon));

        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return responseApiFalse(500, __('site.same_error'));
        }
    }

    public function update($id, Request $request)
    {
        $validator = validator($request->all(), [
            'title' => 'required|string',
            'link' => 'required|URL',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        DB::beginTransaction();

        try {
            $icon = Icon::findOrFail($id);
            $icon->update([
                'link' => $request->get("link"),
                'title' => $request->get("title"),
            ]);
            if ($request->has('image')) {
                $icon->clearMediaCollection('images');
                $icon->addMediaFromRequest('image')->toMediaCollection('images');
            }

            DB::commit();
            return responseApi(200, translate('return_data_success'), new  IconResource($icon));

        } catch (\Exception $e) {
            DB::rollback();
            return responseApiFalse(500, __('site.same_error'));
        }
    }

    public function destroy($id)
    {

        $icon = Icon::find($id);
        if(!$icon){
            return responseApiFalse(500, __('site.icon not found'));
        }
        $icon->clearMediaCollection('images');
        $icon->delete();
        return responseApi(200);

    }


}
