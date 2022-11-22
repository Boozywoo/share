<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class RepairCardTemplateRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'parent_id' => 'nullable',
            'is_comment' => 'in:0,1',
            'is_photo' => 'in:0,1',
        ];
    }
}
