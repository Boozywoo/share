<?php

namespace App\Http\Requests\Api\Client;


class AddOrderRequest extends OrderRequest
{
    public function rules()
    {
        return parent::rules() + [
                'tour_id' => 'required | exists:tours,id',
                'station_from_id' => 'required | integer',
                'station_to_id' => 'required | integer',
                'count_places' => 'required | integer',
            ];
    }

    public function messages()
    {
        return [
            'tour_id.exists' => trans('validation.no_exist'),
        ];
    }
}
