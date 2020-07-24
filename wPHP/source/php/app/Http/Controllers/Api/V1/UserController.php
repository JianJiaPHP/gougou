<?php


namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Models\BuyingInfo;
use App\Models\DiscountInfo;
use App\Models\ParkingInfo;
use App\Models\User;
use App\Services\UploadService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;


class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('user', []);
    }

    /**
     * author: GongZiYan
     * Notes: 用户个人信息
     * User: 1250472056@qq.com
     * Date: 2020/7/24
     */
    public function user_list(){
        $user_id = auth('api')->id();
        $list['user'] = User::where('id',$user_id)->first()->toArray();
        $list['data']['discount'] = DiscountInfo::where('user_id',$user_id)->take(3)->get()->toArray();
        $list['data']['parking'] = ParkingInfo::where('user_id',$user_id)->take(3)->get()->toArray();
        $list['data']['buying'] = BuyingInfo::where('user_id',$user_id)->take(3)->get()->toArray();
        return success($list);
    }
}
