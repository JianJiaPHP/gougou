<?php


namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DiscountInfoRequests;
use App\Models\AdminLoginLog;
use App\Models\BuyingInfo;
use App\Models\DiscountInfo;
use App\Models\Evaluate;
use App\Models\Goods;
use App\Models\Order;
use App\Models\ParkingInfo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;


class InfoController extends Controller
{
    /**
     * author: GongZiYan
     * Notes: 优惠信息列表
     * User: 1250472056@qq.com
     * Date: 2020/7/22
     */
    public function discountList(){
        $params = request()->all();
        $where = [];
        $where['state'] = 1;
        $lat =$params['lat'];
        $lng =$params['lng'];
        if (!empty($params['id'])){
            $where['id'] = $params['id'];
            $list = DiscountInfo::where($where)
                ->withCount(['evaluate'])
                ->select(DB::raw("(POWER(MOD(ABS(lng - $lng),360),2) + POWER(ABS(lat - $lat),2)) AS distance"),
                    'id','user_id','name','imgs','way','end_time','event_time','phone','content','state','lat','lng','created_at','updated_at')
                ->first();
            return success($list);
        }
        if (!empty($params['name'])){
            $where[] = ['name', 'like', "%".$params['name']."%"];
        }
        $list = DiscountInfo::where($where)
            ->orderBy('end_time', 'desc')
            ->orderBy('created_at', 'desc')
            ->select(DB::raw("(POWER(MOD(ABS(lng - $lng),360),2) + POWER(ABS(lat - $lat),2)) AS distance"),
               'id','user_id','name','imgs','way','end_time','event_time','phone','content','state','lat','lng','created_at','updated_at')
            ->withCount(['evaluate'])
            ->paginate(request()->get('limit',15))->toArray();
        return success($list);
    }
    /**
     * author: GongZiYan
     * Notes: 团购信息列表
     * User: 1250472056@qq.com
     * Date: 2020/7/22
     */
    public function buyingList(){
        $params = request()->all();
        $where = [];
        $where['state'] = 1;
        if (!empty($params['id'])){
            $where['id'] = $params['id'];
            $list = BuyingInfo::where($where)
                ->orderBy('created_at', 'desc')
                ->first();
            return success($list);
        }
        $list = BuyingInfo::where($where)->orderBy('created_at', 'desc')
            ->paginate(request()->get('limit',15))->toArray();
        return success($list);
    }
    /**
     * author: GongZiYan
     * Notes: 出租信息列表
     * User: 1250472056@qq.com
     * Date: 2020/7/22
     */
    public function parkingList(){
        $params = request()->all();
        $where = [];
        $where['state'] = 1;
        $list = ParkingInfo::where($where)
            ->orderBy('created_at', 'desc')
            ->paginate(request()->get('limit',15))->toArray();
        return success($list);
    }

    /**
     * author: GongZiYan
     * Notes: 优惠->评价列表
     * User: 1250472056@qq.com
     * Date: 2020/7/22
     */
    public function discountEvaluate(){
        $params = request()->all();
        $where = [];
        if (empty($params['id'])){
            return fail("缺少参数id");
        }else{
            $where['info_id'] =$params['id'];
        }
        $list = Evaluate::where($where)->with(['user'])->get()->toArray();
        return success($list);
    }

    /**
     * author: GongZiYan
     * Notes: 优惠->评价列表
     * User: 1250472056@qq.com
     * Date: 2020/7/22
     */
    public function discountEvaluateCreate(){
        $params = request()->all();
//        $params['user_id'] = auth('api')->id();
        $params['user_id'] = 1;
        if (empty($params['info_id'])){
            return fail("缺少参数信息id");
        }
        if (empty($params['content'])){
            return fail("请输入内容");
        }
        if (empty($params['evaluate'])){
            return fail("请输入评价");
        }
        $list = Evaluate::create($params);
        return success($list);
    }

    /**
     * author: GongZiYan
     * Notes: 商品列表
     * User: 1250472056@qq.com
     * Date: 2020/7/23
     */
    public function discountEvaluateGoods(){
        $params = request()->all();
        $user_id = auth('api')->id();
        $where['user_id'] = 1;
        $where['state'] = 1;
        $where['goods_id'] = 1;
        $order = Order::where($where)->first();
        $goodsWhere['type'] = $params['type'];//1：优惠信息 2:团购信息 3:车位出租'
        //如果有购买过商品 可以免费
        if ($order){
            $goodsWhere[] = ['id','!=',1];
            $list = Goods::where($goodsWhere)->get()->toArray();
            return success($list);
        }
        $list = Goods::where($goodsWhere)->get()->toArray();
        return success($list);
    }

    /**
     * author: GongZiYan
     * Notes: 优惠发布(保存)(编辑)
     * User: 1250472056@qq.com
     * Date: 2020/7/22
     * @param DiscountInfoRequests $infoRequests
     */
    public function discountCreate(DiscountInfoRequests $infoRequests){
        $params = request()->all();
//        $user_id = auth('api')->id();
        $user_id = 1;
        //保存(未发布)(编辑)
        if (!empty($params['id'])){
            if ($params['state'] == 3){
                $add['name'] = $params['name'];
                $add['imgs'] = json_encode($params['imgs']);
                $add['way'] = $params['way'];
                $add['event_time'] = $params['event_time'];
                $add['phone'] = $params['phone'];
                $add['content'] = $params['content'];
                $add['position'] = $params['position'];
                $add['user_id'] = $user_id;
                $res = DiscountInfo::where('id',$params['id'])->update($add);
                return success($res);
            }
        }

        //保存(未发布)
        if ($params['state'] == 3){
            $add['name'] = $params['name'];
            $add['imgs'] = json_encode($params['imgs']);
            $add['way'] = $params['way'];
            $add['event_time'] = $params['event_time'];
            $add['phone'] = $params['phone'];
            $add['content'] = $params['content'];
            $add['position'] = $params['position'];
            $add['user_id'] = $user_id;
            $res = DiscountInfo::create($add);
            return success($res);
        }





    }

}
