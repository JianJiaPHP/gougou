<?php


namespace App\Http\Middleware;


class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if (!auth('api')->check()) {
            return otherReturn(401,'请重新登录');
        }
        //如果没有就重新登录
        if (!auth('api')->user()) {
            return otherReturn(401,'请重新登录');
        }
        return $next($request);
    }

}
