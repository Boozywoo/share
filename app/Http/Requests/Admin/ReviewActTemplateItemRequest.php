<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\ReviewActTemplateItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReviewActTemplateItemRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'status' => 'required|in:' . implode(',', ReviewActTemplateItem::STATUSES),
            'name' => 'required',
            'review_act_template_id' => 'required|exists:review_act_templates,id',
            'is_photo' => Rule::in([0,1]),
            'is_comment' => Rule::in([0,1]),
        ];
        return $rules;
    }

    public function attributes()
    {
        return [
            'status' => trans('admin_labels.status'),
            'name' => trans('admin_labels.name'),
            'review_act_template_id' => trans('admin_labels.review_act_template_id'),
        ];
    }
}
