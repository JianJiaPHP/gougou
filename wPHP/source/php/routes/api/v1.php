<?php
use Illuminate\Support\Facades\Route;
Route::prefix('v1')->namespace('V1')->group(function () {
    Route::get('ping', 'PingController@index');
    //信息列表
    Route::get('discountList', 'InfoController@discountList');//优惠
    Route::post('discountCreate', 'InfoController@discountCreate');//优惠发布提交
    Route::get('buyingList', 'InfoController@buyingList');//团购
    Route::get('parkingList', 'InfoController@parkingList');//车位出租
    //优惠评价
    Route::get('discountEvaluate', 'InfoController@discountEvaluate');
    Route::post('discountEvaluateCreate', 'InfoController@discountEvaluateCreate');

    //获取商品
    Route::get('discountEvaluateGoods', 'InfoController@discountEvaluateGoods');


    //图片上传
    Route::post('upload', 'UploadController@upload');


    #微信第三方
    //第三方登录 获取openid
    Route::post('codeSession','WechatController@codeSession');
    //获取手机号
    Route::post('decryptData','WechatController@decryptData');
    //登录
    Route::post('login','WechatController@login');
    //支付回调
    Route::any('payCallback','WechatController@payCallback');
    #个人中心
    //user详情
    Route::any('user_list','UserController@user_list');
});



