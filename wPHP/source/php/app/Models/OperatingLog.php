<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OperatingLog
 *
 * @property int $id
 * @property int $uid 操作人员id
 * @property string|null $router 操作路径
 * @property string|null $method 操作方式
 * @property string|null $content 操作内容
 * @property string|null $desc 操作简单描述
 * @property string|null $ip 操作ip
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Administrator|null $administrator
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OperatingLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OperatingLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OperatingLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OperatingLog whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OperatingLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OperatingLog whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OperatingLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OperatingLog whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OperatingLog whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OperatingLog whereRouter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OperatingLog whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OperatingLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OperatingLog extends Model
{
    protected $table = 'operating_logs';
    protected $guarded = [];

    /**
     * 关联管理员
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function administrator()
    {
        return $this->hasOne(Administrator::class,'id','uid');
    }

}
