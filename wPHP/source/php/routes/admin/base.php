<?php
Route::namespace('Base')->group(function () {
    //后台登陆
    Route::prefix('me')->group(function () {
        //个人信息
        Route::get('/', 'AuthController@me');
        //退出登陆
        Route::get('logout', 'AuthController@logout');
        //修改个人基本信息
//        Route::put('update', 'MeController@update');
        //修改个人密码
        Route::put('update_password', 'MeController@updatePwd');
        //获取登陆者该有的导航栏
        Route::get('get_nav', 'MeController@getNav');
    });

    /**
     * 配置管理
     */
    Route::get('config', 'ConfigController@index');
    Route::put('config/{id}', 'ConfigController@update');
    Route::post('config', 'ConfigController@store');

    /**
     * 数据权限
     */
    Route::apiResource('permissions', 'PermissionController')->except(['show']);
    //获取权限列表(交集)
    Route::get('permissions/path/list', 'PermissionController@pathList');
    //获取父级权限
    Route::get('permissions/father', 'PermissionController@fatherPath');
    //所有路由
    Route::get('permissions/all', 'PermissionController@pathAll');
    //权限树
    Route::get('permissions/tree', 'PermissionController@tree');

    /**
     * 操作日志
     */
    Route::get('operating_log', 'LogController@operatingLog');
    Route::get('login_log', 'LogController@loginLog');

    /**
     * 角色管理
     */
    Route::apiResource('roles', 'RoleController')->except(['show']);
    //所有角色
    Route::get('roles_all', 'RoleController@getAll');
    /**
     * 管理员管理
     */
    Route::apiResource('administrators', 'AdministratorController')->except(['show']);
    //上传文件
    Route::post('upload', 'UploadController@upload');

});
