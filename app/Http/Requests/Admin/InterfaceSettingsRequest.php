<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class InterfaceSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'theme_color_admin_panel' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'theme_color_admin_panel' => trans(
                'validation.interfaceSettings.theme_color_admin_panel'
            ),
        ];
    }
}
