<?php

namespace App\Http\Requests\Admin\OperationalTasks;

use App\Models\OperationalTask\OperationalTaskStatus;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class TaskEditRequest extends FormRequest
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
            'responsible_id' => 'integer|exists:' . User::getTableName() . ',id',
            'subject' => 'string|max:255',
            'description' => 'string',
            'status' => 'string|in:' . implode(',', OperationalTaskStatus::STATUS_LIST),
            'files.*' => 'file',
            'comment' => 'required|string|max:255',
            'comment-files.*' => 'file'
        ];
    }
}
