<?php

namespace App\Models\OperationalTask;

use App\Models\File;
use App\Models\User;
use App\Traits\ModelTableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OperationalTaskComment extends Model
{
    use ModelTableTrait;

    const FILE_TABLE = 'operational_task_comment_files';

    /**
     * @var string[]
     */
    protected $fillable = [
        'task_id',
        'user_id',
        'comment'
    ];

    /**
     * @return BelongsToMany
     */
    public function files(): BelongsToMany
    {
        return $this->belongsToMany(
            File::class,
            self::FILE_TABLE,
            'comment_id',
            'file_id'
        );
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
