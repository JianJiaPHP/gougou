<?php
/**
 * Notes:
 * User: 12504
 * Date: 2020/5/25
 * Time: 16:22
 * @return
 */

namespace App\Http\Controllers\Admin\Apply;


use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequests;
use App\Models\Administrator;
use App\Models\Apply;
use App\Models\User;
use App\Services\ApplyServer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use function GuzzleHttp\Promise\all;

class IndexController extends Controller
{

    public $model;
    public function __construct(Apply $apply)
    {
        $this->model = $apply;
    }

    /**
     * author: GongZiYan
     * Notes: 申请列表
     * User: 1250472056@qq.com
     * Date: 2020/5/26
     */
    public function index(){
        $params =  request()->all();
        $keyword = $params['keyword'];  //关键字
        $appointment_startTime = $params['appointment_startTime']; //预约时间区间
        $created_at = $params['created_at']; //预约时间区间

        //关联查询 模糊查询关键字
        $list = $this->model;
        if (!empty($appointment_startTime)){
            $list = $list->whereBetween('appointment_startTime',$appointment_startTime);
        }
        if (!empty($created_at)){
            $list = $list->whereDate('created_at',$created_at);
        }
//        $list->when($appointment_startTime, function ($query, $appointment_startTime) {
//            return $query->whereBetween('appointment_startTime',$appointment_startTime);
//        });
        if (!empty($keyword)){
            $list =  $list->where('name', 'like', "%$keyword%");
        }
//        $list->when($keyword, function ($query, $keyword) {
//            return $query->where('name', 'like', "%$keyword%");
//        });
        $list = $list ->orderBy('sort','desc');

        $list = $list ->orderBy('created_at','desc')
            ->paginate()->toArray();

        return success($list);
    }


    /**
     * author: GongZiYan
     * Notes: （后台）添加申请
     * User: 1250472056@qq.com
     * Date: 2020/5/26
     */
    public function createSubmit(){
        $params = request()->all();
//        $params['appointment_startTime'] = $params['startTimeList'][0];
//        $params['appointment_endTime'] = $params['startTimeList'][1];
        $res = ApplyServer::readNingCount(Carbon::now()->toDateString());
        if ($res!=1){
            return fail("当日预约人数已上限！");
        }
        unset($params['startTimeList']);
        $create = $this->model->create($params);
        return choose($create);
    }


    /**
     * author: GongZiYan
     * Notes: 修改申请预约表
     * User: 1250472056@qq.com
     * Date: 2020/5/26
     */
    public function updateSubmit($id){
        $params = request()->all();
        $data['reportingTime'] = $params['reportingTime'];//汇报时长
        $data['sort'] = $params['sort']; //权重
//        $data['appointment_startTime'] = $params['startTimeList'][0]; //预约开始时间
        $res = ApplyServer::readNingCount(Carbon::now()->toDateString());
        if ($res!=1){
            return fail("当日预约人数已上限！");
        }
//        $data['appointment_endTime'] = $params['startTimeList'][1]; //预约结束时间
        $data['thing'] = $params['thing']; //汇报事由
        $data['name'] = $params['name']; //申请人
        //修改
        $res = $this->model->where('id',$id)->update($data);
        return choose($res);
    }

    /**
     * author: GongZiYan
     * Notes: 移除状态
     * User: 1250472056@qq.com
     * Date: 2020/5/26
     */
    public function updateStatus($id,$status){
//        $res =$this->model->where('id',$id)->update(compact('status'));
        $res =Apply::destroy($id);
        return choose($res);
    }


    /**index_frist
     * author: GongZiYan
     * Notes: 获取首页信息
     * User: 1250472056@qq.com
     * Date: 2020/5/29
     */
    public function index_frist(){
        $data['count'] =  Apply::where('status',1)->whereDate('created_at', Carbon::now()->toDateString())->get()->count();//当前预约人数
        $data['Remaining']= configs('day_num')- $data['count']; //剩余可预约人数
        $data['count_start'] =  Apply::where('status',1)->whereDate('appointment_startTime', time())->get()->count();//今日已分配
        $data['sum'] =  Apply::where('status',1)->get()->count();//总申请次数
        return success($data);
    }
}
