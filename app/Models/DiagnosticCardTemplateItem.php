<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosticCardTemplateItem extends Model
{
    protected $fillable = ['title','description','status','diagnostic_card_template_id','is_photo','is_comment'];

    const STATUS_ACTIVE = 'active';
    const STATUS_NOT_ACTIVE = 'not_active';

    const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_NOT_ACTIVE
    ];

    const IS_PHOTO = 1;
    const IS_NOT_PHOTO = 0;

    const PHOTO = [
        self::IS_PHOTO,
        self::IS_NOT_PHOTO
    ];

    const IS_COMMENT = 1;
    const IS_NOT_COMMENT = 0;

    const COMMENT = [
        self::IS_COMMENT,
        self::IS_NOT_COMMENT
    ];

    //relationships

    public function template(){
        return $this->hasOne(DiagnosticCardTemplate::class);
    }

    //scopes
    public function scopeOfStatus($q, $status)
    {
        return $q->whereStatus($status);
    }

}
