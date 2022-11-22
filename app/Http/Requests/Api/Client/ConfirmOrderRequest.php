<?php


namespace App\Http\Requests\Api\Client;


class ConfirmOrderRequest extends OrderRequest
{
    public function rules()
    {
        return parent::rules() + [
                //
            ];
    }
}