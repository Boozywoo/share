<?php

namespace App\Http\Requests\Admin\Auth;

use App\Http\Requests\Request;
use App\Models\User;
use Illuminate\Validation\Rule;

class LoginRequest extends Request
{
    public function rules()
    {
        return [
            'email' => [
                'required',
                'email',
                Rule::exists('users')->where(function ($query) {
                    $query->where('status', User::STATUS_ACTIVE);
                }),
            ],
            'password' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'email' => trans('admin_labels.email'),
            'password' => trans('admin_labels.password'),
        ];
    }

    public function messages()
    {
        return [
            'required' => trans('validation.index.required'),
            'email.exists' => trans('validation.index.custom.login_error'),
        ];
    }
}