<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairSparePart extends Model
{
    protected $fillable = ['status', 'user_id', 'spare_part_id', 'repair_id', 'count'];

    const STATUS_NOT_PROCESSED = 'not_processed';
    const STATUS_IN_STOCK = 'in_stock';
    const STATUS_OUT_OF_STOCK = 'out_of_stock';
    const STATUS_ORDERED = 'ordered';
    const STATUS_ISSUED = 'issued';
    const STATUS_INSTALLED = 'installed';
    const STATUS_RETURNED = 'returned';
    const STATUS_CANCELED = 'canceled';

    const STATUSES = [
        self::STATUS_NOT_PROCESSED,
        self::STATUS_IN_STOCK,
        self::STATUS_OUT_OF_STOCK,
        self::STATUS_ORDERED,
        self::STATUS_ISSUED,
        self::STATUS_INSTALLED,
        self::STATUS_RETURNED,
        self::STATUS_CANCELED
    ];
    const STATUSES_FINISHED = [
        self::STATUS_INSTALLED,
        self::STATUS_RETURNED,
        self::STATUS_CANCELED
    ];


    public function item()
    {
        return $this->hasOne(SparePart::class, 'id', 'spare_part_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    //Scopes
    public function scopeFilter($query, $data)
    {
        $status = array_get($data, 'status');
        $query
            ->when($status, function ($q) use ($status) {
                return $q->where('status', $status);
            });
        return $query;
    }

}
