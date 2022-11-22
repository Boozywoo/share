<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class RepairCardRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'repair_card_type_id' => 'required|exists:repair_card_types,id',
            'childs.*' => 'exists:repair_card_templates,id',
        ];

    }
}

