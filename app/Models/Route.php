<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\Order\StationIntervalsService;

class Route extends Model
{
    protected $fillable = [
        'name', 'name_tr', 'status', 'interval', 'is_international', 'required_inputs', 'is_taxi', 'is_regular', 'is_transfer',
        'discount_front','discount_front_type','discount_return_ticket', 'discount_return_ticket_type', 'discount_child', 'is_egis',
        'discount_child_type', 'bonus_agent', 'bonus_agent_type', 'bonus_driver', 'bonus_driver_type', 'is_line_price', 'currency_id',
        'phone_code', 'discount_mobile', 'discount_mobile_type', 'partial_prepaid', 'position', 'is_route_taxi', 'flight_type', 
        'allow_ind_transfer', 'mileage', 'time_hidden_tour_front'
    ];

    const STATUS_ACTIVE         = 'active';
    const STATUS_DISABLE        = 'disable';
    const STATUS_INACTIVE_FRONT = 'inactive_front';

    const INTERNATIONAL         = '1';
    const NATIONAL              = '0';

    const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE_FRONT,
        self::STATUS_DISABLE,
    ];
    const IS_INTERNATIONAL = [
        self::NATIONAL,
        self::INTERNATIONAL,
    ];

    //Relationships

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }

    public function driverPivot()
    {
        return \DB::table('route_driver')->where('route_id', $this->id);
    }

    public function tours()
    {
        return $this->hasMany(Tour::class);
    }

    public function sales()
    {
        return $this->belongsToMany(Sale::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function addServices()
    {
        return $this->belongsToMany(AddService::class, 'route_add_service')->where('status', 'active');
    }

    public function stations()
    {
        return $this->belongsToMany(Station::class)
            ->whereIn('status',[Station::STATUS_ACTIVE, Station::STATUS_COLLECT])
            ->withPivot('order', 'time', 'interval', 'cost_start', 'cost_finish', 'central', 'tickets_from', 'tickets_to')
            ->orderBy('pivot_order');
    }

    public function stationsUntil($stationId)
    {
        $station = $this->stations->where('id', $stationId)->first()->pivot->order;
        return $this->stations()->wherePivot('order', '<=', $station)->get();
    }

    public function stationsFrom($stationId)
    {
        $station = $this->stations->where('id', $stationId)->first()->pivot->order;
        return $this->stations()->wherePivot('order', '>=', $station)->get();
    }

    public function stationsFromTo($stationFromId, $stationToId)
    {
        $stationFrom = $this->stations->where('id', $stationFromId)->first()->pivot->order;
        $stationTo = $this->stations->where('id', $stationToId)->first()->pivot->order;
        return $this->stations()
            ->wherePivot('order', '>', $stationFrom)
            ->wherePivot('order', '<', $stationTo)
            ->get();
    }

    public function stationsCollect()
    {
        return $this->belongsToMany(Station::class)
            ->where(['status' => Station::STATUS_ACTIVE])
            ->withPivot('order', 'time', 'interval', 'cost_start', 'cost_finish')
            ->orderBy('pivot_order');
    }

    public function stationsTickets()       //  флаг tickets_from - не отображать в поиске рейсов (откуда) на главной странице эту остановку
    {
        return $this->belongsToMany(Station::class)
            ->where(['status' => Station::STATUS_ACTIVE])
            ->where('tickets_from', true)
            ->withPivot('order')
            ->orderBy('pivot_order');
    }

    public function stationsActive()
    {
        return $this->belongsToMany(Station::class)
            ->where(['status' => Station::STATUS_ACTIVE])
            ->withPivot('order', 'time', 'interval', 'cost_start', 'cost_finish')
            ->orderBy('pivot_order');
    }

    public function stationsIds()
    {
        return $this->belongsToMany(Station::class)->pluck('id');
    }

    //Scopes
    public function scopeFilter($query, $data)
    {
        $name = array_get($data, 'name');
        $status = array_get($data, 'status');
        $is_egis = array_get($data, 'is_egis');
        if ($status) $status = (is_array($status))? $status : [$status];
        $userId = array_get($data, 'user_id');
        $query
            ->when($name, function($q) use($name){
                return $q->where('name', 'like', "%$name%");
            })
            ->when($status, function ($q) use ($status) {
                return $q->whereIn('status', $status);
            })
            ->when($is_egis, function ($q) use ($is_egis) {
                return $q->where('is_egis', $is_egis);
            })
            ->when($userId, function ($q) use ($userId) {
                return $q->whereHas('users', function ($q) use ($userId) {
                    $q->where('id', $userId);
                });
            });

        return $query;
    }

    public static function prepareData($data)
    {
        $data['required_inputs'] = implode(',',$data['required_inputs']);
        return $data;
    }

    public function airport()    // Остановка аэропорта для направлений типа трансфер (is_transfer)
    {
        if (!$this->is_transfer) {
            return null;
        }
        if ($this->flight_type == 'departure') {
            return $this->stationsActive->last();
        } elseif ($this->flight_type == 'arrival') {
            return $this->stationsActive->first();
        }
    }

    public function getIntervalActive()
    {
        $time = array();
        $firstActive = $this->stations->where('status', 'active')->first();
        $firstActive = isset($firstActive) ? $firstActive->id : null;

        $lastActive  = $this->stations->where('status', 'active')->last();
        $lastActive  = isset($lastActive) ? $lastActive->id : null;
        if ($firstActive && $firstActive) {
            $time = StationIntervalsService::index($this->id, $firstActive, $lastActive);
        }
        $totalTime = 0;
        foreach($time as $item)
            $totalTime += abs($item);
        return $totalTime;
    }

    public function getTypeNameAttribute()   // Возвращает тип направления - обычный, такси, маршрутное такси или трансфер. Только один тип должен быть = 1, остальные = 0
    {
        $types = ['is_regular', 'is_taxi', 'is_route_taxi','is_transfer'];
        foreach ($types as $item)   {
            if ($this->$item) {
                return $item;
            }
        }
    }

    public function getTextInputsAttribute()
    {
        $requiredInputs = explode(',', $this->required_inputs);

        $textInputs = [];
        foreach ($requiredInputs as $input)  {
            if (in_array($input, OrderPlace::FILLABLE_TEXT))    {
                $textInputs[] = $input;
            }
        }
        return $textInputs;
    }

    public function getRequiredInputsArrayAttribute()
    {
        return explode(',', $this->required_inputs);
    }

}
