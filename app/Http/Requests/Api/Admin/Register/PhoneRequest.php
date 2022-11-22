<?php

namespace App\Http\Requests\Api\Admin\Register;

use App\Http\Requests\Api\ApiRequest;

class PhoneRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules()
    {
        return [
            'phone' => 'required|exists:users,phone',
        ];
    }

    public function attributes()
    {
        return [
            'phone' => trans('admin_labels.phone'),
        ];
    }

    public function messages()
    {
        return [
            'phone.exists' => __("messages.admin.auth.phone_exists")
        ];
    }

}
