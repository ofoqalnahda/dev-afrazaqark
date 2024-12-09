<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CheckApiSecretKey
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $SECRET_KEY = $request->header('Accept-Secret-Key');
        $pass=env('API_SECRET_KEY');
        if(Hash::check($SECRET_KEY,$pass)){
            return $next($request);
        }
        return  responseApiFalse(404,translate('Page not found'));

    }
}
