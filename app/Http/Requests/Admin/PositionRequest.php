<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\Status;

class PositionRequest extends Request
{
    public function rules()
    {
        $rules = [
            'name' => 'required',
            'company_id' => 'required|exists:companies,id',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'name' => trans('admin_labels.name'),
            'company_id' => trans('admin_labels.company_id'),
        ];
    }
}
