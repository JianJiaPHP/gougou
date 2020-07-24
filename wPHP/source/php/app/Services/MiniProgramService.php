<?php


namespace App\Services;


use EasyWeChat\Factory;

class MiniProgramService
{
    protected $app;

    public function __construct()
    {
        $this->app = Factory::miniProgram(config('wechat.mini_program.default'));
    }


    /**
     *   * 授权登陆
     * @param $code string 小程序jscode
     * @return array|bool|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @author Aii
     * @date 2020/1/8 下午3:15
     */
    public function codeSession($code)
    {
        $result =  $this->app->auth->session($code);
        if (!empty($result['errcode'])){
            return false;

        }

        return $result;
    }

    public function code()
    {
        return  $this->app->app_code->get(public_path('/'));
    }
    /**
     * 微信小程序消息解密
     * @param $sessionKey
     * @param $iv
     * @param $encryptedData
     * @return array|bool
     * @throws \EasyWeChat\Kernel\Exceptions\DecryptException
     * @author Aii
     * @date 2020/5/6 下午5:30
     */
    public function decryptData($sessionKey, $iv, $encryptedData)
    {
        $result = $this->app->encryptor->decryptData($sessionKey, $iv, $encryptedData);
        if (!empty($result['errcode'])) {
            return false;
        }
        return $result;
    }
    /**
     * 获取所有客服
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @author Aii
     * @date 2020/3/5 下午2:16
     */
    public function customerService()
    {
        return  $this->app->customer_service->list();
    }
}
