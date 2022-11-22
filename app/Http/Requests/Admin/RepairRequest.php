<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\Repair;

class RepairRequest extends Request
{
    public function rules()
    {
        $rules = [
            'type' => 'required|in:' . implode(',', Repair::TYPES),
            'bus_id' => 'required|exists:buses,id',
            'department_id' => 'required|exists:departments,id',
            'name' => 'required',
            'cards.*' => 'nullable|exists:repair_card_templates,id'
//            'comment' => 'required'
        ];

        /*        if (!$this->get('id')) {
                    $rules += [
                        'date_from' => 'required|date_format:"d.m.Y"|after:today',
                        'date_to' => 'required|date_format:"d.m.Y"|after:today',
                    ];
                } else {
                    $rules += [
                        'status' => 'required|in:' . implode(',', Repair::STATUSES),
                        'date_to' => 'required|date_format:"d.m.Y"|after:today',
                    ];
                }*/

        return $rules;
    }

    public function attributes()
    {
        return [
            'date_from' => trans('admin_labels.date_from'),
            'date_to' => trans('admin_labels.date_to'),
        ];
    }

    public function messages()
    {
        return [
            'date_from.after' => trans('validation.index.custom.date_after_today'),
            'date_to.after' => trans('validation.index.custom.date_after_today'),
        ];
    }
}
