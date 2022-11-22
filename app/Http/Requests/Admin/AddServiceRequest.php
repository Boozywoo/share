<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\AddService;

class AddServiceRequest extends Request
{
    public function rules()
    {
        $rules = [
            'name' => 'required',
        ];

        if ($this->get('id')) {
            $rules += [
                'status' => 'required|in:'. implode(',', AddService::STATUSES),
            ];
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'name' => trans('admin_labels.name'),
        ];
    }
}