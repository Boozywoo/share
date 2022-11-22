<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\RepairSparePart;
use Illuminate\Validation\Rule;

class CreateMassRepairSparePartRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'all.*.spare_part_id' => 'required|exists:spare_parts,id',
            'all.*.count' => 'numeric',
            'all.*.status' => 'nullable|' . Rule::in(RepairSparePart::STATUSES),
        ];
    }
}
