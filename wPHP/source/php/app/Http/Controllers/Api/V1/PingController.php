<?php


namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Models\AdminLoginLog;


class PingController extends Controller
{
    /**
     * ping
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $routesList = [];
        $all_routes = app()->routes->getRoutes();
        //获取所有后台路由
        foreach ($all_routes as $k => $value) {
            if (in_array('admin', $value->action['middleware'])) {
                $routesList[$k]['router'] = $value->uri;
                $routesList[$k]['method'] = $value->methods[0];
            }
        }

        return  success($routesList);
    }

}
