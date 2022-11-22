<?php

namespace App\Http\Requests\Api\NorTrans;

use App\Http\Requests\Api\ApiRequest;

class DiagnosticCardItemRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "childs" => 'array',
            "childs.*" => 'exists:review_act_template_items,id',
            "*.images" => 'array',
            "*.images.*" => 'image'
        ];
    }
    public function attributes()
    {
        return [
              '*.images.*' => __('admin_labels.photo')
        ];
    }
}
