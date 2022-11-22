<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\Driver;
use App\Traits\ClearPhone;

class DriverRequest extends Request
{
    use ClearPhone;

    public function rules()
    {
        $rules = [
            'full_name' => 'required',
            'phone' => 'required|unique:drivers,phone,'. $this->get('id').'|digits_between:10,13',
            'company_id' => 'required|exists:companies,id',
            'birth_day' => 'required|date_format:"d.m.Y"|before:today',
        ];

        if ($this->get('id')) {
            $rules += [
                'status' => 'required|in:'. implode(',', Driver::STATUSES),
                'reputation' => 'required|in:'. implode(',', Driver::REPUTATIONS)
            ];
        }
        
        if (!$this->get('id')) $rules['password'] = 'required';

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
            'full_name' => trans('admin_labels.full_name'),
            'phone' => trans('admin_labels.phone'),
            'company_id' => trans('admin_labels.company_id'),
            'birth_day' => trans('admin_labels.birth_day'),
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