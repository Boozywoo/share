<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class SmsConfigRequest extends Request
{
    protected $redirectRoute = 'admin.settings.smscongif.edit';

    public function rules()
    {
        $rules = [
            'sms_send'  => 'required',
            'sms_sender'  => 'required',
            'sms_api_login'  => 'required',
            'sms_api_password'  => 'required',
        ];

        return $rules;
    }
}