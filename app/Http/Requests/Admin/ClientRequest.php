<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\Client;
use App\Traits\ClearPhone;

class ClientRequest extends Request
{
    use ClearPhone;

    public function rules()
    {
        $rules = [
            'first_name'  => 'required',
            'email' => 'unique:clients,email,' . $this->get('id'),
            'phone' => 'required|unique:clients,phone,'. $this->get('id').'|digits_between:10,12',
            'date_social' => 'date_format:"d.m.Y"',
            'birth_day' => 'date_format:"d.m.Y"',
            'status_id' => 'exists:statuses,id',
        ];

        if ($this->get('id')) {
            $rules += [
                'status' => 'required|in:'. implode(',', Client::STATUSES),
                'reputation' => 'required|in:'. implode(',', Client::REPUTATIONS),
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
            'date_social' => trans('admin_labels.date_social'),
            'birth_day' => trans('admin_labels.birth_day'),
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