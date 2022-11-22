<?php

namespace App\Http\Requests\Index\Auth;

use App\Http\Requests\Request;
use App\Traits\ClearPhone;
use Illuminate\Validation\Rule;

class LoginRequest extends Request
{
    use ClearPhone;

    public function rules()
    {
        return [
            'phone' => [
                'required',
                'digits_between:10,12',
                Rule::exists('clients')->where(function ($q) {
//                    $q->where('register', 1);
                }),
            ],
            'password' => 'required',
        ];
    }

    protected function validationData()
    {
        $data = $this->all();
        $data['phone'] = $this->clearPhone($data['phone']);
        return $data;
    }

    public function attributes()
    {
        return [
            'phone' => trans('admin_labels.phone'),
            'password' => trans('admin_labels.password'),
        ];
    }

    public function messages()
    {
        return [
            'required' => trans('validation.index.required'),
            'phone.size' => trans('validation.index.custom.phone_size'),
            'phone.exists' => trans('validation.index.custom.login_error'),
        ];
    }
}