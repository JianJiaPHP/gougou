<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Models\Base
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Base newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Base newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Base query()
 * @mixin \Eloquent
 */
class Base extends Model
{
    /**
     * 获取表名，当前类名转下划线
     * @return string
     * @author Aii
     * @date 2020/5/18 下午2:17
     */
    public function getTable()
    {
        return Str::snake(class_basename($this));
    }
    protected $guarded = [];

}
