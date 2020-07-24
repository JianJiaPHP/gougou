<?php


namespace App\Http\Controllers\Admin\Base;


use App\Http\Controllers\Controller;
use App\Models\PagePermission;


class PagePermissionController extends Controller
{

    protected $permission_service;

    public function __construct()
    {
        $this->permission_service = app('App\Services\Admin\PermissionService');
    }
    /**
     * 页面权限列表
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     */
    public function index()
    {
        $params = request()->all();
        $data = PagePermission::orderBy('created_at','desc')->with('pName');
        $keyword = $params['keyword'];
        $data->when($keyword, function ($query, $keyword) {
            return $query->where('name', 'like', "%$keyword%")
                ->orWhere('router', 'like', "%$keyword%");
        });
        $result = $data->paginate();

        return success($result);
    }
    /**
     * 页面权限添加
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     */
    public function store()
    {
        $params = request()->all();

        $result = PagePermission::create($params);

        return choose($result);
    }

    /**
     * 页面权限更新
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     */
    public function update()
    {
        $params = request()->all();

        $result = PagePermission::where('id',$params['id'])->update($params);

        return choose($result);
    }

    /**
     * 页面权限删除
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     */
    public function destroy($id)
    {
        $id = explode(',',$id);
        $result = PagePermission::destroy($id);
        return choose($result);
    }
    /**
     * 权限树
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     */
    public function tree()
    {
        $permission = $this->permission_service->getPermission();
        return success($permission);
    }

    /**
     * 页面权限树
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     */
    public function allTree()
    {
        $permission = $this->permission_service ->treeRouter();
        return success($permission);

    }


}
