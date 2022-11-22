<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\City;

class CityRequest extends Request
{
    public function rules()
    {
        $rules = [
            'name' => 'required'
        ];

        if ($this->get('id')) {
            $rules += [
                'status' => 'required|in:'. implode(',', City::STATUSES),
            ];
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'name' => trans('admin_labels.name'),
            'place_address' => trans('admin_labels.place_address'),
        ];
    }
}