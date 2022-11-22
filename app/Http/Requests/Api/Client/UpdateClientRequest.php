<?php

namespace App\Http\Requests\Api\Client;

use App\Http\Requests\Api\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;


class UpdateClientRequest extends ApiRequest
{
    public function rules()
    {
        return [
          'api_token' => 'required|exists:tokens,api_token',
          'password_confirmation' => 'required_with:password',
          'password' => 'nullable|confirmed|min:2',
        ];
    }

    public function messages()
    {
        return [
          'api_token.exists' => trans('validation.no_exist'),
        ];
    }

    public function withValidator($validator)
    {
        return;
    }
}
