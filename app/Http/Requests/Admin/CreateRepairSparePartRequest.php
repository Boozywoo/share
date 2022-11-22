<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\RepairSparePart;
use Illuminate\Validation\Rule;

class CreateRepairSparePartRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'spare_part_id' => 'exists:spare_parts,id',
            'count' => 'numeric',
            'status' => 'nullable|' . Rule::in(RepairSparePart::STATUSES),
        ];
    }
}
