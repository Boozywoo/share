<?php


namespace App\Http\Requests\Api\Client;


class UpdateOrderRequest extends OrderRequest
{
    public function rules()
    {
        return parent::rules() + [
                //
            ];
    }
}