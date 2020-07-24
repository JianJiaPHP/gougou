<?php


namespace App\Services\Admin;


use App\Models\Permission;
use App\Models\Role;

class PermissionService
{
    protected $permissions;//所有权限

    protected $hasRoutes;//所有权限（只查'route','methods'）

    protected $routesList;//所有后台路由

    public function __construct()
    {
        $this->permissions = Permission::orderBy('sort', 'asc')->get()->toArray();
        $this->hasRoutes = Permission::select('router', 'method')->get()->toArray();
        $all_routes = app()->routes->getRoutes();
        //获取所有后台路由
        foreach ($all_routes as $k => $value) {
            if (in_array('admin', $value->action['middleware'])) {
                $this->routesList[$k]['router'] = $value->uri;
                $this->routesList[$k]['method'] = $value->methods[0];
            }
        }
    }

    /**
     * 获取添加修改的数据
     * @param $params
     * @return array
     * @author Aii
     * @date 2019/12/20 下午4:58
     */
    private function getData($params)
    {
        //0=菜单 1=按钮 3=页面 2=接口
        if ($params['type'] == 2) {
            $routers = explode(',', $params['router']);
            $data = [
                'router' => $routers[0],
                'method' => $routers[1],
            ];
        } elseif ($params['type'] == 1) {
            $data = [
                'btn_key' => $params['btn_key'],
            ];
        } else {
            $data = [
                'icon' => $params['icon'],
                'component' => $params['component'],
                'router' => $params['router'],
            ];
        }
        $data = array_merge($data, [
            'name' => $params['name'],
            'pid' => $params['pid'],
            'type' => $params['type'],
            'sort' => $params['sort'],
        ]);

        return $data;
    }

    /**
     * 添加权限
     * @param $params
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     * @date 2019/12/20 下午4:58
     */
    public function store($params)
    {
        $data = $this->getData($params);
        $result = Permission::create($data);
        return success($result);

    }

    /**
     * 修改权限
     * @param $id
     * @param $params
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     * @date 2019/12/20 下午4:59
     */
    public function update($id, $params)
    {
        $data = $this->getData($params);
        $result = Permission::where('id', $id)->update($data);
        return success($result);

    }

    /**
     * 获取所有路由
     * @return array
     */
    public function routesList()
    {
        return $this->routesList;
    }

    /**
     * 已使用和未使用路由差集列表
     * @return array
     */
    public function differenceRoutes()
    {
        $routes = [];
        //获取差集路由
        foreach ($this->routesList as $key => $value) {
            if (!in_array($value, $this->hasRoutes)) {
                $routes[] = $value;
            }
        }
        return $routes;
    }


    /**
     * 数据权限树图
     * @param $permissions
     * @param $pid
     * @param $lvl
     * @return array
     * @author Aii
     */
    public function treeRoutes($permissions, $pid, $lvl)
    {
        $fathers = [];
        foreach ($permissions as $key => $v) {
            if ($v['pid'] == $pid) {
                $lvls = $pid == 0 ? $lvl : $lvl + 1;
                $child = ['name' => str_repeat('|____', $lvls) . $v['name'], 'id' => $v['id']];
                $fathers[] = $child;
                $fathers = array_merge($fathers, $this->treeRoutes($permissions, $v['id'], $lvls));
            }
        }
        return $fathers;
    }

    /**
     * 获取所有的权限
     * @param $data
     * @param int $id
     * @return array
     * @author Aii
     * @date 2019/12/23 上午11:33
     */
    public function getChild($data, $id = 0)
    {
        //初始化结果
        $result = [];
        //循环所有数据找$id的儿子
        foreach ($data as $key => $datum) {
            //找到儿子了
            if ($datum['pid'] == $id) {
                $type = $datum['type'];
                if ($type == Permission::MENU) {
                    $type_name = '菜单';
                } elseif ($type == Permission::BUTTON) {
                    $type_name = '按钮';
                } elseif ($type == Permission::PAGE) {
                    $type_name = '页面';
                } else {
                    $type_name = '接口';
                }
                //保存下来，然后继续找儿子的儿子
                $children = ['name' => $datum['name'] . "【{$type_name}】", 'id' => $datum['id']];
                //递归调用
                $children['children'] = $this->getChild($data, $datum['id']);
                if (empty($children['children'])) {
                    //如果不存在$child['children']就销毁这个变量
                    unset($children['children']);
                }
                //追加进$result
                array_push($result, $children);
            }
        }
        return $result;
    }

