<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\Repair;

class TourRequest extends Request
{
    public function rules()
    {

        $rules = [
            'date_start' => 'required|date_format:"d.m.Y"',
            'time_start' => 'required|date_format:"H:i"',
            'bus_id' => 'required|exists:buses,id',
            'driver_id' => 'required|exists:drivers,id',
        ];

        if (!$this->get('id')) {
            $rules += [
                'route_id' => 'required|exists:routes,id',
                'price' => 'required|numeric',
            ];
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'route_id' => trans('admin_labels.route_id'),
            'bus_id' => trans('admin_labels.bus_id'),
            'driver_id' => trans('admin_labels.driver_id'),
            'price' => trans('admin_labels.price'),
            'date_start' => trans('admin_labels.date_start'),
            'time_start' => trans('admin_labels.time_start'),
            'time_finish' => trans('admin_labels.time_finish'),
        ];
    }
}