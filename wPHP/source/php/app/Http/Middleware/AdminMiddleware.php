<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use App\Models\Role;
use Closure;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!auth('admin')->check()) {
            return otherReturn(401,'请重新登录');
        }
        $user = auth('admin')->user();
        //如果没有就重新登录
        if (!$user) {
            return otherReturn(401,'请重新登录');
        }
        //TODO token和数据库不一样重新登陆
//        $jwt = $request->header('authorization');
//        $token = explode(' ',$jwt)[1];
//        if ($token != $user['token']){
//            return otherReturn(401,'请重新登录');
//        }

        //为0是超级管理员 直接通过
        if ($user['role_id'] == 0){
            return $next($request);
        }

        $route = $request->route()->uri;
        $methods = $request->method();

        $permissionsId = Permission::where('type',Permission::INTERFACES)
            ->where('router',$route)->where('method',$methods)->value('id');

        $role = Role::where('id',$user['role_id'])->value('permission');

        if(!in_array($permissionsId,$role)){
            return otherReturn(403,'您没有权限访问');
        }

        return $next($request);
    }
}
