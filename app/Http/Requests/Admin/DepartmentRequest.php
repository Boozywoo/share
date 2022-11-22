<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\Status;

class DepartmentRequest extends Request
{
    public function rules()
    {
        $rules = [
            'name' => 'required',
            'director_id' => 'required|exists:users,id',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'name' => trans('admin_labels.name'),
            'director_id' => trans('admin_labels.director_id'),
        ];
    }
}
