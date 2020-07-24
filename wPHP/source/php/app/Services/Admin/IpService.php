<?php


namespace App\Services\Admin;

use App\Helpers\HttpHelper;
use GuzzleHttp\Client;


class IpService
{
    use HttpHelper;

    /**
     * 根据ip获取详细信息
     * @param $ip
     * @return bool
     * @author Aii
     * @date 2019/12/11 下午3:36
     */
    public function getIpInfo($ip)
    {
        try{
            $client = new Client(['timeout'  => 5.0]);
            $response = $client->request('GET', 'http://ip.taobao.com/service/getIpInfo.php', [
                'query' => ['ip' => $ip]
            ]);
            $result = $this->getContentFromResponse($response);
            if (!$result){
                return  false;
            }
            return $result['data'];
        }catch (\Exception $exception){
            return  false;
        }
    }


}
