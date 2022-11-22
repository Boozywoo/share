<?php

namespace App\Models;

use App\Traits\ImageableTrait;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use ImageableTrait;

    protected $fillable = [
        'name', 'percent', 'status','is_percent', 'value', 'apply_to_all_orders'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_DISABLE = 'disable';

    const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_DISABLE,
    ];

    const IMAGE_TYPE_IMAGE = 'image';

    const IMAGES_PARAMS = [
        self::IMAGE_TYPE_IMAGE => [
            'multiple' => false,
            'params' => [
                'admin' => [
                    'w' => 50,
                    'fit' => 'max',
                ],
            ],
        ],
    ];

    public function routes()
    {
        return $this->belongsToMany(Route::class);
    }

    //Scopes
    public function scopeFilter($query, $data)
    {
        $name = array_get($data, 'name');
        $status = array_get($data, 'status');
        $query
            ->when($name, function ($q) use ($name) {
                return $q->where('name', 'like', "%$name%");
            })
            ->when($status, function ($q) use ($status) {
                return $q->where('status', $status);
            });
        return $query;
    }

    public static function SelectStatuses()
    {
        $response = array('0' => trans('admin.settings.statuses.status_not'));
        $statuses = self::get();
        foreach ($statuses as $status)
            if($status->status == 'active') {
                $response[$status->id] = $status->name;
            }
        return $response;
    }
}
