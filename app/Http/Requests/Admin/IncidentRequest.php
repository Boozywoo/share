<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\DiagnosticCardTemplate;
use App\Models\Incident;
use App\Models\IncidentTemplate;

class IncidentRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'status' => 'required|in:' . implode(',', Incident::STATUSES),
            'name' => 'required',
            'comment' => 'required',
            'department_id' => 'required|exists:departments,id',
            'incident_template_id' => 'required|exists:incident_templates,id'
        ];
        return $rules;
    }

    public function attributes()
    {
        return [
            'status' => trans('admin_labels.status'),
            'name' => trans('admin_labels.name'),
            'comment' => trans('admin_labels.comment'),
            'department_id' => trans('admin_labels.department_id'),
            'incident_template_id' => trans('admin_labels.incident_template_id'),
        ];
    }
}
