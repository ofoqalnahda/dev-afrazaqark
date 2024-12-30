<?php

namespace Modules\Info\App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Modules\Admin\App\Models\Admin;
use Modules\Info\App\Http\resources\Dashboard\InfoResource;
use Modules\Info\App\Models\Faq;
use Modules\Info\App\Models\Info;
use Modules\Info\App\resources\FaqResource;

class InfoController extends Controller
{
    public function __construct()
    {
        Config::set( 'jwt.user', 'App\Models\Provider' );
        Config::set( 'auth.providers.users.model', Admin::class );
        $this->middleware('auth.gard:admin');
    }

    public function Show(String $slug): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {

        $info =Info::where('slug', $slug)->with('translations')->first();
        if (!$info)
            return responseApiFalse(405,translate('page not found') );


        return responseApi(200,'',new InfoResource($info));
    }


    public function update(String $slug,Request $request): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {

        $info =Info::where('slug', $slug)->first();
        if (!$info)
            return responseApiFalse(405,translate('page not found') );

            DB::beginTransaction();
        try{

            $info->update($request->all());
            DB::commit();
            return responseApi(200,'',new InfoResource($info));
        } catch (\Exception $e) {
            DB::rollBack();
            return responseApiFalse(500, __('site.same_error'));
        }

    }
}
