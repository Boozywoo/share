<?php

namespace App\Http\Requests\Api\Admin\Register;

use App\Http\Requests\Api\ApiRequest;
use App\Models\Client;
use App\Models\User;
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
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'middle_name' => ['required', 'string'],
            'phone' => ['required', 'digits_between:10,13',
                Rule::unique('users'),
                ],
            'password' => ['required', 'min:6', 'max:20', 'confirmed'],
            'password_confirmation' => ['required', 'min:6', 'max:20'],
            'email' => [
                'required',
                'email',
                Rule::unique('users'),
            ],
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
            'first_name' => trans('admin_labels.first_name'),
            'last_name' => trans('admin_labels.last_name'),
            'middle_name' => trans('admin_labels.middle_name'),
            'phone' => trans('admin_labels.phone'),
            'password' => trans('admin_labels.password'),
            'email' => trans('admin_labels.email')
        ];
    }

    public function messages()
    {
        return [
            'first_name.string' => trans('validation.first_name'),
            'last_name.string' => trans('validation.last_name'),
            'middle_name.string' => trans('validation.middle_name'),
            'required' => trans('validation.index.required'),
            'email.unique' => trans('validation.index.custom.email_unique'),
            'phone.size' => trans('validation.index.custom.phone_size'),
            'password.min' => trans('validation.index.custom.password_min_six')
        ];
    }
}
