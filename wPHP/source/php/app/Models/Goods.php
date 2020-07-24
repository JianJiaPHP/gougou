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
 * App\Models\Goods
 *
 * @property int $id
 * @property string|null $name 商品名
 * @property int|null $time 月份时间  1：1个月 2：2个月
 * @property int|null $type 适用类型 ：1：优惠信息 2:团购信息 3:车位出租
 * @property float|null $price 价格
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Goods newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Goods newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Goods query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Goods whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Goods whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Goods whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Goods whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Goods wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Goods whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Goods whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Goods whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Goods extends Base
{


}
