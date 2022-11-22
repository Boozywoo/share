<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\Status;

class StatusRequest extends Request
{
    public function rules()
    {
        $rules = [
            'name' => 'required',
            'percent' => 'required|min:0|max:100',
        ];

        if ($this->get('id')) {
            $rules += [
                'status' => 'required|in:'. implode(',', Status::STATUSES),
            ];
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'name' => trans('admin_labels.name'),
            'percent' => trans('admin_labels.percent'),
        ];
    }
}