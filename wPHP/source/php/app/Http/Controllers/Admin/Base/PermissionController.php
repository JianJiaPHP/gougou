<?php


namespace App\Http\Controllers\Admin\Base;


use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{

    protected $permission_service;

    public function __construct()
    {
        $this->permission_service = app('App\Services\Admin\PermissionService');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $params = request()->all();
        $data = Permission::orderBy('created_at','desc');

        $keyword = $params['keyword'];
        $data->when($keyword, function ($query, $keyword) {
            return $query->where('name', 'like', "%$keyword%")
                ->orWhere('router', 'like', "%$keyword%");
        });
        $result = $data->paginate(\request('limit','15'));
        return success($result);
    }

    /**
     * 权限添加
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     */
    public function store(Request $request)
    {
        $params = $request->all();
        $permission = $this->permission_service->store($params);
        return $permission;
    }


    /**
     * 权限更新
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     */
    public function update(Request $request, $id)
    {
        $params = $request->all();
        $permission = $this->permission_service->update($id,$params);
        return $permission;
    }

    /**
     * 权限删除
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     */
    public function destroy($id)
    {
        $id = explode(',',$id);
        $result = Permission::destroy($id);
        return choose($result);
    }

    /**
     * 权限树
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     */
    public function tree()
    {
        $permission = $this->permission_service->permissionsTree();
        return success($permission);
    }

    /**
     * 获取权限列表(交集)
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     * @date 2019/12/13 下午3:42
     */
    public function pathList()
    {
        $routes_list = $this->permission_service->differenceRoutes();
        return success($routes_list);
    }

    /**
     * 获取父级权限
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     */
    public function fatherPath()
    {
        $data = $this->permission_service->father();
        return success($data);
    }

    /**
     * 所有路由
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     */
    public function pathAll()
    {
        $routes_list = $this->permission_service->routesList();
        return success($routes_list);
    }


}
