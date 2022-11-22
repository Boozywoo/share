<?php

namespace App\Http\Requests\Index\Order;

use App\Http\Requests\Request;

class ScheduleFormRequest extends Request
{
    public function rules()
    {
        return [
            'station_from_id' => 'required',
            'station_to_id' => 'required',
            //'route_id' => 'required|exists:routes,id',
            'date' => 'required|date_format:d.m.Y'
        ];
    }

    public function attributes()
    {
        return [
            'station_from_id' => trans('admin_labels.station_from_id'),
            'station_to_id' => trans('admin_labels.station_to_id'),
            'route_id' => trans('admin_labels.route_id'),
            'date' => trans('admin_labels.date'),
        ];
    }
}