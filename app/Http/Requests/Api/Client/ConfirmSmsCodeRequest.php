<?php

namespace App\Http\Requests\Api\Client;

use App\Http\Requests\Api\ApiRequest;
use App\Traits\ClearPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConfirmSmsCodeRequest extends ApiRequest
{
    use ClearPhone;

    public function rules()
    {
        return [
            'phone' => [
                'required',
                Rule::exists('codes'),
                'digits_between:10,13',
            ],
            'code' => ['required'],
        ];
    }

    protected function validationData()
    {
        $data = $this->all();
        if (isset($data['phone'])) $data['phone'] = $this->clearPhone($data['phone']);
        return $data;
    }
}
