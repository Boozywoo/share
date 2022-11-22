<?php


namespace App\Http\Requests\Api\Client;


use App\Http\Requests\Api\ApiRequest;

class GetStationTourRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'from_city_id' => 'required|integer|exists:cities,id',
            'to_city_id' => 'required|integer|exists:cities,id',
        ];
    }
}