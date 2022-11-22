<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class VariablesRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $data = ['min_odometer' => 'required|numeric',
            'odometer' => 'required|numeric|min:' . $this->get('min_odometer'),
            'fuel' => 'required|numeric',
        ];
        $data['condition'] = 'required|in:1,0';
        return $data;
    }

    public function attributes()
    {
        return [
            'odometer' => __('admin_labels.odometer'),
            'fuel' => __('admin_labels.fuel'),
            'condition' => __('admin_labels.condition'),
        ];
    }
}
