<?php

namespace App\Models;

use App\Traits\ClearPhone;
use App\Traits\PhoneTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property json $departments_notification
 */
class NotificationType extends Model
{
    protected $fillable = [
        'name', 'slug', 'approved', 'read', 'denied', 'view', 'role_id',
        'departments_notification',
    ];

    protected $casts = [
        'departments_notification' => 'json',
    ];

    protected $table = 'notification_types';
    public $timestamps = false;


    public function departments()
    {
        return $this->belongsToMany(Department::class);
    }

}
