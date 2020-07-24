<?php


namespace App\Services;


use Illuminate\Support\Facades\Storage;

class UploadService
{
    public function upload($file, $drive = 'oss')
    {
        $path = 'yilin/'.date('Ym/d');
        $disk = Storage::disk($drive);
        $path = $disk->put($path, $file);
        switch ($drive) {
            case 'oss':
                return $disk->url($path);
        }
    }

    public function deleteFile(string $path)
    {
        $path = explode('.com/', $path);
        $result = false;
        if (Storage::exists($path[1])) {
            $result = Storage::delete($path[1]);
        }

        return $result;
    }

}
