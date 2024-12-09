<?php

namespace Modules\Sort\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Sort\App\Http\Resources\PropertyTypeResource;
use Modules\Sort\App\Models\PropertyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PropertyTypeController extends Controller
{

    public  function index(Request $request){
        $property_types=PropertyType::Active()->orderBy('sort')->get();
        return responseApi(200, translate('return_data_success'), PropertyTypeResource::collection($property_types));

    }
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'ar' => 'required|array',
            'ar.title' => 'required|string',
            'status' => 'nullable|in:1,0',
            'sort' => 'nullable|string',
            'image' => 'required|image',

        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());
        DB::beginTransaction();

        try {
            $data = $request->except(['image']);

            $property_type = PropertyType::create($data);

            $property_type->addMediaFromRequest('image')->toMediaCollection('images');

            DB::commit();
            return responseApi(200, translate('return_data_success'), new  PropertyTypeResource($property_type));

        } catch (\Exception $e) {
            DB::rollBack();
            return responseApiFalse(500, translate('same_error'));
        }
    }



    public  function update ($id,Request $request){
        $validator = Validator::make($request->all(), [
            'ar' => 'required|array',
            'ar.title' => 'required|string',
            'image' => 'nullable|image',

        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        try {
            DB::beginTransaction();

            $property_type = PropertyType::find($id);
            $data = $request->all();

           if(!$property_type){
                return responseApiFalse(500, translate('property_type not found'));
            }
            $property_type->update($data);

            if($request->hasFile('image')){
                $property_type->clearMediaCollection('images');
                $property_type->addMediaFromRequest('image')->toMediaCollection('images');
            }
            DB::commit();
            return responseApi(200, translate('return_data_success'), new PropertyTypeResource($property_type));

        }catch (\Exception $e){
            DB::rollback();
            return responseApiFalse(500, translate('same_error'));
        }
    }


    public function destroy($id)
    {

        $property_type = PropertyType::find($id);
        if(!$property_type){
            return responseApiFalse(500, __('site.property_type not found'));
        }
        $property_type->clearMediaCollection('images');


        $property_type->delete();
        return responseApi(200);

    }
}
