<?php


namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Models\AdminLoginLog;
use App\Models\BuyingInfo;
use App\Models\DiscountInfo;
use App\Models\Evaluate;
use App\Models\ParkingInfo;
use App\Models\RepairUser;
use App\Models\User;
use App\Services\MiniProgramService;
use App\Services\PayService;
use Illuminate\Database\Eloquent\Builder;


class WechatController extends Controller
{



    /**
     * author: GongZiYan
     * Notes: 获取手机号
     * User: 1250472056@qq.com
     * Date: 2020/7/7
     */
    public function decryptData(MiniProgramService $ServiceMini){
        $params = request()->all();
        $result = $ServiceMini->decryptData($params['sessionKey'], $params['iv'], $params['encryptedData']);
        return success($result);
    }
    /**
     * author: GongZiYan
     * Notes: 根据前端微信code获取openid
     * User: 1250472056@qq.com
     * Date: 2020/7/7
     */
    public function codeSession(MiniProgramService $ServiceMini){
        $params = request()->all();
        $Openid = $ServiceMini->codeSession($params['code']);
        if ($Openid){
            $where['openid'] = $Openid['openid'];
            $adminList = User::where($where)->first();
            if ($adminList){
//                if ($adminList->is_ban == 2){
//                    return fail("该账户被禁用");
//                }
                $token = auth('api')->login($adminList);
                if (!$token) {
                    return fail();
                }
                User::where($where)->update(['openid'=>$Openid['openid']]);
                $reData['data'] =  $adminList;
                $reData['token'] =  $token;
                $reData['code'] =  200;
                return success($reData);
            }else{
                $reData['data'] =  $Openid;
                $reData['code'] =  100;
                return success($reData);
            }
        }


        return fail('获取失败');
    }

    /**
     * author: GongZiYan
     * Notes: 根据手机号登录
     * @param phone 手机号
     * @param openid
     * @param nickname
     * @param img
     * User: 1250472056@qq.com
     * Date: 2020/7/7
     */
    public function login(){
        $params = request()->all();
        //判断库里面是否有该用户 并且是维保人员
        $where['phone'] = $params['phone'];
        $adminList = User::where($where)->first();
        if ($adminList){
            //如果有账号
            if ($adminList->is_ban == 2){
                return fail("该账户被禁用");
            }
            $token = auth('api')->login($adminList);
            if (!$token) {
                return fail();
            }
            //有账户->登录->保存openid
            User::where($where)->update(['openid'=>$params['openid'],'token'=>$token]);
            $reData['data'] = $adminList;
            $reData['token'] = $token;
            return success($reData);
        }
        //新增//
        $data = [
            'openid'=>$params['openid'],
            'phone'=>$params['phone'],
            'name'=>$params['name']?$params['name']:"",
            'img'=>$params['img']?$params['img']:"",
        ];
        $repair_user = User::create($data);
        $token = auth('api')->login($repair_user);
        if (!$token) {
            return fail();
        }
        User::where($where)->update(['token'=>$token]);
        $reData['data'] = $repair_user;
        $reData['token'] = $token;
        return success($reData);
    }
    /**
     * author: GongZiYan
     * Notes: 支付回调
     * User: 1250472056@qq.com
     * Date: 2020/7/23
     * @param PayService $payService
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \EasyWeChat\Kernel\Exceptions\Exception
     */
    public function payCallback(PayService $payService){
        return $payService->payCallback();
    }
}
