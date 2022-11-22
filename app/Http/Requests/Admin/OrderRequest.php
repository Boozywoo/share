<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\Order;
use App\Traits\ClearPhone;

class OrderRequest extends Request
{
    use ClearPhone;

    protected $redirectRoute = 'admin.orders.index';

    public function rules()
    {
        $rules = [
            'phone' => 'required|digits_between:10,13',
            'first_name' => 'required',
            'tour_id' => 'required|exists:tours,id',
            'status' => 'required|string|in:active,reserve',
        ];

        if ($this->get('status') != Order::STATUS_RESERVE) {
            $rules['places'] = 'required|array|filled';
        }

        if ($this->get('id')) $rules['status'] = 'in:' . implode(',', Order::STATUSES);
        if ($this->get('middle_name') === '') $rules['middle_name'] = 'required';
        if ($this->get('last_name') === '') $rules['last_name'] = 'required';
        if ($this->get('passport') === '') $rules['passport'] = 'required';
        if ($this->get('birth_day') === '') $rules['birth_day'] = 'required';
        if (isset($this->flight_number)) $rules['flight_number'] = 'required|string';

        $phone = preg_replace('/[^0-9.]+/', '', $this->get('phone'));
        if (substr($phone, 0, 1) == '7') {
            $rules['phone'] = 'min:11';
        } elseif (substr($phone, 0, 3) == '380') {
            $rules['phone'] = 'min:12';
        } elseif (substr($phone, 0, 3) == '375') {
            $rules['phone'] = 'min:12';
        }
        return $rules;
    }

    protected function validationData()
    {
        $data = $this->all();
        $data['phone'] = $this->clearPhone($data['phone']);
        return $data;
    }

    public function attributes()
    {
        return [
            'first_name' => trans('admin_labels.first_name'),
            'last_name' => trans('admin_labels.last_name'),
            'middle_name' => trans('admin_labels.middle_name'),
            'passport' => trans('admin_labels.passport'),
            'birth_day' => trans('admin_labels.birth_day'),
            'phone' => trans('admin_labels.phone'),
            'flight_number' => trans('admin_labels.flight_number'),
        ];
    }

    public function messages()
    {
        return [
            'phone.size' => trans('validation.index.custom.phone_size'),
            'places.required' => trans('validation.index.custom.places_required'),
            'tour_id.required' => trans('validation.index.custom.not_selected'),
        ];
    }
}