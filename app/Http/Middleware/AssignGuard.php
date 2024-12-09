<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class AssignGuard extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param null $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null): mixed
    {
        if ($guard != null) {
            auth()->shouldUse($guard); //should your user guard / table
            try {
                $user = JWTAuth::parseToken()->authenticate();

                if(!auth()->check())
                    return responseApiFalse(403, translate('Unauthenticated user'));

            } catch (TokenExpiredException|JWTException $e) {
                return responseApiFalse(403, translate('Unauthenticated user'));
            }

        } else {
            auth()->shouldUse($guard); //should your user guard / table
            try {
                JWTAuth::parseToken()->authenticate();
            } catch (TokenExpiredException|JWTException $e) {
                return $next($request);

            }
        }
        return $next($request);
    }
}
