<?php

namespace App\Http\Requests\Admin\Auth;

use App\Http\Requests\Request;
use App\Models\User;
use Illuminate\Validation\Rule;

class RegisterRequest extends Request
{
    public function rules()
    {
        return [
            'first_name' => ['required', 'string'],
            'phone' => ['required', 'digits_between:10,13',
                Rule::unique('users')->where(function ($query) {
                    $query->where('status', User::STATUS_ACTIVE);
                }),],
            'password' => ['required', 'min:6', 'max:20'],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->where(function ($query) {
                    $query->where('status', User::STATUS_ACTIVE);
                }),
            ],
            'company_id' => ['required',
                'exists:companies,id'
                ],
            'department_id' => ['nullable',
                'exists:departments,id'
                ],
            'position_id' => ['required',
                'exists:positions,id'
            ],
        ];
    }

    public function attributes()
    {
        return [
            'first_name' => trans('admin_labels.first_name'),
            'phone' => trans('admin_labels.phone'),
            'password' => trans('admin_labels.password'),
            'email' => trans('admin_labels.email'),
            'company_id' => trans('admin_labels.company_id'),
            'department_id' => trans('admin_labels.department_id'),
            'position' => trans('admin_labels.position'),
        ];
    }

    public function messages()
    {
        return [
            'first_name.string' => trans('validation.first_name'),
            'required' => trans('validation.index.required'),
            'email.unique' => trans('validation.index.custom.email_unique'),
            'phone.size' => trans('validation.index.custom.phone_size'),
            'password.min' => trans('validation.index.custom.password_min_six'),
            'company_id.exists' => trans('validation.company_exists'),
            'department_id.exists' => trans('validation.department_exists'),
        ];
    }
}