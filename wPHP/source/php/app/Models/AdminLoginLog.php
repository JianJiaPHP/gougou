<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AdminLoginLog
 *
 * @property int $id
 * @property int|null $uid 管理员id
 * @property string|null $ip ip地址
 * @property string|null $country 国家
 * @property string|null $region 区域
 * @property string|null $city 城市
 * @property string|null $county 县
 * @property string|null $isp 运营商
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Administrator|null $administrator
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminLoginLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminLoginLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminLoginLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminLoginLog whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminLoginLog whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminLoginLog whereCounty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminLoginLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminLoginLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminLoginLog whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminLoginLog whereIsp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminLoginLog whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminLoginLog whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminLoginLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AdminLoginLog extends Model
{
    protected $table = "admin_login_logs";
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
