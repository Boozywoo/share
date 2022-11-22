<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\Bus;
use Illuminate\Validation\Rule;

class WishesCompleteRequest extends Request
{
    public function rules()
    {
        $rules = [
            'id' => 'required',
            'comment_complete' => 'required'
        ];

        return $rules;
    }
}
