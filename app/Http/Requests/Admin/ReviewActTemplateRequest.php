<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\ReviewActTemplate;

class ReviewActTemplateRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'status' => 'required|in:' . implode(',', ReviewActTemplate::STATUSES),
            'name' => 'required',
        ];
        return $rules;
    }

    public function attributes()
    {
        return [
            'status' => trans('admin_labels.status'),
            'name' => trans('admin_labels.name'),
            'diagnostic_card_template_id' => trans('admin_labels.review_act_template_id'),
        ];
    }
}
