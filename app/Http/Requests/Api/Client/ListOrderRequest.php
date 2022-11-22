<?php

namespace App\Http\Requests\Api\Client;

use Illuminate\Foundation\Http\FormRequest;

class ListOrderRequest extends OrderRequest
{
    public function rules()
    {
        return parent::rules() + [
                //
            ];
    }
}
