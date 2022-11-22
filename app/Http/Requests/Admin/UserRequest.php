<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\ClearPhone;

class UserRequest extends Request
{
    use ClearPhone;

    public function rules()
    {
        $rules = [
            'first_name'  => 'required',
            'email' => 'required|unique:users,email,' . $this->get('id'),
            'phone' => 'required|unique:users,phone,'. $this->get('id').'|digits_between:10,13',
            'role_id' => 'required|exists:roles,id',
        ];

        if (!$this->get('id')) $rules['password'] = 'required';


        if ($this->get('id')) {
            $rules += [
                'status' => 'required|in:'. implode(',', User::STATUSES),
            ];
        }

        return $rules;
    }

    protected function validationData()
    {
        $data = $this->all();
        if (isset($data['phone'])) $data['phone'] = $this->clearPhone($data['phone']);
        return $data;
    }

    public function attributes()
    {
        return [
            'first_name' => trans('admin_labels.first_name'),
            'email' => trans('admin_labels.email'),
            'phone' => trans('admin_labels.phone'),
            'password' => trans('admin_labels.password'),
        ];
    }

    public function messages()
    {
        return [
            'phone.unique' => trans('validation.index.custom.phone_unique'),
            'phone.size' => trans('validation.index.custom.phone_size'),
        ];
    }
}