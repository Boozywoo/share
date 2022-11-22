<?php

namespace App\Http\Requests\Api\Client;

use App\Http\Requests\Api\ApiRequest;
use App\Models\Client;
use App\Traits\ClearPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends ApiRequest
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
    use ClearPhone;

    public function rules()
    {
        return [
            'phone' => ['required', 'digits_between:10,12'],
            'password' => 'required',
            'first_name' => 'required',
        ];
    }

    protected function validationData()
    {
        $data = $this->all();
        if (isset($data['phone'])) $data['phone'] = $this->clearPhone($data['phone']);
        return $data;
    }

    public function attributes()
    {
        return [
            'phone' => trans('admin_labels.phone'),
            'password' => trans('admin_labels.password'),
        ];
    }

    public function messages()
    {
        return [
            'required' => trans('validation.index.required'),
            'email.exists' => trans('validation.index.custom.login_error'),
        ];
    }
}
