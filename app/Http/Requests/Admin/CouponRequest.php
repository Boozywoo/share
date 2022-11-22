<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\Coupon;

class CouponRequest extends Request
{
    public function rules()
    {
        $rules = [
            'name' => 'required',
            'code' => 'required',
            'percent' => 'required|integer|min:0|max:100',
            'date_start' => 'required|date_format:"d.m.Y"',
            'date_finish' => 'required|date_format:"d.m.Y"',
        ];

        if ($this->get('id')) {
            $rules += [
                'status' => 'required|in:'. implode(',', Coupon::STATUSES),
            ];
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'name' => trans('admin_labels.name'),
            'percent' => trans('admin_labels.percent'),
            'code' => trans('admin_labels.code'),
        ];
    }
}