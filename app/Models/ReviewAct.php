<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ReviewAct extends Model
{
    protected $fillable = [
        'bus_id', 'name', 'review_act_template_id', 'body', 'diagnostic_card_id'
    ];

    const STATUS_OK = 'ok';
    const STATUS_WARNING = 'warning';

    const STATUSES = [
        self::STATUS_OK,
        self::STATUS_WARNING
    ];

    //Relationships
    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function items(){

        return $this->hasMany(ReviewActItem::class,'review_act_id','id');
    }
    public function template(){
        return $this->belongsTo(ReviewActTemplate::class,'review_act_template_id', 'id');
    }
    
    public function diagnostic_card()
    {
        return $this->belongsTo(DiagnosticCard::class, 'diagnostic_card_id');
    }

}
