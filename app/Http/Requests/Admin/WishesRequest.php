<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\Bus;
use Illuminate\Validation\Rule;

class WishesRequest extends Request
{
    public function rules()
    {
        $rules = [
            'subject' => 'required',
            'new_comment' => 'required'
        ];

        /*if ($this->get('id')) {
            $rules += [
                'status' => 'required|in:'. implode(',', Bus::STATUSES),
            ];
        }*/

        return $rules;
    }
}
