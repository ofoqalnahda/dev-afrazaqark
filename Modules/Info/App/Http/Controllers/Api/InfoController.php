<?php

namespace Modules\Info\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Response;
use Modules\Info\App\Http\resources\InfoResource;
use Modules\Info\App\Models\Faq;
use Modules\Info\App\Models\Info;
use Modules\Info\App\resources\FaqResource;

class InfoController extends Controller
{
    public function FQA(): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        $faqs= Faq::when(\request()->search , function ($q){
            $q->WhereTranslationLike('title',  '%'.\request()->search.'%')->orWhereTranslationLike('description', '%'.\request()->search.'%');
        })->get();
        return responseApi('200','',FaqResource::collection($faqs));
    }

    public function Show(String $slug): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {

        $info =Info::where('slug', $slug)->first();
        if (!$info)
            return responseApiFalse(405,translate('page not found') );


        return responseApi('200','',new InfoResource($info));
    }
}
