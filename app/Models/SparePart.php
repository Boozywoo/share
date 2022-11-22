<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SparePart extends Model
{
    protected $fillable = ['name', 'status'];

    const STATUS_ACTIVE = 'active';
    const STATUS_NOT_ACTIVE = 'not_active';

    const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_NOT_ACTIVE
    ];


    public function childs()
    {
        return $this->hasMany(SparePart::class, 'parent_id', 'id');
    }

    public function active_childs()
    {
        return $this->hasMany(SparePart::class, 'parent_id', 'id')->whereStatus('active');
    }

    public function parent()
    {
        return $this->hasOne(SparePart::class, 'id', 'parent_id');
    }
}
