<?php

namespace App\Http\Requests\Api\Client;

use App\Http\Requests\Api\ApiRequest;

class CityToRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'from' => ['required', 'integer']
        ];
    }
}
