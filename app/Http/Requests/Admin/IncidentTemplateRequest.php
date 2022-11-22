<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\DiagnosticCardTemplate;
use App\Models\Incident;
use App\Models\IncidentTemplate;

class IncidentTemplateRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'status' => 'required|in:' . implode(',', IncidentTemplate::STATUSES),
            'name' => 'required',
            'is_photo' => 'required'
        ];
        return $rules;
    }

    public function attributes()
    {
        return [
            'status' => trans('admin_labels.status'),
            'name' => trans('admin_labels.name'),
            'is_photo' => trans('admin_labels.is_photo'),
        ];
    }
}
