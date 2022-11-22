<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class DriverAppSettingRequest extends Request
{
    protected $redirectRoute = 'admin.settings.driverapp.index';

    public function rules()
    {
        $rules = [
        ];

        return $rules;
    }
}