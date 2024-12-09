<?php

namespace Modules\Sort\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Sort\App\Http\Resources\RouteTypeResource;
use Modules\Sort\App\Models\RouteType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RouteTypeController extends Controller
{

    public  function index(Request $request){
        $route_types=RouteType::Active()->orderBy('sort')->get();
        return responseApi(200, translate('return_data_success'), RouteTypeResource::collection($route_types));

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
            $route_type = RouteType::create($data);


            DB::commit();
            return responseApi(200, translate('return_data_success'), new  RouteTypeResource($route_type));

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

            $route_type = RouteType::find($id);
            $data = $request->all();

           if(!$route_type){
                return responseApiFalse(500, translate('route_type not found'));
            }
            $route_type->update($data);
            DB::commit();
            return responseApi(200, translate('return_data_success'), new RouteTypeResource($route_type));

        }catch (\Exception $e){
            DB::rollback();
            return responseApiFalse(500, translate('same_error'));
        }
    }


    public function destroy($id)
    {

        $route_type = RouteType::find($id);
        if(!$route_type){
            return responseApiFalse(500, __('site.route_type not found'));
        }
        $route_type->delete();
        return responseApi(200);

    }
}
