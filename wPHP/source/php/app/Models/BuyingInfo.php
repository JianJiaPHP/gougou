<?php
/**
 * Notes:团购优惠
 * User: 12504
 * Date: 2020/5/25
 * Time: 16:55
 * @return
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Boss
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Boss newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Boss newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Boss query()
 * @mixin \Eloquent
 * @property int $id
 * @property string|null $name 商户名
 * @property string|null $imgs 图片“，”逗号隔开
 * @property string|null $way 优惠方式
 * @property string|null $end_time 发布结束时间
 * @property string|null $phone 联系电话
 * @property string|null $position 经纬度位置
 * @property int|null $state 1：已发布 2:已撤回 3:未发布
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DiscountInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DiscountInfo whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DiscountInfo whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DiscountInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DiscountInfo whereImgs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DiscountInfo whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DiscountInfo wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DiscountInfo wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DiscountInfo whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DiscountInfo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DiscountInfo whereWay($value)
 * @property-read \App\Models\Administrator|null $evaluate
 * @property string|null $details 商品描述
 * @property string|null $time_1 活动开始时间
 * @property string|null $time_0 活动截止时间
 * @property string|null $price 原价
 * @property string|null $price_new 现价格
 * @property string|null $com_name 小区名称
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Evaluate[] $evaluateCount
 * @property-read int|null $evaluate_count_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BuyingInfo whereComName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BuyingInfo whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BuyingInfo wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BuyingInfo wherePriceNew($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BuyingInfo whereTime0($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BuyingInfo whereTime1($value)
 */
class BuyingInfo extends Base
{

    public function evaluate()
    {
        return $this->hasOne(Evaluate::class,'user_id','id');
    }

    public function evaluateCount()
    {
        return $this->hasMany('App\Models\Evaluate');
    }
}
