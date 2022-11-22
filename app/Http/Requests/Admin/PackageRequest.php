<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class PackageRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'route_id' => 'required',
            'tour_id' => 'required',
            'package_from' => 'required_without:from_station_id|min:2|max:50',
            'package_destination' => 'required_without:destination_station_id|min:2|max:50',
            'from_station_id' => 'required_without:package_from',
            'destination_station_id' => 'required_without:package_destination',
            'price' => 'required|integer',
            'name_sender' => 'required|min:2|max:30',
            'phone_sender' => 'required|min:2|max:30',
            'name_receiver' => 'required|min:2|max:30',
            'phone_receiver' => 'required|min:2|max:30',
        ];
    }

    public function attributes()
    {
        return [
            'route_id' => trans('admin_labels.routes'),
            'tour_id' => trans('admin_labels.tours'),
            'price' => trans('admin_labels.price'),
            'package_destination.required_without.test' => trans('admin_labels.package_destination'),
            'destination_station_id.required_without' => trans('admin_labels.package_destination'),
            'package_from.required_without' => trans('admin_labels.package_from'),
            'from_station_id.required_without' => trans('admin_labels.package_from'),
            'name_sender' => trans('admin_labels.name_sender'),
            'phone_sender' => trans('admin_labels.phone_sender'),
            'name_receiver' => trans('admin_labels.name_receiver'),
            'phone_receiver' => trans('admin_labels.phone_receiver'),
            'validation.required_without' => trans('admin_labels.phone_receiver'),
        ];
    }


    public function messages()
    {
        return [
            'package_from.required_without' => trans('messages.packages.from'),
            'package_destination.required_without' => trans('messages.packages.destination'),
            'from_station_id.required_without' => trans('messages.packages.from'),
            'destination_station_id.required_without' => trans('messages.packages.destination'),
        ];
    }

}
