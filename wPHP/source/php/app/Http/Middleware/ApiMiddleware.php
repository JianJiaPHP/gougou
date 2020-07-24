<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class ApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $api_sign = env('API_SIGN');
        //检查是否开始api的sign  没有开始直接下一步
        if (!$api_sign) {
            return $next($request);
        }
        $result = checkSign($request,env('API_KEY'));
        if (!$result){
            return fail();
        }

        return $next($request);
    }
}
