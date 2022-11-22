<?php

namespace App\Models\OperationalTask;

use App\Models\File;
use App\Models\User;
use App\Traits\ModelTableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * @property int $applicant_id
 * @property int $responsible_id
 * @property string $subject
 * @property string $description
 * @property string $status
 */
class OperationalTask extends Model
{
    use ModelTableTrait;

    const FILE_TABLE = 'operational_tasks_files';
    public $history;
    protected $fillable = [
        'applicant_id',
        'responsible_id',
        'subject',
        'description',
        'status'
    ];

    /**
     * @return BelongsToMany
     */
    public function files(): BelongsToMany
    {
        return $this->belongsToMany(
            File::class,
            self::FILE_TABLE,
            'task_id',
            'file_id'
        );
    }

    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(OperationalTaskComment::class, 'task_id');
    }

    /**
     * @return HasOne
     */
    public function applicant(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'applicant_id');
    }

    /**
     * @return HasOne
     */
    public function responsible(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'responsible_id');
    }

    /**
     * @return HasMany
     */
    public function statuses(): HasMany
    {
        return $this->hasMany(OperationalTaskStatus::class, 'task_id');
    }

    /**
     * @param Collection $history
     */
    public function setHistory(Collection $history)
    {
        $this->history = !$history->isEmpty() ? $history : new Collection();
    }

    /**
     * @return HasOne
     */
    public function lastComment(): HasOne
    {
        return $this->hasOne(OperationalTaskComment::class, 'task_id')
            ->orderBy('created_at', 'desc');
    }
}
