<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\DiagnosticCard;

class DiagnosticCardRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            //'date_exec' => 'required|date_format:"d.m.Y"',
//            'bus_id' => 'required|exists:buses,id',
            //'status' => 'required|in:' . implode(',', DiagnosticCard::STATUSES),
            //'sap_number' => 'required',
            //'reg_number' => 'required',
            //'master_id' => 'required|exists:drivers,id',
            //'contractor_id' => 'required|exists:drivers,id',
            'diagnostic_card_template_id' => 'required|exists:diagnostic_card_templates,id',
            'min_odometer' => 'numeric',
            'odometer' => 'required|numeric|min:'.$this->get('min_odometer'),
            'fuel' => 'required|numeric',
        ];
        return $rules;
    }

    public function attributes()
    {
        return [
            'bus_id' => trans('admin_labels.bus_id'),
            'status' => trans('admin_labels.status'),
            'sap_number' => trans('admin_labels.sap_number'),
            'date_exec' => trans('admin_labels.date_exec'),
            'master_id' => trans('admin_labels.master_id'),
            'contractor_id' => trans('admin_labels.contractor_id'),
            'reg_number' => trans('admin_labels.reg_number'),
            'odometer' => trans('admin_labels.odometer'),
            'fuel' => trans('admin_labels.fuel'),
            'diagnostic_card_template_id' => trans('admin_labels.diagnostic_card_template_id'),
        ];
    }

}
