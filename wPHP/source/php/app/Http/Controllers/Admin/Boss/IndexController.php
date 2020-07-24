<?php
/**
 * Notes:
 * User: 12504
 * Date: 2020/5/25
 * Time: 16:22
 * @return
 */

namespace App\Http\Controllers\Admin\Boss;


use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BossRequests;
use App\Models\Boss;

use function GuzzleHttp\Promise\all;

class IndexController extends Controller
{

    public $model;
    public function __construct(Boss $boss)
    {
        $this->model = $boss;
    }

    /**
     * author: GongZiYan
     * Notes: 领导信息
     * User: 1250472056@qq.com
     * Date: 2020/5/26
     */
    public function index(){
        $find = $this->model->where('id',1)->first();
        return success($find);
    }

    /**
     * author: GongZiYan
     * Notes: 修改
     * User: 1250472056@qq.com
     * Date: 2020/5/26
     */
    public function submit($id){
        $params = request()->all();
        $update = $this->model->where('id',$id)->update($params);
        return choose($update);
    }


    public function form_submit(BossRequests $requests)
    {
        dd($requests->validated());
        $params = request()->validate('rules')->all();
        $aa = BossRequests::rules($params);
        dd($aa);
    }
}
