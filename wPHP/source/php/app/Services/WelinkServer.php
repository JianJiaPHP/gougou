<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class WelinkServer
{




    /**
     *  1 welink 获取access_token
     *
     * @return  access_token
     */
    public function post_access_token(){
        $durl = 'https://open.welink.huaweicloud.com/api/auth/v2/tickets';
        $post_data = config('welink');
        // header传送格式
        $headers =array("Content-type:application/json;","Accept:application/json");

        $data = self::curl_file_post_contents($durl,$post_data,$headers);
        if($data['code'] == 0){
            return $data['access_token'];
        }
        return false;
    }





    /**
     *  2 welink 获取userId
     *  $code
     * @return  userId  用户帐号
     *          tenantId  租户id
     */
    public function get_userId($code,$access_token){
        $durl = 'https://open.welink.huaweicloud.com/api/auth/v2/userid?code='.$code;

        $headers=array("x-wlk-Authorization:".$access_token);

        $data = self::curl_file_get_contents($durl,$headers);

        $ret = [];
        if($data['code'] == 0){
            $ret = ['status'=>100,'msg'=>'获取成功','data'=>['userId'=>$data['userId'],'tenantId'=>$data['tenantId']]];
        }
        if($data['code'] != 0){
            $ret = ['status'=>101,'msg'=>$data['message'],'data'=>['userId'=>'','tenantId'=>'']];
        }
        return  $ret;
    }


    /**
     *  3 welink 获取用户详细信息
     * @param   $userId
     * @return  $data{
    "code": "0",        数据正常返回“0”，如果发生错误，会返回对应的错误码。
    "message": "ok",    返回信息，包括接口请求发生错误时的详细信息。
    "userStatus": "1",  状态, 1：未开户2：开户中3：已开户4已销户
    "userId": "zhangshan2@welink",  用户帐号, Key值, 必填
    "deptCode": "10001",        部门Id, Key值, 必填
    "mobileNumber": "+86-15811847236",  绑定手机号码, 必填
    "userNameCn": "张三",         用户中文名称, 必填
    "userNameEn": "zhangshan",      用户英文名称, 必填
    "sex": "M",                 	性别, 仅：M/F, M: 男, F: 女, 必填
    "corpUserId": "36188",          该用户在租户自身系统的登录标识，用于认证和邮箱登录（客户内唯一）（集成用的字段，如果在开户时没有维护则为空）
    "userEmail": "zhangshan4@126.com",  用户邮箱, 必填
    "secretary": "zhangshan@welink",    秘书（用户帐号）
    "phoneNumber": "0755-88888888",     电话号码（座机）
    "address": "广东省深圳",            地址
    "remark": "欢迎加入WeLink",     	备注
    "isActivated": 1,               用户是否已激活(用户登陆WeLink客户端表示已激活), 1为已激活，0为未激活
    "creationTime": "2018-05-03 13:58:02",      创建时间
    "lastUpdatedTime": "2018-05-03 13:58:02"    最后更新时间
     */
    public function get_userinfo($userId,$access_token){
        $durl = 'https://open.welink.huaweicloud.com/api/contact/v1/users?userId='.$userId;

        $headers=array("x-wlk-Authorization:".$access_token);

        $data = self::curl_file_get_contents($durl,$headers);
        $ret = [];
        if($data['code'] == 0){
            $ret = ['status'=>100,'msg'=>'获取成功','data'=>$data];
        }
        if($data['code'] != 0){
            $ret = ['status'=>101,'msg'=>$data['message'],'data'=>[]];
        }
        return   $ret;
    }


    /**
     *  4 welink 推送消息
     * $access_token    token
     * $param   toUserList  array     要推送的用户  如 ["john@welink", "john@1234"] 数组格式
     *          msgTitle    string    标题
     *          msgContent  string    推送内容描述
     *          urlType     String	  链接类型定义，如"html",则可跳转到http://url地址。
     *          urlPath     String	  点击后跳转的链接，如需要跳转到微码，参考推送消息实现免登。
     *          msgOwner    String	  消息所有者，如“差旅管理”。
     *          createTime  String	  消息创建时间，可不传系统将自动生成推送时间。
     */
    public function push($post_data,$access_token){
        $durl = 'https://open.welink.huaweicloud.com/api/messages/v3/send';
        $headers =array("Content-type:application/json;","x-wlk-Authorization:".$access_token);
        $data = self::curl_file_post_contents($durl,$post_data,$headers);
        $ret = [];
//        if($data['code'] == 0){
//            $ret = ['status'=>100,'msg'=>'获取成功','data'=>['userId'=>$data['userId'],'tenantId'=>$data['tenantId']]];
//        }
//        if($data['code'] != 0){
//            $ret = ['status'=>101,'msg'=>$data['message'],'data'=>['userId'=>'','tenantId'=>'']];
//        }
        return  $data;
    }



    /*
  *   php访问url路径，post请求
  *
  *   durl   路径url
  *   post_data   array()   post参数数据
   *  headers  传送格式
  */
    private  static function curl_file_post_contents($durl, $post_data,$headers=[]){
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $durl);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, false);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, true);

        // 设置post请求参数
        curl_setopt($curl, CURLOPT_POSTFIELDS,json_encode($post_data) );
        // 添加头信息
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        // CURLINFO_HEADER_OUT选项可以拿到请求头信息
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        // 不验证SSL
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        //执行命令
        $data = curl_exec($curl);
        // 打印请求头信息
//        echo curl_getinfo($curl, CURLINFO_HEADER_OUT);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        return json_decode($data,true);
    }



    /*
    *  php访问url路径，get请求
    * // header传送格式
    */
    private static function curl_file_get_contents($durl,$headers=[]){
        // header传送格式
        // 初始化
        $curl = curl_init();
        // 设置url路径
        curl_setopt($curl, CURLOPT_URL, $durl);
        // 将 curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true) ;
        // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, true) ;
        // 添加头信息
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        // CURLINFO_HEADER_OUT选项可以拿到请求头信息
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        // 不验证SSL
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        // 执行
        $data = curl_exec($curl);
        // 打印请求头信息
//        echo curl_getinfo($curl, CURLINFO_HEADER_OUT);
        // 关闭连接
        curl_close($curl);
        // 返回数据
        return json_decode($data,true);
    }

    /**
     * author: GongZiYan
     * Notes: 判断该用户是否为BOSS
     * User: 1250472056@qq.com
     * Date: 2020/5/27
     */
    public static function thisBoss($phone){
        return DB::table('boss')->where('id',1)->where('phone',$phone)->first();
    }

}
