<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class TourImportRequest extends Request
{
    public function rules()
    {

        $rules = [
            'orders' => 'required|array',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'orders.required' => 'Выберите хоть одну бронь',
        ];
    }
}