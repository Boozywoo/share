<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\Station;

class StationRequest extends Request
{
    public function rules()
    {
        $rules = [
            'name' => 'required',
            'name_tr' => 'required',
            'city_id' => 'required',
            //'longitude'  => 'required',
            //'latitude'  => 'required',
        ];

        if ($this->get('id')) {
            $rules += [
                'status' => 'required|in:'. implode(',', Station::STATUSES),
            ];
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'city_id' => trans('admin_labels.city_id'),
            'name' => trans('admin_labels.name'),
            'name_tr' => trans('admin_labels.name_tr'),
            'place_address' => trans('admin_labels.place_address'),
        ];
    }
}