    /**
     * 数据权限树图
     * @return array
     * @author Aii
     * @date 2019/12/13 上午11:22
     */
    public function permissionsTree()
    {
        $tree = $this->getChild($this->permissions, 0);
        return $tree;
    }

    /**
     * 获取父级菜单（所有为菜单的）
     * @return array
     * @author Aii
     * @date 2019/12/20 下午2:50
     */
    public function father()
    {
        $permissions = Permission::orderBy('sort', 'asc')
            ->whereType(Permission::MENU)
            ->get();

        $fathers = [];
        foreach ($permissions as $key => $p) {
            if ($p['pid'] == 0) {
                array_push($fathers, ['id' => $p['id'], 'router' => $p['router'], 'name' => $p['name']]);
                unset($this->permissions[$key]);
                foreach ($this->permissions as $value) {
                    if ($value['pid'] == $p['id']) {
                        $value['name'] = '|__' . $value['name'];
                        array_push($fathers, ['id' => $value['id'], 'router' => $value['router'], 'name' => $value['name']]);
                    }
                }
            }
        }
        array_unshift($fathers, ['id' => 0, 'name' => '顶级']);
        return $fathers;
    }


    /**
     * 获取菜单列
     * @param $permissions
     * @param $role_id int 角色id
     * @return array
     * @author Aii
     * @date 2019/12/20 下午2:49
     */
    private function navs($permissions,$role_id)
    {
        $navs = [];
        foreach ($permissions as $key => $p) {
            if ($p['pid'] == 0) {
                //循环一级菜单
                $nav = [
                    'id'=>$p['id'],
                    'path' => $p['router'],
                    'name' => ucfirst(str_replace('/', '', $p['router'])),
                    'component' => !isset($p['component']) ? 'Layout' : $p['component'],
                    'redirect' => $p['router'] . Permission::wherePid($p['id'])->value('router'),
                    'hidden'=> $p['type'] == Permission::MENU  ? false  : true,
                    'meta' => [
                        'icon' => $p['icon'],
                        'title' => $p['name'],
                    ]
                ];
                $nav['children'] = [];
                //找到当前一级菜单的二级菜单
                foreach ($permissions as $value) {
                    if ($value['pid'] == $p['id']) {
                        $btn = Permission::wherePid($value['id'])
                            ->whereType(Permission::BUTTON)->pluck('btn_key');
                        if ($role_id != 0){
                            $permission = Role::where('id', $role_id)->value('permission');
                            $btn = Permission::wherePid($value['id'])
                                ->whereIn('id', $permission)
                                ->whereType(Permission::BUTTON)->pluck('btn_key');
                        }


                        //追加到children里
                        array_push($nav['children'], [
                            'path' => $value['router'],
                            'name' => ucfirst(str_replace('/', '', $p['router'])),
                            'component' => $value['component'],
                            'hidden'=> $value['type'] == Permission::MENU  ? false  : true,
                            'meta' => [
                                'id' => $value['id'],
                                'icon' => $value['icon'],
                                'title' => $value['name'],
                                'role' => $btn,
                            ]
                        ]);
                    }
                }
                //如果children不存在就去除children
                if (empty($nav['children'])) {
                    unset($nav['children']);
                }
                //追加到nav数组里
                array_push($navs, $nav);
            }
        }
        return $navs;
    }


    /**
     * 获取用户的权限
     * @return array
     * @author Aii
     * @date 2019/12/17 下午5:07
     */
    public function getNav()
    {
        $user = auth('admin')->user();

        $role_id = $user['role_id'];

        //超级管理员查出所有页面菜单权限
        if ($role_id == 0) {
            $permissions = Permission::orderBy('sort', 'asc')
//                ->whereType(Permission::MENU)
                ->get();
        } else {
            $permission = Role::where('id', $role_id)->value('permission');
            $permissions = Permission::whereIn('id', $permission)
//                ->whereType(Permission::MENU)
                ->orderBy('sort', 'asc')
                ->get();
        }
        return $this->navs($permissions,$role_id);
    }
}
