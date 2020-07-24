<?php


namespace App\Services;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Overtrue\EasySms\EasySms;

class SendMsgService
{

    protected $config = [
        // HTTP 请求的超时时间（秒）
        'timeout' => 5.0,

        // 默认发送配置
        'default' => [
            // 网关调用策略，默认：顺序调用
            'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

            // 默认可用的发送网关
            'gateways' => [
                'aliyun',
            ],
        ],
        // 可用的网关配置
        'gateways' => [
            'errorlog' => [
                'file' => '/tmp/easy-sms.log',
            ],
            'aliyun' => [
                'access_key_id' => '',
                'access_key_secret' => '',
                'sign_name' => '轩骏科技',
            ],
        ],
    ];

    /**
     *
     * 发送短信
     * @param $phone string 电话号码
     * @return \Illuminate\Http\JsonResponse
     * @throws \Overtrue\EasySms\Exceptions\InvalidArgumentException
     * @author Aii
     * @date 2020/1/15 下午2:41
     */

    public function sendMsg($phone)
    {
        $code = mt_rand(10000, 99999);
        str_shuffle($code);

        $key = strtoupper(md5($phone . $code));

        try {
            $easySms = new EasySms($this->config);
            $easySms->send($phone, [
                'content' => '您的验证码为: ', $code,
                'template' => '',
                'data' => [
                    'code' => $code
                ],
            ]);
            Cache::put($key, $code, 300);

            return success($code);
        } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
            $message = $exception->getException('aliyun')->getMessage();
            Log::error('send code error',
                ['location' => __METHOD__,
                    'params' => ['code' => $code, 'phone' => $phone],
                    'error' => $message
                ]
            );
            return fail();
        }


    }

    /**
     *验证验证码
     * @param $phone string 手机号码
     * @param $code integer 验证码
     * @return bool
     * @author Aii
     * @date 2020/1/8 下午12:47
     */
    public function verifyCode($phone, $code)
    {
        $key = strtoupper(md5($phone . $code));
        $value = Cache::get($key);
        if (!$value) {
            return false;
        }

        if ($value == $code) {
            return true;
        }
        return false;
    }

}
