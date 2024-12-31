<?php

namespace Modules\Info\App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Modules\Admin\App\Models\Admin;
use Modules\Info\App\Http\resources\Dashboard\FaqListResource;
use Modules\Info\App\Http\resources\Dashboard\FaqResource;
use Modules\Info\App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
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
        $Faqs=Faq::when($request->search , function ($q) use ($request){
            $q->whereTranslationLike('title','%'. $request->search .'%');
        })->orderBy('sort')->paginate($count_paginate);
        $data=[
            'Faqs'=>FaqListResource::collection($Faqs),
            'count'=>$Faqs->total(),
            'current_page'=>$Faqs->currentPage(),
            'last_page'=>$Faqs->lastPage(),
        ];

        return responseApi(200, translate('return_data_success'),$data );

    }
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'sort' => 'required|numeric',
            'ar' => 'required|array',
            'ar.title' => 'required|string',
            'ar.description' => 'required|string',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());
        DB::beginTransaction();

        try {
            $data = $request->all();
            $Faq = Faq::create($data);
            DB::commit();
            return responseApi(200, translate('return_data_success'), new  FaqResource($Faq));

        } catch (\Exception $e) {
            DB::rollBack();
           return responseApiFalse(500, __('site.same_error'));
        }
    }


    public  function show ($id ){
        $Faq = Faq::whereId($id)->with('translations')->first();
        if(!$Faq){
            return responseApiFalse(500, translate('Faq not found'));
        }
        return responseApi(200, translate('return_data_success'), new  FaqResource($Faq));
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

            $Faq = Faq::whereId($id)->first();
            $data = $request->all();

           if(!$Faq){
                return responseApiFalse(500, __('site.Faq not found'));
            }

            $Faq->update($data);


            DB::commit();
            return responseApi(200, translate('return_data_success'), new FaqResource($Faq));

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
        $Faq=  Faq::where('id', $id)->first();
        if(!$Faq){
            return responseApiFalse(404, translate('Faq not found'));
        }

        try {
            DB::beginTransaction();
            $Faq->status = ($Faq->status - 1) * -1;
            $Faq->save();
            DB::commit();
            return responseApi(200, translate('update Faq success'));
        }catch (\Exception $exception){
            DB::rollBack();
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }
    }
    public function destroy($id)
    {

        $Faq = Faq::whereId($id)->first();
        if(!$Faq){
            return responseApiFalse(500, translate('Faq not found'));
        }
        $Faq->delete();
        return responseApi(200);

    }
}
