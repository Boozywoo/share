<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\Repair;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FinishRepairRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => 'required|'.Rule::in([Repair::STATUS_WAIT, Repair::STATUS_WITHOUT_REPAIR, Repair::STATUS_OF_REPAIR])
        ];
    }
}
