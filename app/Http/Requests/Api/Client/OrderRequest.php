<?php

namespace App\Http\Requests\Api\Client;

use App\Http\Requests\Api\ApiRequest;

class OrderRequest extends ApiRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'api_token' => 'required|exists:tokens,api_token',
        ];
    }

    public function messages()
    {
        return [
            'api_token.exists' => trans('validation.no_exist'),
        ];
    }
}
