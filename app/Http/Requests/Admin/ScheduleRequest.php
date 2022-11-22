<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\Schedule;

class ScheduleRequest extends Request
{
    public function rules()
    {
        $rules = [
            'bus_id' => 'required|exists:buses,id',
            'date_finish_date' => 'required|date_format:"d.m.Y"',
            'date_start_date' => 'required|date_format:"d.m.Y"',
            'date_start_time' => 'required|date_format:"H:i"',
            'days.*.driver_id' => 'required_if:days.*.active,1|exists:drivers,id',
        ];

        if ($this->get('id') || $this->get('copy')) {
            $rules += [
                'status' => 'required|in:'. implode(',', Schedule::STATUSES),
            ];
        } else {
            $rules += [
                'route_id' => 'required|exists:routes,id',
                'days' => 'required|array|size:9',
                'days.*.active' => 'required|boolean',
                'days.*.price' => 'required_if:days.*.active,1|numeric',
            ];
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'bus_id' => trans('admin_labels.bus_id'),
            'places' => trans('admin_labels.places'),
            'date_finish_date' => trans('admin_labels.date_finish_date'),
            'date_finish_time' => trans('admin_labels.date_finish_time'),
            'date_start_date' => trans('admin_labels.date_start_date'),
            'date_start_time' => trans('admin_labels.date_start_time'),
        ];
    }

    public function messages()
    {
       return [
           'required_if' => 'Поле обязательно для заполнения',
       ];
    }
}