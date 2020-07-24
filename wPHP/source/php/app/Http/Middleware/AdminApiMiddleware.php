<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class AdminApiMiddleware
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
        $admin_sign = env('ADMIN_SIGN');
        //检查是否开始api的sign  没有开始直接下一步
        if (!$admin_sign) {
            return $next($request);
        }
        if ($request->route()->uri == 'yw_admin/upload') {
            return $next($request);
        }
        $res = checkSign($request,env('ADMIN_KEY'));
        if (!$res){
            return fail();
        }

        return $next($request);
    }
}
