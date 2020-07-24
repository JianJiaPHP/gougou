<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Permission
 *
 * @property int $id
 * @property int $type 类型 0=菜单 1=按钮 2=接口 3=页面
 * @property string|null $btn_key 按钮权限（如 create）
 * @property string|null $component 组件
 * @property string|null $icon 图标
 * @property int $pid 父级id 0=顶级
 * @property string|null $name 权限名称
 * @property string|null $router 权限路径
 * @property string|null $method 请求方式
 * @property int $sort 排序
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission whereBtnKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission whereComponent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission whereRouter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Permission extends Model
{
    protected $table = 'permissions';
    protected $guarded = [];
    //菜单
    const MENU = 0;
    //按钮
    const BUTTON = 1;
    // 接口
    const INTERFACES = 2;
    //页面
    const  PAGE = 3;

}
