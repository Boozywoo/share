<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\DiagnosticCardTemplateItem;
use App\Models\ReviewActTemplateItem;
use Illuminate\Foundation\Http\FormRequest;

class DiagnosticCardTemplateItemRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            /*'status' => 'required|in:' . implode(',', DiagnosticCardTemplateItem::STATUSES),
            'title' => 'required',*/
            'diagnostic_card_template_id' => 'required|exists:diagnostic_card_templates,id',
            'review_act_template_id' => 'required|exists:review_act_templates,id',
        ];
        return $rules;
    }

    public function attributes()
    {
        return [
            /*'status' => trans('admin_labels.status'),
            'name' => trans('admin_labels.name'),*/
            'diagnostic_card_template_id' => trans('admin_labels.review_act_template_id'),
            'review_act_template_id' => trans('admin_labels.review_act_template_id'),
        ];
    }
}
