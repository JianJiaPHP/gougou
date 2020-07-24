<?php
/**
 * Notes:评价
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
 * @property int|null $user_id 用户ID
 * @property string|null $content 评价内容
 * @property \App\Models\Administrator|null $evaluate 评价 1：好评 2：中评 3：差评
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Evaluate whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Evaluate whereEvaluate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Evaluate whereUserId($value)
 * @property int|null $info_id 优惠id
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Evaluate whereInfoId($value)
 */
class Evaluate extends Base
{
    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
