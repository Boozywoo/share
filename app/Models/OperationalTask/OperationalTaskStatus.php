<?php

namespace App\Models\OperationalTask;

use App\Models\User;
use App\Traits\ModelTableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OperationalTaskStatus extends Model
{
    use ModelTableTrait;

    const STATUS_NEW = 'new';
    const STATUS_CLOSED = 'closed';
    const STATUS_IN_PROGRESS = 'in-progress';

    const STATUS_LIST = [
        self::STATUS_NEW,
        self::STATUS_CLOSED,
        self::STATUS_IN_PROGRESS
    ];

    protected $fillable = [
        'task_id',
        'user_id',
        'status'
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
