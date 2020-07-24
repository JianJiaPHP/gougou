<?php
namespace App\Services;

use App\Models\Apply;

class ApplyServer
{
    /**
     * author: GongZiYan
     * Notes: 判断指定天申请剩余是否够
     * User: 1250472056@qq.com
     * Date: 2020/5/27
     * @param $getTime // 指定时间
     */
    public static function readNingCount($getTime){
        $count =  Apply::where('status',1)->whereDate('created_at', $getTime)->get()->count();//当前预约人数
        $Remaining= configs('day_num')-$count; //剩余可预约人数
        if ($Remaining>0){
            return 1;
        }else{
            return 0;
        }
    }
}
