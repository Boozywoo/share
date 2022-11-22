<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\Street;

class StreetRequest extends Request
{
    public function rules()
    {
        $rules = [
            'name' => 'required',
            'name_tr' => 'required',
        ];

        if ($this->get('id')) {
            $rules += [
                'status' => 'required|in:'. implode(',', Street::STATUSES),
            ];
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'name' => trans('admin_labels.name'),
            'name_tr' => trans('admin_labels.name_tr'),
            'place_address' => trans('admin_labels.place_address'),
        ];
    }
}