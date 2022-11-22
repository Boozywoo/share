<?php

namespace App\Models;

use App\Traits\ModelTableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class WishesType extends Model
{
    use ModelTableTrait;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'status',
        'notification_type_id',
        'notifi_supervisor',
        'departments_notification',
        'denied',
        'view',
    ];

    /**
     * @var string
     */
    protected $table = 'wishes_types';

    /**
     * @return BelongsToMany
     */
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class);
    }

    /**
     * @return BelongsTo
     */
    public function notificationType(): BelongsTo
    {
        return $this->belongsTo(NotificationType::class, 'notification_type_id', 'id');
    }

    /**
     * @return array
     */
    public function getCheckDepartmentsNotification(): array
    {
        return (array)json_decode($this->departments_notification);
    }
}
