<?php
/**
 * Notes:
 * User: 12504
 * Date: 2020/5/25
 * Time: 16:22
 * @return
 */

namespace App\Http\Controllers\Admin\Data;


use App\Http\Controllers\Controller;

use http\Env\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use function GuzzleHttp\Promise\all;

class IndexController extends Controller
{

    /**
     * 数据备份首页
     * @author 田建龙 <864491238@qq.com>
     */
    public function index() {
        $tmp = DB::select('SHOW TABLE STATUS');
        $data['data'] = $tmp;
        $data['total'] = count($tmp);
        return success($data);
    }


    /**
     * 备份数据库
     * @param  String  $ids 表名
     * @param  Integer $id     表ID
     * @param  Integer $start  起始行数
     * @author 田建龙 <864491238@qq.com>
     */
    public function dataBackup() {
        //备份数据库
        $backup = Artisan::call('backup:run ', ['--only-db'=>true]);
//        php artisan backup:run --only-db
        dd($backup);
        //这里注意 参数是以数组的形式
        if ($backup==0) {
            $arr = [
                'error' => 0,
                'msg' => '数据库备份成功'
            ];
        } else {
            $arr = [
                'error' => 1,
                'msg' => '数据库备份失败'
            ];
        }
        return $arr;
    }



    public function downloadZip(Request $request)
    {
        $filename = $request->route('filename');
        $disk = Storage::disk('local');
        $directory = '/edu-data-backup';
        $exists = $disk->exists($directory.'/'.$filename);
        if (!$exists) {
            $arr = [
                'error' => 1,
                'msg' => '文件不存在'
            ];
        }
        //完整路径下载
        return response()->download(public_path().'/uploads'.$directory.'/'.$filename);
    }

    /**
     * 优化表
     * @param  String $ids 表名
     */
    public function optimize() {

        $tables = \request()->get('tables');
        $tables = explode(',',$tables);
        foreach ($tables as $k =>$v){
            $list = DB::select("OPTIMIZE TABLE `$v`");
        }
       if($list){
            return success("数据表优化完成！");
       } else {
           return fail("数据表优化出错请重试！");
       }
    }



    /**
     * 修复表
     * @param  String $ids 表名
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function repair() {
        $tables = \request()->get('tables');
        $tables = explode(',',$tables);
        foreach ($tables as $k =>$v){
            $list = DB::select("REPAIR TABLE `$v`");
        }
         if($list){
             return success("数据表修复完成！");
         } else {
             return fail("数据表修复出错请重试！");
         }
    }




    /**
     * 还原数据库
     * @param 类型 参数 参数说明
     * @author staitc7 <static7@qq.com>
     */

    public function import() {
        //列出备份文件列表
        $path_tmp = Config::get('data_backup_path');
        is_dir($path_tmp) || mkdir($path_tmp, 0755, true);
        $path = realpath($path_tmp);
        $flag = \FilesystemIterator::KEY_AS_FILENAME;
        $glob = new \FilesystemIterator($path, $flag);
        $list = array();
        foreach ($glob as $name => $file) {
            if (preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql(?:\.gz)?$/', $name)) {
                $name = sscanf($name, '%4s%2s%2s-%2s%2s%2s-%d');
                $date = "{$name[0]}-{$name[1]}-{$name[2]}";
                $time = "{$name[3]}:{$name[4]}:{$name[5]}";
                $part = $name[6];
                if (isset($list["{$date} {$time}"])) {
                    $info = $list["{$date} {$time}"];
                    $info['part'] = max($info['part'], $part);
                    $info['size'] = $info['size'] + $file->getSize();
                } else {
                    $info['part'] = $part;
                    $info['size'] = $file->getSize();
                }
                $extension = strtoupper(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
                $info['compress'] = ($extension === 'SQL') ? '-' : $extension;
                $info['time'] = strtotime("{$date} {$time}");
                $list["{$date} {$time}"] = $info;
            }
        }
        $value['data'] = $list;
        $this->view->metaTitle = '数据还原';
        return $this->view->assign($value ?: null)->fetch();
    }

    /**
     * 删除备份文件
     * @param  Integer $time 备份时间
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function del($time = 0) {
        empty($time) && $this->error('参数错误！');
        $name = date('Ymd-His', $time) . '-*.sql*';
        $path = realpath(Config::get('data_backup_path')) . DIRECTORY_SEPARATOR . $name;
        array_map("unlink", glob($path));
        if (count(glob($path))) {
            writelog(session('uid'),session('username'),'备份文件删除失败',2);
            return $this->error('备份文件删除失败，请检查权限！');
        } else {
            writelog(session('uid'),session('username'),'备份文件删除成功',1);
            return $this->success('备份文件删除成功！');
        }
    }

    /**
     * 还原数据库
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function revert($time = 0, $part = null, $start = null) {
        if (is_numeric($time) && is_null($part) && is_null($start)) { //初始化
            writelog(session('uid'),session('username'),'还原数据库成功',1);
            //获取备份文件信息
            $name = date('Ymd-His', $time) . '-*.sql*';
            $path = realpath(Config::get('data_backup_path')) . DIRECTORY_SEPARATOR . $name;
            $files = glob($path);
            $list = [];
            foreach ($files as $name) {
                $basename = basename($name);
                $match = sscanf($basename, '%4s%2s%2s-%2s%2s%2s-%d');
                $gz = preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql.gz$/', $basename);
                $list[$match[6]] = array($match[6], $name, $gz);
            }
            ksort($list);
            $last = end($list);//检测文件正确性
            if (count($list) === $last[0]) {
                Session::set('backup_list', $list); //缓存备份列表
                return $this->success('初始化完成,请等待！', '', ['part' => 1, 'start' => 0]);
            } else {
                return $this->error('备份文件可能已经损坏，请检查！');
            }
        } elseif (is_numeric($part) && is_numeric($start)) {
            $list = Session::get('backup_list');
            $db = new \com\Database($list[$part], [
                    'path' => realpath(Config::get('data_backup_path')) . DIRECTORY_SEPARATOR,
                    'compress' => $list[$part][2]
                ]
            );
            $start = $db->import($start);
            if (false === $start) {
                return $this->error('还原数据出错！');
            } elseif (0 === $start) { //下一卷
                if (isset($list[++$part])) {
                    $data = array('part' => $part, 'start' => 0);
                    $this->success("正在还原...#{$part}", '', $data);
                } else {
                    Session::set('backup_list', null);
                    $this->success('数据库还原完成！');
                }
            } else {
                $data = array('part' => $part, 'start' => $start[0]);
                if ($start[1]) {
                    $rate = floor(100 * ($start[0] / $start[1]));
                    return $this->success("正在还原...#{$part} ({$rate}%)", '', $data);
                } else {
                    $data['gz'] = 1;
                    return $this->success("正在还原...#{$part}", '', $data);
                }
            }
        } else {
            return $this->error('参数错误！');
        }
    }

}
