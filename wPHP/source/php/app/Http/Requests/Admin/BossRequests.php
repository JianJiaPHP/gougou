<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class BossRequests extends BaseRequest
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
            'account' => ['required'],
            'password' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'account.required'    => '请输入账号',
            'password.required'    => '请输入密码',
        ];
    }
}
