<?php
/**
 * 失败返回
 */
if (!function_exists('fail')) {
    function fail($message = 'fail', $data = null)
    {
        return response()->json([
            'code' => 500,
            'message' => $message,
            'data' => $data
        ], 500);
    }
}

/**
 * 成功返回
 */
if (!function_exists('success')) {
    function success($data = null)
    {
        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $data
        ], 200);
    }
}
/**
 * 根据传入的值判断失败还是成功
 */
if (!function_exists('choose')) {
    function choose($data)
    {
        return $data ? success($data) : fail();
    }
}
/**
 * 其他状态返回
 */
if (!function_exists('otherReturn')) {
    function otherReturn($code, $message, $data = null)
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data
        ], $code);
    }
}

/**
 * 获取配置
 */
if (!function_exists('configs')) {
    function configs($key='*')
    {
        $config_service = app('App\Services\ConfigService');
        if ($key=='*'){
            return $config_service->getAll();
        }else{
            return $config_service->getOne($key);
        }


    }
}

/**
 * 检查签名
 */
if (!function_exists('checkSign')) {
    /**
     * @param \Illuminate\Http\Request $request
     * @param $key
     * @return bool
     * @author Aii
     * @date 2019/12/30 下午1:00
     */
    function checkSign($request,$key)
    {
        $get_sign = $request->header('Yw-Sign');
        $get_time = $request->header('Yw-Time');
        if (empty($get_sign) || empty($get_time)) {
            return false;
        }

        if (abs($get_time - time()) > 600) {
            return false;
        }
        $sign = "";
        $params = $request->all();
        if (empty($params)) {
            $sign = $key . "--" . $key;
        } else {
            ksort($params);
            foreach ($params as $k => $v) {
                $sign .= $key . $k . "--" . $v . $key;
            }
        }
        $sign = strtoupper(md5($sign));

        if ($sign != $get_sign) {
            return false;
        }
        return  true;
    }
}
