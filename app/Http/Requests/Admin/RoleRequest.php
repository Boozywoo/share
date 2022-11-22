<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\ReviewActTemplateItem;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'slug' => 'required|sometimes|'.Rule::unique('roles')->ignore($this->id),
            'name' => 'required',
        ];
        return $rules;
    }

    public function attributes()
    {
        return [
            'slug' => trans('admin_labels.status'),
            'name' => trans('admin_labels.name'),
        ];
    }
}
