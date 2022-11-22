<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    protected $fillable = [
        'bus_id', 'type', 'status',
        'date_to', 'date_from', 'comment', 'name','date_end'
    ];

    protected $dates = ['date_to', 'date_from','date_end'];

    const STATUS_ORDER = 'order';
    const STATUS_WAIT = 'wait';
    const STATUS_REPAIR = 'repair';
    const STATUS_OF_REPAIR = 'of_repair';
    const STATUS_WITHOUT_REPAIR = 'without_repair';
    const STATUS_CANCELED = 'canceled';

    const STATUSES = [
        self::STATUS_ORDER,
        self::STATUS_WAIT,
        self::STATUS_REPAIR,
        self::STATUS_OF_REPAIR,
        self::STATUS_WITHOUT_REPAIR,
        self::STATUS_CANCELED
    ];
    const CLOSED_STATUSES = [
        self::STATUS_OF_REPAIR,
        self::STATUS_WITHOUT_REPAIR,
        self::STATUS_CANCELED
    ];

    const TYPE_TO = 'TO';
    const TYPE_EMERGENCY = 'emergency';
    const TYPE_CURRENT = 'current';

    const TYPES = [
        self::TYPE_TO,
        self::TYPE_EMERGENCY,
        self::TYPE_CURRENT,
    ];

    const FILTER_REPAIR_ORDERS = 'order';
    const FILTER_WAITING_FOR_REPAIR = 'wait';
    const FILTER_REPAIR_CARS = 'repair';
    const FILTER_MAINTENANCE = 'TO';
    const FILTER_REPEAT_VISIT = 'repeat_visit';

    const FILTER_STATUSES = [
        'STATUSES' => [
            self::FILTER_REPAIR_ORDERS,
            self::FILTER_WAITING_FOR_REPAIR,
            self::FILTER_REPAIR_CARS,
        ],
        'TYPES' => [
            self::FILTER_MAINTENANCE,
//            self::FILTER_REPEAT_VISIT,
        ]
    ];

    //Relationships
    public function bus()
    {
        return $this->hasOne(Bus::class, 'id', 'bus_id');
    }

    public function order_outfit()
    {
        return $this->hasOne(RepairOrderOutfit::class, 'repair_id');
    }

    public function diagnostic_card()
    {
        return $this->hasOne(RepairCard::class, 'repair_id');
    }

    public function spare_parts()
    {
        return $this->hasMany(RepairSparePart::class, 'repair_id', 'id');
    }
    public function card_templates(){

        return $this->belongsToMany(RepairCardTemplate::class, 'repair_order_card_templates','repair_id','repair_card_template_id');
    }

    //Mutators
    public function getPrettyDateAttribute()
    {
        return $this->date_from->format('d-m-Y') . ' - ' . $this->date_to->format('d-m-Y');
    }

    public function setDateFromAttribute($date)
    {
        return $this->attributes['date_from'] = Carbon::createFromFormat('d.m.Y', $date);
    }

    public function setDateToAttribute($date)
    {
        return $this->attributes['date_to'] = Carbon::createFromFormat('d.m.Y', $date);
    }

    public function getSparePartInStockAttribute()
    {
        $parts = $this->spare_parts()->get()->groupBy('spare_part_id')
            ->filter(function ($group) {
                $group = $group->sortByDesc('created_at');
                return in_array($group->first()->status, [RepairSparePart::STATUS_OUT_OF_STOCK, RepairSparePart::STATUS_NOT_PROCESSED, RepairSparePart::STATUS_ORDERED, RepairSparePart::STATUS_IN_STOCK]);
            });
        $count = $parts->count();

        $inStock = $parts->filter(function ($group) {
            $group = $group->sortByDesc('created_at');
            return  $group->first()->status == RepairSparePart::STATUS_IN_STOCK;
        })->count();
        if ($inStock > 0 && $inStock >= $count) {
            return 'all';
        } elseif ($inStock > 0) {
            return 'in';
        } elseif ($count == 0) {
            return 'not';
        } else {
            return 'out';
        }

    }

    //Scopes
    public function scopeFilter($query, $data)
    {
        $status = array_get($data, 'status');
        $type = array_get($data, 'type');
        $busId = array_get($data, 'bus_id');
        $betweenDateFrom = array_get($data, 'between_date_from');
        $name = array_get($data, 'name');
        $id = array_get($data, 'id');
        $bus_number = array_get($data, 'bus_number');
        $bus_garage_number = array_get($data, 'bus_garage_number');
        $query
            ->when($status, function ($q) use ($status) {
                return $q->where('status', $status);
            })
            ->when($type, function ($q) use ($type) {
                return $q->where('type', $type);
            })
            ->when($busId, function ($q) use ($busId) {
                return $q->where('bus_id', $busId);
            })
            ->when($name, function ($q) use ($name) {
                return $q->where('name', 'like', "%$name%");
            })
            ->when($id, function ($q) use ($id) {
                return $q->where('id', $id);
            })
            ->when($bus_number, function ($q) use ($bus_number) {
                $buses = Bus::where('number', 'like', "%$bus_number%")->pluck('id')->toArray();
                return $q->whereIn('bus_id', $buses);
            })
            ->when($bus_garage_number, function ($q) use ($bus_garage_number) {
                $buses = Bus::where('garage_number', 'like', "%$bus_garage_number%")->pluck('id')->toArray();
                return $q->whereIn('bus_id', $buses);
            })
            ->when($betweenDateFrom, function ($q) use ($betweenDateFrom) {
                return $q->whereBetween('date_from', [$betweenDateFrom['dateFrom'], $betweenDateFrom['dateTo']]);
            })
        ;
        return $query;
    }

    public function scopeOfStatus($q, $status)
    {
        return $q->whereStatus($status);
    }
}
