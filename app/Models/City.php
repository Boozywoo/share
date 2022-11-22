<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeZone;
use DateTime;

class City extends Model
{
    protected $fillable = [
        'name', 'name_tr', 'timezone', 'status','is_rent', 'is_transfer',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_DISABLE = 'disable';

    const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_DISABLE,
    ];

    private static $timezone_list = array();
    //Relationships
    public function stations()
    {
        return $this->hasMany(Station::class);
    }

    public function streets()
    {
        return $this->hasMany(Street::class);
    }

    //Scopes
    public function scopeFilter($query, $data)
    {
        $name = array_get($data, 'name');
        $status = array_get($data, 'status');
        $routeId = array_get($data, 'route_id');
        $isRent = array_get($data, 'is_rent');
        $query
            ->when($name, function($q) use($name){
                return $q->where('name', 'like', "%$name%");
            })
            ->when($status, function ($q) use ($status) {
                return $q->where('status', $status);
            })
            ->when($isRent, function ($q) use ($isRent) {
                return $q->where('is_rent', $isRent);
            })
            ->when($routeId, function ($q) use ($routeId) {
                return $q->whereHas('stations', function ($q) use ($routeId) {
                    $q->filter(['route_id' => $routeId]);
                });
            })
        ;
        return $query;
    }
    
    static function getTimezoneList() 
    {
        if ( count(self::$timezone_list) == 0 ) 
        {
            $timezones = DateTimeZone::listIdentifiers( DateTimeZone::ALL );
            
            $timezone_offsets = array();
            foreach( $timezones as $timezone )
            {
                $tz = new DateTimeZone($timezone);
                $timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
            }
            
            // sort timezone by offset
            asort($timezone_offsets);
            
            foreach( $timezone_offsets as $timezone => $offset )
            {
                $offset_prefix = $offset < 0 ? '-' : '+';
                $offset_formatted = gmdate( 'H:i', abs($offset) );
                
                $pretty_offset = "UTC${offset_prefix}${offset_formatted}";
                
                self::$timezone_list[$timezone] = "(${pretty_offset}) $timezone";
            }
            
            asort(self::$timezone_list);
            
        }
        return self::$timezone_list;
    }
    
    public function getFullTimezoneAttribute()
    {
        return City::getTimezoneList()[$this->timezone];
    }
    
    public function getShortTimezoneAttribute()
    {
        return strstr(City::getTimezoneList()[$this->timezone],' ',true);
    }

    public function getUTCOffsetAttribute()
    {
        $tz = new DateTimeZone($this->timezone);
        $now = new DateTime("now", $tz);
        return $now->format('P');
    }

}
