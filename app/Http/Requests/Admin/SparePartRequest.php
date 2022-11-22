<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\SparePart;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SparePartRequest extends Request
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
            'status' => 'required|'.Rule::in(SparePart::STATUSES),
        ];
    }
}
