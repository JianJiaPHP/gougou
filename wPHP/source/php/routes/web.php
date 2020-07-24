<?php
Route::prefix('yw_admin')->namespace('Admin\Base')->group(function () {
    //后台登陆
    Route::post('login', 'AuthController@login');
    //获取配置
    Route::get('get_config/{key}', 'ConfigController@getOne');
});

Route::get('/', function () {
    $name = configs('admin_name');
    $record = configs('record');
    return view('index',compact('name','record'));
});
