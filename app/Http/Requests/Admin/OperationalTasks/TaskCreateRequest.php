<?php

namespace App\Http\Requests\Admin\OperationalTasks;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class TaskCreateRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'responsible' => 'required|integer|exists:' . User::getTableName() . ',id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'files.*' => 'file'
        ];
    }
}
