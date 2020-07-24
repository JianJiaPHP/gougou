<?php


namespace App\Http\Controllers\Admin\Base;


use App\Http\Controllers\Controller;
use App\Models\Role;

class RoleController extends Controller
{
    /**
     * 角色列表
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     */
    public function index()
    {
        $params = request()->all();
        $data = Role::orderBy('created_at', 'desc');

        $keyword = $params['keyword'];
        $data->when($keyword, function ($query, $keyword) {
            return $query->where('name', 'like', "%$keyword%")
                ->orWhere('desc', 'like', "%$keyword%");
        });

        $result = $data->paginate(request('limit',15));
        return success($result);
    }


    /**
     * 角色添加
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     */
    public function store()
    {
        $params = request()->all();

        $permission = explode(',',$params['permission']);
        $result = Role::create([
            'name' => $params['name'],
            'desc' => $params['desc'],
            'permission' => serialize($permission),
        ]);
        return choose($result);
    }


    /**
     * 角色更新
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     */
    public function update($id)
    {
        $params = request()->all();
        $permission = explode(',',$params['permission']);
        $result = Role::where('id', $id)->update([
            'name' => $params['name'],
            'desc' => $params['desc'],
            'permission' => serialize($permission),
        ]);

        return choose($result);

    }

    /**
     * 角色删除
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     */
    public function destroy($id)
    {
        $id = explode(',', $id);
        $result = Role::destroy($id);
        return choose($result);
    }

    /**
     * 获取所有角色
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     */
    public function getAll()
    {
        $data = Role::orderBy('created_at', 'desc')->select('id', 'name')->get();
        return choose($data);
    }

}
