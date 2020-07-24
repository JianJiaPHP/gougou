<?php


namespace App\Http\Controllers\Admin\Base;


use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MePwdRequests;
use App\Http\Requests\Admin\MeUpdateRequests;
use App\Models\Administrator;
use Illuminate\Support\Facades\Hash;

class MeController extends Controller
{

    /**
     * 修改自己的基本信息
     * @param MeUpdateRequests $meUpdateRequests
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     * @date 2019/12/11 下午2:45
     */
    public function update(MeUpdateRequests $meUpdateRequests)
    {
        $id = auth('admin')->id();
        $params = $meUpdateRequests->all();
        $result = Administrator::whereId($id)->update([
            'avatar' => $params['avatar'],
            'account' => $params['account'],
            'nickname' => $params['nickname']
        ]);
        return choose($result);
    }

    /**
     * 修改密码
     * @param MePwdRequests $mePwdRequests
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     * @date 2019/12/11 下午2:54
     */
    public function updatePwd(MePwdRequests $mePwdRequests)
    {
        try {
            $user = auth('admin')->user();
            $params = $mePwdRequests->validated();
            if (!Hash::check(md5($params['old_password']), $user['password'])) {
                throw new \Exception("原密码错误");
            }
            $password = Hash::make(md5($params['password']));
            $result = Administrator::where('id',$user['id'])->update([
                'password'=>$password
            ]);
            if (!$result){
                throw new \Exception("请求超时");
            }
            return success();
        } catch (\Exception $exception) {
            return fail($exception->getMessage());
        }

    }
    /**
     * 获取菜单
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     * @date 2019/12/13 下午3:16
     */
    public function getNav()
    {
        $data = app('App\Services\Admin\PermissionService')->getNav();
        return success($data);
    }

}
