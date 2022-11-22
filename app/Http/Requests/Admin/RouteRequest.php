<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\Bus;
use App\Models\Route;

class RouteRequest extends Request
{
    public function rules()
    {
        $rules = [
            'type' => 'required|in:is_regular,is_taxi,is_route_taxi,is_transfer',
            'name' => 'required',
            'name_tr' => 'required',
        ];

        if ($this->get('id')) {
            $rules += [
                'status' => 'required|in:'. implode(',', Route::STATUSES),
            ];
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'name' => trans('admin_labels.name'),
            'name_tr' => trans('admin_labels.name_tr'),
        ];
    }
}