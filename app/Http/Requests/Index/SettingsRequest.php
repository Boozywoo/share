<?php

namespace App\Http\Requests\Index;

use App\Http\Requests\Request;
use App\Traits\ClearPhone;

class SettingsRequest extends Request
{
    use ClearPhone;

    public function rules()
    {
        $userId = auth()->user()->client_id;
        $rules = [
            'first_name' => 'required',
            'phone' => 'required|digits_between:10,12|unique:clients,phone,'. $userId,
            'email' => 'required|email|unique:clients,email,'. $userId,
            'password' => 'min:6',
            'status_id' => 'exists:statuses,id',
        ];

        return $rules;
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
            'first_name' => trans('index_labels.first_name'),
            'phone' => trans('index_labels.phone'),
            'password' => trans('index_labels.password'),
        ];
    }

    public function messages()
    {
        return [
            'required' => trans('validation.index.required'),
            'phone.size' => trans('validation.index.custom.phone_size'),
            'phone.unique' => trans('validation.index.custom.phone_unique'),
            'email.unique' => trans('validation.index.custom.email_unique'),
            'password.min' => trans('validation.index.custom.password_min_six'),
        ];
    }
}