<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\Sale;

class SaleRequest extends Request
{
    public function rules()
    {
        $rules = [
            'name' => 'required',
            //'percent' => 'required|integer|min:0|max:100',
            'value' => 'required|regex:/^\d+(\.\d{1,2})?$/',   // Два знака после точки
            'type' => 'required|in:'. implode(',', Sale::TYPES),
            'date_start' => 'required|date_format:"d.m.Y"',
            'date_finish' => 'required|date_format:"d.m.Y"',
        ];

        if ($this->get('id')) {
            $rules += [
                'status' => 'required|in:'. implode(',', Sale::STATUSES),
            ];
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'name' => trans('admin_labels.name'),
            'percent' => trans('admin_labels.percent'),
            'type' => trans('admin_labels.type'),
        ];
    }
}