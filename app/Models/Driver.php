<?php

namespace App\Models;

use App\Traits\ClearPhone;
use App\Traits\ImageableTrait;
use App\Traits\PhoneTrait;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Driver extends Authenticatable
{
    use PhoneTrait, ClearPhone, ImageableTrait;
    use Notifiable;

    protected $fillable = [
        'full_name', 'phone', 'work_phone', 'company_id',
        'birth_day', 'status', 'reputation', 'password',
        'med_day', 'driver_license', 'end_visa', 'day_before_med_day',
        'day_before_end_visa', 'day_before_driver_license',
        'last_name', 'middle_name', 'doc_type', 'doc_number', 'gender', 'country_id', 'is_admin_driver'
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];
    protected $appends = ['name'];
    protected $dates = ['birth_day'];

    const STATUS_ACTIVE = 'active';
    const STATUS_DISABLE = 'disable';
    const STATUS_SYSTEM = 'system';

    const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_DISABLE,
        self::STATUS_SYSTEM,
    ];

    const REPUTATION_NEW = 'new';
    const REPUTATION_RELIABLE = 'reliable';
    const REPUTATION_PROBLEM = 'problem';

    const REPUTATIONS = [
        self::REPUTATION_NEW,
        self::REPUTATION_RELIABLE,
        self::REPUTATION_PROBLEM,
    ];

    const FINE_TYPE_LIGHT = 'light';
    const FINE_TYPE_MIDDLE = 'middle';
    const FINE_TYPE_CRITICAL = 'critical';


    const FINE_TYPES = [
        self::FINE_TYPE_LIGHT,
        self::FINE_TYPE_MIDDLE,
        self::FINE_TYPE_CRITICAL
    ];


    const IMAGE_TYPE_IMAGE = 'image';

    const IMAGES_PARAMS = [
        self::IMAGE_TYPE_IMAGE => [
            'multiple' => false,
            'params' => [
                'admin' => [
                    'w' => 50,
                    'fit' => 'max',
                ],
            ],
        ],
    ];

    //Relationships
    public function buses()
    {
        return $this->morphToMany(Bus::class, 'imageable','user_bus');
    }

    public function fines()
    {
        return $this->hasMany(DriverFines::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function token()
    {
        return $this->hasOne(Token::class);
    }

    public function reviewsPositive()
    {
        return $this->reviews()->filter(['type' => Review::TYPE_POSITIVE]);
    }

    public function reviewsNegative()
    {
        return $this->reviews()->filter(['type' => Review::TYPE_NEGATIVE]);
    }

    public function routes()
    {
        return $this->belongsToMany(Route::class, 'route_driver')->withPivot('pay_order_fix', 'pay_order_percent', 'pay_month_fix');
    }

    public function tours()
    {
        return $this->hasMany(Tour::class)->whereNull('is_rent')->filter(['status' => Tour::STATUS_ACTIVE]);
    }

    public function toursToday()
    {
        return $this->tours()->where(function($query) 
        { 
            $today = date('Y-m-d');
            $date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
            $yesterday = date('Y-m-d', strtotime($date .' -1 day'));
              
            $matchThese = ['date_start' => $yesterday, 'date_finish' => $today];
            $orThose = ['date_start' => $today];

            $query->where($matchThese)->orWhere($orThose);
        });
    }

    public function toursTomorrow()
    {
        return $this->tours()->where(function($query) 
        { 
            $today = date('Y-m-d');
            $date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
            $tomorrow = date('Y-m-d', strtotime($date .' +1 day')); 
              
            $matchThese = ['date_start' => $today, 'date_finish' => $tomorrow];
            $orThose = ['date_start' => $tomorrow];

            $query->where($matchThese)->orWhere($orThose);
        });
    }

    public function toursWeek()
    {
        $today = date('Y-m-d');
        $endOfWeek = (date('l') == 'Sunday') ? date('Y-m-d') : date('Y-m-d', strtotime('sunday'));

        return $this->tours()->whereBetween('date_start', [$today, $endOfWeek]);
    }

    public function toursMonth()
    {
        $today = date('Y-m-d'); 
        $last_day_this_month  = date('Y-m-t');

        return $this->tours()->whereBetween('date_start', [$today, $last_day_this_month]);
    }

    public static function getSystemDriver()
    {
        return self::whereStatus(self::STATUS_SYSTEM)->first();
    }

    public function getNameAttribute()
    {
        return $this->full_name .' '. $this->middle_name .' '. $this->last_name;
    }

    //Mutators
    public function setBirthDayAttribute($date)
    {
        return $this->attributes['birth_day'] = $date ? Carbon::createFromFormat('d.m.Y', $date) : null;
    }

    public function setDriverLicenseAttribute($date)
    {
        return $this->attributes['driver_license'] = $date ? Carbon::createFromFormat('d.m.Y', $date) : null;
    }

    public function setMedDayAttribute($date)
    {
        return $this->attributes['med_day'] = $date ? Carbon::createFromFormat('d.m.Y', $date) : null;
    }

    public function setEndVisaAttribute($date)
    {

        return $this->attributes['end_visa'] = $date ? Carbon::createFromFormat('d.m.Y', $date) : null;
    }

    public function setPasswordAttribute($value)
    {
        if ($value) $this->attributes['password'] = bcrypt($value);
    }

    public function getInitialsAttribute()
    {
        return $this->last_name . " " . mb_substr($this->full_name,0, 1) . "." . mb_substr($this->middle_name,0, 1).".";
    }

    //Scopes
    public function scopeFilter($query, $data)
    {
        $fullName = array_get($data, 'full_name');
        $status = array_get($data, 'status');
        $reputation = array_get($data, 'reputation');
        $companies = array_get($data, 'companies');
        $query
            ->when($fullName, function ($q) use ($fullName) {
                return $q->where('full_name', 'like', "%$fullName%");
            })
            ->when($status, function ($q) use ($status) {
                return $q->where('status', $status);
            })
            ->when($reputation, function ($q) use ($reputation) {
                return $q->where('reputation', $reputation);
            })
            ->when($companies, function ($q) use ($companies) {
                $q->whereIn('company_id', $companies);
            });
        return $query;
    }
}
