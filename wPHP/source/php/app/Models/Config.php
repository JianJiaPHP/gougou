<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * App\Models\Config
 *
 * @property int $id
 * @property string|null $key 配置key
 * @property string|null $value 配置值
 * @property string|null $desc 配置描述
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Config newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Config newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Config query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Config whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Config whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Config whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Config whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Config whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Config whereValue($value)
 * @mixin \Eloquent
 */
class Config extends Model
{
    protected $table = 'config';
    protected $guarded = [];

    /**
     * 获取所有配置信息
     * @return mixed
     * @author Aii
     * @date 2020/1/17 上午9:34
     */
    public function getAll()
    {
        return Cache::rememberForever('config', function () {
            return array_combine($this->orderBy('id', 'asc')->pluck('key')->toArray(),
                $this->orderBy('id', 'asc')->pluck('value')->toArray());
        });
    }


    /**
     * 刷新缓存
     * @return bool
     * @author Aii
     * @date 2020/1/17 上午10:09
     */
    public function refresh()
    {
        Cache::forget('config');

        return $this->getAll();
    }

    /**
     *更新或者添加
     * @param array $params 配置值
     * @param int $id 配置ID
     * @return mixed
     * @author Aii
     * @date 2020/1/17 上午10:50
     */
    public function updateOrUpdate($params, $id = null)
    {
        if (is_null($id)) {
            $result = $this->create($params);
        } else {
            $result = $this->where('id', $id)->update($params);
        }

        $this->refresh();

        return $result;
    }

}
