<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\Repair;

class TemplateRequest extends Request
{
    public function rules()
    {

        $rules = [
            'name' => 'required',
            'ranks' => 'required|integer|min:3|max:5',
            'columns' => 'required|integer|min:3|max:20',
            'placeTypes' => 'required|array|size:'. $this->get('ranks') * $this->get('columns'),
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'name' => trans('admin_labels.name'),
            'ranks' => trans('admin_labels.ranks'),
            'columns' => trans('admin_labels.columns'),
        ];
    }
}