<?php

namespace App\Http\Requests\Admin\Auth;

use App\Http\Requests\Request;
use App\Traits\ClearPhone;

class AuthConfirmRequest extends Request
{
    use ClearPhone;

    public function rules()
    {
        return [
            'register_code' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'register_code.required' => 'Введите код',
        ];
    }
}