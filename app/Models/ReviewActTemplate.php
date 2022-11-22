<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewActTemplate extends Model
{
    protected $fillable = ['name', 'status', 'company_id', 'diagnostic_card_template_id'];

    const STATUS_ACTIVE = 'active';
    const STATUS_NOT_ACTIVE = 'not_active';

    const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_NOT_ACTIVE
    ];
    //relationships

    public function items(){

        return $this->hasMany(ReviewActTemplateItem::class, 'review_act_template_id', 'id');
    }
    
    public function diagnosticCardTemplate()
    {
        return $this->belongsToMany(DiagnosticCardTemplate::class, 'diagnostic_card_template_review_act_templates', 'review_act_template_id', 'diagnostic_card_template_id');
    }


    //scopes
    public function scopeOfStatus($q, $status)
    {
        return $q->whereStatus($status);
    }

    public function scopeFilter($query, $data)
    {
        $companies = array_get($data, 'companies');
        $query
            ->when($companies, function ($q) use ($companies) {
                $q->whereIn('company_id', $companies);
            });
        return $query;
    }

}
