<?php

namespace App\Http\Requests\Api\Driver;

use App\Http\Requests\Api\ApiRequest;
use App\Traits\ClearPhone;

class AddOrderDriverRequest extends ApiRequest
{
    use ClearPhone;

    public function rules()
    {
        $rules = [
            'phone' => 'required|digits_between:10,13',
            'first_name' => 'required',
            'tour_id' => 'required|exists:tours,id',
            'places' => 'required|array',
            'station_from_id' => 'required',
            'station_to_id' => 'required',
            'delay' => 'integer|min:1',
        ];

        return $rules;
    }

    protected function validationData()
    {
        $data = $this->all();
        $data['phone'] = isset($data['phone']) ? $this->clearPhone($data['phone']) : null;
        return $data;
    }
}
