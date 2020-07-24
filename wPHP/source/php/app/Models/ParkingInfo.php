<?php
/**
 * Notes:优惠信息
 * User: 12504
 * Date: 2020/5/25
 * Time: 16:55
 * @return
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Models\ParkingInfo
 *
 * @property int $id
 * @property string|null $name 联系名
 * @property string|null $phone 联系方式
 * @property int|null $time 车位出租多少个月
 * @property string|null $where 车位位置
 * @property string|null $price 金额
 * @property int|null $state 1：已发布 2:已撤回
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ParkingInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ParkingInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ParkingInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ParkingInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ParkingInfo whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ParkingInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ParkingInfo whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ParkingInfo wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ParkingInfo wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ParkingInfo whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ParkingInfo whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ParkingInfo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ParkingInfo whereWhere($value)
 * @mixin \Eloquent
 */
class ParkingInfo extends Base
{


}
