<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DiagnosticCard extends Model
{
    protected $fillable = [
        'bus_id', 'status', 'date_scheduled', 'diagnostic_card_template_id', 'notes', 'user_id', 'type'
    ];
    protected $dates = ['date_exec', 'date_scheduled'];

    const STATUS_OK = 'ok';
    const STATUS_TO_REPAIR_LATER = 'to_repair_later';
    const STATUS_TO_REPAIR = 'to_repair';

    const STATUSES = [
        self::STATUS_OK,
        self::STATUS_TO_REPAIR_LATER,
        self::STATUS_TO_REPAIR
    ];

    const TYPE_TAKE = 'take';
    const TYPE_PUT = 'put';
    const TYPE_REVIEW = 'review';

    const TYPES = [
        self::TYPE_TAKE,
        self::TYPE_PUT,
        self::TYPE_REVIEW
    ];

    //Relationships
    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function items()
    {
        return $this->hasMany(DiagnosticCardReviewActTemplateItem::class, 'diagnostic_card_id', 'id');
    }

    public function template()
    {
        return $this->belongsTo(DiagnosticCardTemplate::class, 'diagnostic_card_template_id', 'id');
    }

    public function bus_variable()
    {
        return $this->morphOne(BusVariable::class, 'imageable');
    }

    public function user_taken_bus()
    {

        return $this->hasOne(UserTakenBus::class, 'diagnostic_card_id', 'id');
    }

    //Mutators
    public function setDateExecAttribute($date)
    {
        return $this->attributes['date_exec'] = Carbon::createFromFormat('d.m.Y', $date);
    }

    public function setDateScheduledAttribute($date)
    {
        return $this->attributes['date_scheduled'] = Carbon::createFromFormat('d.m.Y', $date);
    }


    //Accessor
    public function getTypeAttribute()
    {
/*        if ($this->user_taken_bus && $this->user_taken_bus->type) {
            $type = $this->user_taken_bus->type;
        } else {
            $type = UserBus::TYPE_REVIEW;
        }*/
        if(empty($this->attributes['type'])){
            $this->attributes['type'] = self::TYPE_REVIEW;
        }
        return $this->attributes['type'];
    }


    //Scopes
    public function scopeFilter($query, $data)
    {
        $status = array_get($data, 'status');
        $name = array_get($data, 'name');
        $dateExec = array_get($data, 'date_exec');
        $master = array_get($data, 'master_id');
        $contractor = array_get($data, 'contractor_id');
        $query
            ->when($status, function ($q) use ($status) {
                return $q->where('status', $status);
            })
            ->when($master, function ($q) use ($master) {
                return $q->where('master_id', $master);
            })
            ->when($contractor, function ($q) use ($contractor) {
                return $q->where('contractor_id', $contractor);
            })
            ->when($name, function ($q) use ($name) {
                return $q->where('name', 'like', '%' . $name . '%');
            })
            ->when($dateExec, function ($q) use ($dateExec) {
                return $q->whereDate('date_exec', $dateExec);
            });
        return $query;
    }


    public function scopeOfStatus($q, $status)
    {
        return $q->whereStatus($status);
    }

}
