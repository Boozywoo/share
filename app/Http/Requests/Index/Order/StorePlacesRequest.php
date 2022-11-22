<?php

namespace App\Http\Requests\Index\Order;

use App\Http\Requests\Request;
use App\Models\Order;
use App\Traits\ClearPhone;

class StorePlacesRequest extends Request
{
    public function rules()
    {
        $rules = [
            'places' => 'array',
        ];

        return $rules;
    }
}