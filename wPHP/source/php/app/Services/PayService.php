<?php


namespace App\Services;


use Carbon\Carbon;
use EasyWeChat\Factory;
use Illuminate\Support\Facades\Log;

class PayService
{

    protected $app;

    public function __construct()
    {
        $this->app = Factory::payment(config('wechat.payment.default'));
    }

    /**
     * 微信支付回调
     * @throws \EasyWeChat\Kernel\Exceptions\Exception
     * @author Aii
     * @date 2020/1/7 下午5:06
     */
    public function payCallback()
    {
        $response = $this->app->handlePaidNotify(function ($message, $fail) {
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $res = $this->app->order->queryByOutTradeNumber($message['transaction_id']);

            if ($order->pay_time) { // 如果订单不存在 或者 订单已经支付过了
                return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }
            ///【订单查询】接口查一下该笔订单的情况，确认是已经支付

            if ($message['return_code'] === 'SUCCESS' && $res) { // return_code 表示通信状态，不代表支付状态
                // 用户是否支付成功
                if ($message['result_code'] === 'SUCCESS') {

                    // 用户支付失败
                } elseif ($message['result_code'] === 'FAIL') {
                    Log::error(
                        'pay error',
                        [
                            'location' => __METHOD__,
                            'params' => $message,
                            'error' => '支付失败'
                        ]
                    );

                }
            } else {
                Log::error(
                    'pay error',
                    [
                        'location' => __METHOD__,
                        'params' => $message,
                        'error' => '支付失败'
                    ]
                );
                return $fail('通信失败，请稍后再通知我');
            }
            // 保存订单

            return true; // 返回处理完成
        });

        return $response; // return $response;
    }

    /**
     * 统一下单
     * @param string $body 下单内容
     * @param string $out_trade_no 订单号
     * @param float $total_fee 下单价格（元）
     * @param string $openid
     * @param string $trade_type 交易类型
     * @return array|bool|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author Aii
     * @date 2020/1/14 下午3:57
     */
    public function pay($body, $out_trade_no, $total_fee, $openid, $trade_type = 'JSAPI')
    {
        $result = $this->app->order->unify([
            'body' => $body,
            'out_trade_no' => $out_trade_no,
            //TODO 金额
            'total_fee' => bcmul($total_fee, 100),
            // 'total_fee' => 1,
            'trade_type' => $trade_type, // 请对应换成你的支付方式对应的值类型
            'openid' => $openid,
            'notify_url' => route('pay_callback')
        ]);

        if ($result['return_code'] == 'SUCCESS') {
            if ($result['result_code'] === 'FAIL') {
                return false;
            }
        }

        return $result;
    }




    /**
     * 订单退款
     * @param $transactionId string 微信订单好
     * @param $refundNumber string 用户订单退款号
     * @param $totalFee int 订单金额（分）
     * @param $refundFee int 退款金额（分）
     * @param array $config
     * @return bool
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @author Aii
     * @date 2020/1/14 下午4:52
     */
    public function refund(
        $transactionId,
        $refundNumber,
        $totalFee,
        $refundFee,
        $config = []
    )
    {
        $result = $this->app->refund->byTransactionId(
            $transactionId,
            $refundNumber,
            $totalFee,
            $refundFee,
            $config
        );

        if ($result['return_code'] == 'SUCCESS') {
            if ($result['result_code'] === 'FAIL') {
                Log::error(
                    'pay error',
                    [
                        'location' => __METHOD__,
                        'params' => $result,
                        'error' => '退款失败'
                    ]
                );
                return false;
            }
        }
        return $result ? true : false;
    }
}
