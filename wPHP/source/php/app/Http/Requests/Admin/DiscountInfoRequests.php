<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class DiscountInfoRequests extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required'],
            'way' => ['required'],
            'imgs' => ['required'],
            'phone' => ['required'],
            'position' => ['required'],
            'state' => ['required'],
            'goods' => ['required'],
            'content' => ['required'],
            'event_time' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'name.required'    => '请输入商品名',
            'way.required'    => '请输入优惠',
            'imgs.required'    => '请上传图片',
            'phone.required'    => '请输入手机号',
            'position.required'    => '请输入地址',
            'state.required'    => '请输入类型状态',
            'goods.required'    => '请选择发布类型',
            'content.required'    => '请输入优惠内容',
            'event_time.required'    => '请输入活动截止时间',
        ];
    }
}
