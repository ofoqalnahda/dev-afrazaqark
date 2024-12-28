<?php

namespace Modules\Home\App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Home\App\Models\Offer;
use Modules\Home\App\Models\Slider;
use Modules\Home\App\resources\InfoResource;
use Modules\Home\App\resources\SliderResource;
use Modules\Info\App\Models\Faq;
use Modules\Info\App\resources\FaqResource;

class HomeController extends Controller
{
    public function __construct(Request $requst)
    {
        if ($requst->header('Authorization')){
            $this->middleware('auth.gard:api');
        }

    }
    /**
     * Display a listing of the resource.
     * @return Application|Response|ResponseFactory
     */
    public function index(): Application|Response|ResponseFactory
    {
        $data['sliders']=SliderResource::collection(Slider::Active()->orderBy('sort')->get());
        $data['offers']=InfoResource::collection(Offer::Active()->orderBy('sort')->get());
        $data['fqa']=FaqResource::collection(Faq::orderBy('sort')->take(3)->get());
      return  responseApi(200,'',$data);
    }


}
