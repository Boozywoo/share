<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewActTemplateItem extends Model
{
    protected $fillable = ['review_act_template_id','name','status','is_photo','is_comment'];

    const STATUS_ACTIVE = 'active';
    const STATUS_NOT_ACTIVE = 'not_active';

    const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_NOT_ACTIVE
    ];

    //relationships

    public function template(){
        return $this->hasOne(ReviewActTemplate::class);
    }

    //scopes
    public function scopeOfStatus($q, $status)
    {
        return $q->whereStatus($status);
    }

}
