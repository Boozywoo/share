<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\Bus;
use Illuminate\Validation\Rule;

class WishesDelegateRequest extends Request
{
    public function rules()
    {
        $rules = [
            'id' => 'required',
            'delegate_id' => 'required'
        ];

        /*if ($this->get('id')) {
            $rules += [
                'status' => 'required|in:'. implode(',', Bus::STATUSES),
            ];
        }*/

        return $rules;
    }
}
