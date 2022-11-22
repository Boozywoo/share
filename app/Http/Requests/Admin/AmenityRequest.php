<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\Amenity;
use App\Models\ReviewActTemplateItem;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AmenityRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required',
            'status' => 'required|in:'. implode(',', Amenity::STATUSES),
        ];
        return $rules;
    }

    public function attributes()
    {
        return [
            'status' => trans('admin_labels.status'),
            'name' => trans('admin_labels.name'),
        ];
    }
}
