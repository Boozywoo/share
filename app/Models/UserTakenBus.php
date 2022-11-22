<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTakenBus extends Model
{
    protected $fillable = ['started_at', 'ended_at', 'imageable_id', 'imageable_type', 'bus_id', 'condition', 'is_card', 'diagnostic_card_id', 'status'];
    public $timestamps = true;

//    protected $dates = ['started_at', 'ended_at'];

    const STATUS_CREATED = 'created';
    const STATUS_TAKEN = 'taken';
    const STATUS_RETURNED = 'returned';
    const STATUS_CANCELED = 'canceled';

    const STATUSES = [
        self::STATUS_CREATED,
        self::STATUS_TAKEN,
        self::STATUS_RETURNED,
        self::STATUS_CANCELED
    ];

    public function imageable()
    {
        return $this->morphTo();
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function user()
    {
        return $this->morphTo('imageable');
    }

    public function driver()
    {
        return $this->morphTo(Driver::class, 'imageable');
    }

    public function diagnostic_cards()
    {
        return $this->belongsToMany(DiagnosticCard::class, 'taken_bus_diagnostic_card', 'user_taken_bus_id', 'diagnostic_card_id');
    }

    public function diagnostic_card_type_take(){
        return $this->diagnostic_cards()->whereType(DiagnosticCard::TYPE_TAKE)->latest()->first();
    }
    public function diagnostic_card_type_put(){
        return $this->diagnostic_cards()->whereType(DiagnosticCard::TYPE_PUT)->latest()->first();
    }

}
