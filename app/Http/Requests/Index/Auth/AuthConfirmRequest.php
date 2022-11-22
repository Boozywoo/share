<?php

namespace App\Http\Requests\Index\Auth;

use App\Http\Requests\Request;
use App\Traits\ClearPhone;
use Illuminate\Validation\Rule;

class AuthConfirmRequest extends Request
{
    use ClearPhone;

    public function rules()
    {
        return [
            'code' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'Введите код',
        ];
    }
}