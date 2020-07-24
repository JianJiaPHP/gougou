<?php

namespace App\Http\Controllers\Admin\Base;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequests;
use App\Models\Administrator;
use App\Models\AdminLoginLog;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin', ['except' => ['login']]);
    }

    /**
     * 登录
     * @param LoginRequests $loginRequests
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     * @date 2019/12/11 下午2:09
     */
    public function login(LoginRequests $loginRequests)
    {
        $params = $loginRequests->validated();
        $exist = Administrator::where('account', $params['account'])->first();
        if (!$exist) {
            return fail("账号不存在");
        }
        if (!Hash::check($params['password'], $exist->password)) {
            return fail("密码错误");
        }

        $token = auth('admin')->login($exist);
        if (!$token) {
            return fail();
        }
        $exist->update(['token' => $token]);
        $result = app('App\Services\Admin\IpService')->getIpInfo(\request()->getClientIp());
        if ($result) {
            AdminLoginLog::create([
                'uid' => $exist->id,
                'ip' => $result['ip'],
                'country' => $result['country'],
                'region' => $result['region'],
                'city' => $result['city'],
                'county' => $result['county'],
                'isp' => $result['isp'],
            ]);
        }

        return success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('admin')->factory()->getTTL() * 60
        ]);
    }

    /**
     * 查看个人信息
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     * @date 2019/12/11 下午12:06
     */
    public function me()
    {
        $user = auth('admin')->user();
        $data = [
            'id' => $user->id,
            'account' => $user->account,
            'nickname' => $user->nickname,
            'avatar' => $user->avatar,
        ];

        return success($data);
    }

    /**
     * 退出登录
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     * @date 2019/12/11 下午12:07
     */
    public function logout()
    {
        auth('admin')->logout();
        return success();
    }

}
