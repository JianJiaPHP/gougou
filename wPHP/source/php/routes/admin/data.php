<?php
/**
 * Notes: 后台申请列表
 * User: 12504
 * Date: 2020/5/25
 * Time: 16:24
 * ${PARAM_DOC}
 * @return ${TYPE_HINT}
 * ${THROWS_DOC}
 */
use Illuminate\Support\Facades\Route;
Route::namespace('Data')->group(function () {

    Route::prefix('data')->group(function () {
        //列表
        Route::get('index', 'IndexController@index');
        Route::get('optimize', 'IndexController@optimize');//优化表
        Route::get('repair', 'IndexController@repair');//修复表
        Route::get('dataBackup', 'IndexController@dataBackup');
        Route::get('downloadZip', 'IndexController@downloadZip');
    });

});

