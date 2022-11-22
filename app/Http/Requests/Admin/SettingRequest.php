<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class SettingRequest extends Request
{
    protected $redirectRoute = 'admin.settings.index';

    public function rules()
    {
        $rules = [
//            'price_catalog'  => 'required',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'title' => trans('admin_labels.price_catalog'),
        ];
    }
}