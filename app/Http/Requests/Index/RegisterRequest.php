<?php

namespace App\Http\Requests\Index;

use App\Http\Requests\Request;
use App\Traits\ClearPhone;
use Illuminate\Validation\Rule;

class RegisterRequest extends Request
{
    use ClearPhone;

    public function rules()
    {
        return [
            'first_name' => 'required',
            'phone' => [
                'required',
                'digits_between:10,12',
                Rule::unique('clients')->where(function ($query) {
                    $query->where('register', 1);
                })
            ],
            'email' => [
                'required',
                Rule::unique('clients')->where(function ($query) {
                    $query->where('register', 1);
                })
            ],
            'password' => 'required|min:6',
            'confirm' => 'accepted',
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
            'first_name' => trans('admin_labels.first_name'),
            'phone' => trans('admin_labels.phone'),
            'password' => trans('admin_labels.password'),
            'email' => trans('admin_labels.email'),
        ];
    }

    public function messages()
    {
        return [
            'required' => trans('validation.index.required'),
            'phone.unique' => trans('validation.index.custom.phone_unique'),
            'email.unique' => trans('validation.index.custom.email_unique'),
            'phone.size' => trans('validation.index.custom.phone_size'),
            'password.min' => trans('validation.index.custom.password_min_six'),
            'confirm.accepted' => trans('validation.index.custom.confirm_accepted'),
        ];
    }
}