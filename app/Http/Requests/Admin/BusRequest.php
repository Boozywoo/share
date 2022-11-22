<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\Bus;
use Illuminate\Validation\Rule;

class BusRequest extends Request
{
    public function rules()
    {
        $rules = [
            'name' => 'required',
            'name_tr' => 'required',
            'number' => 'required',
            'places' => 'required|integer|min:1',
            'company_id' => 'required|exists:companies,id',
            'department_id' => 'nullable',
            'template_id' => [
                'required',
                Rule::exists('templates', 'id')->where(function ($query) {
                    $query->where('count_places', $this->get('places'));
                }),
            ]
        ];

        if ($this->get('id')) {
            $rules += [
                'status' => 'required|in:'. implode(',', Bus::STATUSES),
            ];
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'name' => trans('admin_labels.name'),
            'name_tr' => trans('admin_labels.name_tr'),
            'number' => trans('admin_labels.number'),
            'places' => trans('admin_labels.places'),
            'department_id' => trans('admin_labels.department_id'),
        ];
    }

    public function messages()
    {
       return [
           'template_id.required' => trans('validation.index.custom.no_template_places', ['places' => $this->get('places')]),
       ];
    }
}
