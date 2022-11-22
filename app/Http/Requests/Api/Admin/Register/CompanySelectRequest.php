<?php

namespace App\Http\Requests\Api\Admin\Register;

use App\Http\Requests\Api\ApiRequest;

class CompanySelectRequest extends ApiRequest
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
            'company_id' => "exists:companies,id|required",
        ];
    }

    public function attributes()
    {
        return [
            'password' => trans('admin_labels.password'),
            'email' => trans('admin_labels.email')
        ];
    }

    public function messages()
    {
        return [
            'password.min' => trans('validation.index.custom.password_min_six')
        ];
    }
}
