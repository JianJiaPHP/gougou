<?php


namespace App\Http\Controllers\Admin\Base;


use App\Http\Controllers\Controller;
use App\Models\Administrator;
use Illuminate\Support\Facades\Hash;

class AdministratorController extends Controller
{
    /**
     * 管理员列表
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     */
    public function index()
    {
        $params = request()->all();

        $data = Administrator::orderBy('created_at','desc')
            ->where('id','>',1);

        $keyword = $params['keyword'];
        $data->when($keyword, function ($query, $keyword) {
            return $query->where('nickname', 'like', "%$keyword%")
                ->orWhere('account', 'like', "%$keyword%");
        });

        $result = $data->select('id','account','nickname','avatar','role_id','created_at')
                ->paginate(request()->get('limit',15));

        return success($result);
    }
    /**
     * 添加管理员
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     */
    public function store()
    {
        $params = request()->all();
        $exist = Administrator::where('account',$params['account'])->first();
        if ($exist){
            return  fail('该账号已存在');
        }
        $md5_password = md5($params['password']);
        $params['password'] = Hash::make($md5_password);
        $result = Administrator::create($params);
        return choose($result);
    }

    /**
     * 管理更新
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     */
    public function update($id)
    {
        $params = request()->all();
        $md5_password = md5($params['password']);
        $params['password'] = Hash::make($md5_password);
        $result = Administrator::where('id',$id)->update($params);
        return choose($result);
    }

    /**
     * 管理员删除
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     */
    public function destroy($id)
    {
        $id = explode(',',$id);
        $result = Administrator::destroy($id);
        return choose($result);
    }


}
