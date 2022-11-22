<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\ReviewAct;

class ReviewActRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'bus_id' => 'required|exists:buses,id',
            'review_act_template_id' => 'required|exists:review_act_templates,id',
            'diagnostic_card_id' => 'required|exists:diagnostic_cards,id',
        ];
        return $rules;
    }

    public function attributes()
    {
        return [
            'bus_id' => trans('admin_labels.bus_id'),
            'name' => trans('admin_labels.name'),
            'review_act_template_id' => trans('admin_labels.review_act_template_id'),
        ];
    }
}
