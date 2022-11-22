<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\DiagnosticCardTemplate;

class DiagnosticCardTemplateRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'status' => 'required|in:' . implode(',', DiagnosticCardTemplate::STATUSES),
            'name' => 'required',
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
