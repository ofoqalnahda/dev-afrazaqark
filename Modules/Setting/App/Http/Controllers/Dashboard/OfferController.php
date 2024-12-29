<?php

namespace Modules\Setting\App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Modules\Home\App\Models\Offer;
use Modules\Setting\App\Http\resources\Dashboard\OfferResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OfferController extends Controller
{
    protected $count_paginate = 10;

    public  function index(Request $request){
        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $offers=Offer::paginate($count_paginate);
        $data=[
            'offers'=>OfferResource::collection($offers),
            'count'=>$offers->count(),
            'current_page'=>$offers->currentPage(),
            'last_page'=>$offers->lastPage(),
        ];

        return responseApi(200, translate('return_data_success'),$data );

    }
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'status' => 'required|in:1,0',
            'sort' => 'required|numeric|min:0',
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());


        DB::beginTransaction();
        try {
            $data = [
                 "name" => $request->title,
                  "sort" => $request->sort,
                  "status" => $request->status
            ];

            $offer = Offer::create($data);

            $offer->addMediaFromRequest('image')->toMediaCollection('images');

            DB::commit();
            return responseApi(200, translate('return_data_success'), new  OfferResource($offer));

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public  function show ($id ){
        $offer = Offer::whereId($id)->first();
        if(!$offer){
            return responseApiFalse(500, translate('offer not found'));
        }
        return responseApi(200, translate('return_data_success'), new  OfferResource($offer));
    }
    public  function update ($id,Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'status' => 'required|in:1,0',
            'sort' => 'required|numeric|min:0',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());


        $offer = Offer::find($id);
        if(!$offer){
            return responseApiFalse(500, translate('offer not found'));
        }

        DB::beginTransaction();
        try {


            $data = [
                "name" => $request->title,
                "sort" => $request->sort,
                "status" => $request->status
            ];
            $offer->update($data);

            if ($request->hasFile('image')) {
                $offer->clearMediaCollection('images');
                $offer->addMediaFromRequest('image')->toMediaCollection('images');
            }

            DB::commit();
            return responseApi(200, translate('return_data_success'), new OfferResource($offer));

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
        $offer=  Offer::where('id', $id)->first();
        if(!$offer){
            return responseApiFalse(404, translate('Offer not found'));
        }

        try {
            DB::beginTransaction();
                $offer->status = ($offer->status - 1) * -1;
                $offer->save();
            DB::commit();
            return responseApi(200, translate('update Offer success'));
        }catch (\Exception $exception){
            DB::rollBack();
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }
    }
    public function destroy($id)
    {

        $offer = Offer::find($id);
        if(!$offer){
            return responseApiFalse(500, translate('offer not found'));
        }
        $offer->clearMediaCollection('images');
        $offer->delete();
        return responseApi(200);

    }
}
