<?php


namespace App\Http\Controllers\Admin\Base;


use App\Http\Controllers\Controller;
use App\Models\Config;

class ConfigController extends Controller
{


    protected $config;

    public function __construct()
    {
        $this->middleware('admin', ['except' => ['getOne']]);
        $this->config = new Config;
    }

    /**
     * 获取所有的配置信息
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     * @date 2019/12/13 下午3:22
     */
    public function index()
    {
        $params = request()->all();
        $keyword = $params['keyword'];
        $data = $this->config->orderBy('created_at', 'desc');
        $data->when($keyword, function ($query, $keyword) {
            return $query->where('key', 'like', "%$keyword%")
                ->orWhere('value', 'like', "%$keyword%")
                ->orWhere('desc', 'like', "%$keyword%");
        });

        $result = $data->paginate(request()->get('limit', 15));

        return success($result);
    }


    /**
     * 修改配置信息
     * @param $id int 配置id
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     * @date 2019/12/13 下午3:24
     */
    public function update($id)
    {
        $params = request()->all();

        $result = $this->config->updateOrUpdate($params, $id);

        return choose($result);
    }

    /**
     * 添加配置
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     * @date 2019/12/19 上午10:40
     */
    public function store()
    {
        $params = request()->all();

        $result = $this->config->updateOrUpdate($params);

        return choose($result);
    }

    /**
     * 根据key值获取
     * @param $key
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     * @date 2020/1/17 上午10:53
     */
    public function getOne($key)
    {
        return success(configs($key));
    }

}
