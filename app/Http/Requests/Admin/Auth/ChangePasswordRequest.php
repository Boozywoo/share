<?php

namespace App\Http\Requests\Admin\Auth;

use App\Http\Requests\Request;
use App\Traits\ClearPhone;

class ChangePasswordRequest extends Request
{
    use ClearPhone;

    public function rules()
    {
        $rules = [
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'old_password' => trans('admin_labels.old_password'),
            'new_password' => trans('admin_labels.new_password'),
        ];
    }

    public function messages()
    {
        return [
            'required' => trans('validation.index.required'),
            'new_password.min' => trans('validation.index.custom.hard_password'),
        ];
    }
}