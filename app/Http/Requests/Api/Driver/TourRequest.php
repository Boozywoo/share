<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\ApiRequest;
use App\Models\Tour;

class TourRequest extends ApiRequest
{
    public function rules()
    {
        $rules = [
            'type_driver' => 'in:'. implode(',', Tour::TYPE_DRIVERS),
        ];

        return $rules;
    }
}