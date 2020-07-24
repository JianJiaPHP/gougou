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
Route::namespace('Apply')->group(function () {

    Route::prefix('index')->group(function () {
        //列表
        Route::get('index', 'IndexController@index');
        //修改状态
        Route::patch('updateStatus/{id}/{status}', 'IndexController@updateStatus');
        //修改预约
        Route::patch('updateSubmit/{id}', 'IndexController@updateSubmit');
        //添加
        Route::post('createSubmit', 'IndexController@createSubmit');
        //首页数据
        Route::get('index_frist', 'IndexController@index_frist');
    });

});

