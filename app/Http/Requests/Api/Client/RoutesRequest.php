<?php

namespace App\Http\Requests\Api\Client;

use App\Http\Requests\Api\ApiRequest;

class RoutesRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'from' => ['required', 'integer'],
            'to' => ['required', 'integer'],
            'count_places' => ['required', 'integer']
        ];
    }
}
