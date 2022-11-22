<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\Company;
use App\Traits\ClearPhone;

class CompanyRequest extends Request
{
    use ClearPhone;

    public function rules()
    {
        $rules = [
            'name' => 'required',
            'responsible' => 'required',
            'position' => 'required',
            'phone' => 'required|digits_between:10,12',
            'phone_sub' => 'digits_between:10,12',
        ];

        if ($this->get('id')) {
            $rules += [
                'status' => 'required|in:'. implode(',', Company::STATUSES),
                'reputation' => 'required|in:'. implode(',', Company::REPUTATIONS)
            ];
        }

        return $rules;
    }

    protected function validationData()
    {
        $data = $this->all();
        if (isset($data['phone'])) $data['phone'] = $this->clearPhone($data['phone']);
        if (isset($data['phone_sub'])) $data['phone_sub'] = $this->clearPhone($data['phone_sub']);
        return $data;
    }

    public function attributes()
    {
        return [
            'name' => trans('admin_labels.name'),
            'responsible' => trans('admin_labels.responsible'),
            'position' => trans('admin_labels.position'),
            'phone' => trans('admin_labels.phone'),
            'phone_sub' => trans('admin_labels.phone'),
        ];
    }

    public function messages()
    {
        return [
            'phone.size' => trans('validation.index.custom.phone_size'),
            'phone_sub.size' => trans('validation.index.custom.phone_size'),
        ];
    }
}