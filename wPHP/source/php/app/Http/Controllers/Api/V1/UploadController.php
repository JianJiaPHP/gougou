<?php


namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Services\UploadService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;


class UploadController extends Controller
{
    /**
     * 上传文件
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     * @date 2019/12/23 下午2:38
     */
    public function upload(Request $request,UploadService $uploadService)
    {
        $file = $request->file('file');
        $path = $uploadService->upload($file);
        return success($path);
    }
}
