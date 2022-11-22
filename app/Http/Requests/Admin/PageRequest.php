<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class PageRequest extends Request
{
    protected $redirectRoute = 'admin.pages.index';

    public function rules()
    {
        $rules = [
            'title'  => 'required',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'title' => trans('admin_labels.title'),
        ];
    }
}