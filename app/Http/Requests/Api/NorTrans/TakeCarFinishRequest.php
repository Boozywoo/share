<?php

namespace App\Http\Requests\Api\NorTrans;

use App\Http\Requests\Api\ApiRequest;

class TakeCarFinishRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "condition" => "required|in:0,1"
        ];
    }

    public function attributes()
    {
        return [
            "condition" => __('admin_labels.condition')
        ];
    }
}

