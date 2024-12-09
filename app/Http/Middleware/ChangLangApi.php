<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ChangLangApi
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
        $lang = $request->header('Accept-Language');
        if (in_array($lang, config('app.locales'))) app()->setLocale($lang);
        else app()->setLocale('ar');
        return $next($request);
    }
}
