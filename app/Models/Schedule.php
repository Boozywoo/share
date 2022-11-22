<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use App\Services\Prettifier;

/**
 * App\Models\Schedule
 *
 * @property Carbon $date_start
 * @property Carbon $date_finish
 * @property-read ScheduleDay $scheduleDays
 * @mixin \Eloquent
 */
class Schedule extends Model
{
    protected $fillable = [
        'date_start', 'date_finish',
        'bus_id', 'route_id', 'status', 'repeat', 'reservation_by_place', 'is_collect', 'is_days_rotate',
        'flight_ac_code', 'flight_number', 'flight_time', 'flight_offset',
    ];

    protected $dates = ['date_start', 'date_finish'];

    const DAY_MONDAY = 1;
    const DAY_TUESDAY = 2;
    const DAY_WEDNESDAY = 3;
    const DAY_THURSDAY = 4;
    const DAY_FRIDAY = 5;
    const DAY_SATURDAY = 6;
    const DAY_SUNDAY = 0;

    const DAYS = [
        self::DAY_MONDAY,
        self::DAY_TUESDAY,
        self::DAY_WEDNESDAY,
        self::DAY_THURSDAY,
        self::DAY_FRIDAY,
        self::DAY_SATURDAY,
        self::DAY_SUNDAY,
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_DISABLE = 'disable';
    const STATUS_VIRTUAL = 'virtual';

    const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_DISABLE,
        self::STATUS_VIRTUAL,
    ];

    //Relationships
    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function tours()
    {
        return $this->hasMany(Tour::class);
    }

    public function scheduleDays()
    {
        return $this->hasMany(ScheduleDay::class);
    }

    //Mutators
    public function getPrettyDateAttribute()
    {
        $result = $this->date_start->format('d-m-Y'). ' - ' .$this->date_finish->format('d.m.Y');
        $result .= '<br>'. $this->date_start->format('H:i'). ' - ' .$this->date_finish->format('H:i');
        return $result;
    }

    public function getPrettyFlightTimeAttribute()
    {
        return empty($this->flight_time) ? '' : Prettifier::prettifyTime($this->flight_time);
    }

    public function getDaysMaskAttribute()   //  Недельная маска календаря для указания дней недели, когда выполняется рейс. Первый символ маски соответствует понедельнику, второй – вторнику и т.д. Если рейс выполняется в определенный день недели, то ставится отметка “1”, если нет, ставится “0”. Например, если указана маска daysmask="0001000", то рейс отправляется только по четвергам.
    {
        $mask = '0000000';
        foreach($this->scheduleDays as $item)  {
            if ($item->day < 7) {
                $item->day = $item->day ? $item->day : 7;       // Заменяем 0 на 7 (7й день - воскресенье)
                $mask = substr_replace($mask, '1', $item->day - 1, 1);
            }
        }
        return $mask;
    }

    public function getWeekDaysAttribute()   //  Список номер дней недели, когда выполняется рейс одной строкой. К примеру, если рейс только по будням будет '12345'
    {
        $mask = '';
        foreach($this->scheduleDays->sortBy('day') as $item)  {
            if ($item->day < 7) {
                $item->day = $item->day ? $item->day : 7;       // Заменяем 0 на 7 (7й день - воскресенье)
                $mask .= $item->day;
            }
        }
        return $mask;
    }

    public function getPrettyPriceAttribute()
    {
        $samePrice = true;
        $prices = $this->scheduleDays->pluck('price');
        $firstPrice = $prices->first();
        foreach($prices as $dayPrice) {
            if ($dayPrice != $firstPrice) {
                $samePrice = false;
                break;
            }
        }
        if ($samePrice) {
            return $firstPrice;
        } else {
            return 'от '.$prices->min();
        }
    }

    //Scopes
    public function scopeFilter($query, $data)
    {
        $id = array_get($data, 'id');
        $flight_ac_code = array_get($data, 'flight_ac_code');
        $flight_number = array_get($data, 'flight_number');
        $flight_type = array_get($data, 'flight_type');
        $status = array_get($data, 'status');
        $busId = array_get($data, 'bus_id');
        $routeId = array_get($data, 'route_id');
        $routes = array_get($data, 'routes');
        $query
            ->when($id, function ($q) use ($id) {
                return $q->where('id', $id);
            })
            ->when($status, function ($q) use ($status) {
                return $q->where('status', $status);
            })
            ->when($busId, function ($q) use ($busId) {
                return $q->where('bus_id', $busId);
            })
            ->when($routeId, function ($q) use ($routeId) {
                return $q->where('route_id', $routeId);
            })
            ->when($flight_ac_code, function ($q) use ($flight_ac_code) {
                return $q->where('flight_ac_code', 'LIKE', '%'.$flight_ac_code.'%');
            })
            ->when($flight_number, function ($q) use ($flight_number) {
                return $q->where('flight_number', 'LIKE', '%'.$flight_number.'%');
            })
            ->when($flight_type, function ($q) use ($flight_type) {
                return $q->whereHas('route', function ($q) use ($flight_type) {
                    $q->where('flight_type', $flight_type);
                });
            })
            ->when($routes, function ($q) use ($routes) {
                if ($routes instanceof Collection) $routes = $routes->toArray();
                if (!is_array($routes)) $routes = [$routes];
                return $q->whereIn('route_id', $routes);
            })
        ;
        return $query;
    }
}
