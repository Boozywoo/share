<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class StoreCardReviewRequest extends Request
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
        return $data;
    }

    public function attributes()
    {
        return [
            'odometer' => __('admin_labels.odometer'),
            'fuel' => __('admin_labels.fuel'),
        ];
    }

}
