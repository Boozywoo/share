<?php

namespace App\Http\Requests\Api\Client;


class TaxiOrderRequest extends OrderRequest
{
    public function rules()
    {
        return parent::rules() + [
                'station_from_id' => 'required|integer|exists:stations,id',
                'station_to_id' => 'required|integer|exists:stations,id',
                'places' => 'integer|min:1',
            ];
    }

    public function messages()
    {
        return [
            'tour_id.exists' => trans('validation.no_exist'),
        ];
    }
}
