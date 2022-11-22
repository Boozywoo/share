<?php

namespace App\Models;

use App\Traits\ImageableTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use ImageableTrait;

    protected $fillable = [
        'company_id', 'template_id', 'bus_type_id', 'max_rent_time',
        'name', 'name_tr', 'number', 'status', 'places', 'revision_day', 'insurance_day', 'is_rent', 'password',
        'universal_day', 'universal_field',
        'day_before_insurance', 'day_before_revision', 'garage_number', 'operating_mileage',
        'diagnostic_card_template_id', 'repair_card_type_id', 'location_status',
        'vin', 'driver_category', 'year', 'color', 'manufacturer', 'vehicle_passport', 'vehicle_passport_date',
        'registration_certificate', 'registration_certificate_date', 'insurance_policy', 'inventory_number',
        'engine_model', 'engine_number', 'engine_power', 'weight_allowed', 'weight_empty', 'balance_price',
        'residual_price', 'transport_tax', 'property_tax', 'chassis_number', 'body_number', 'diagnostic_card_number',
        'diagnostic_card_date', 'owner_id', 'customer_id', 'commissioning_date', 'tires', 'structure_department',
        'owner_legally', 'customer_company',
        'customer_department', 'customer_director', 'fact_referral',
        'garage_latitude', 'garage_longitude'
    ];

    protected $hidden = [
        'password'
    ];
    protected $appends = ['odometer', 'fuel'];
    protected $dates = ['vehicle_passport_date', 'registration_certificate_date', 'insurance_day', 'diagnostic_card_date', 'commissioning_date'];

    const STATUS_ACTIVE = 'active';
    const STATUS_DISABLE = 'disable';
    const STATUS_REPAIR = 'repair';
    const STATUS_OF_REPAIR = 'of_repair'; // в ремонте, или ожидает ремонта, или запланирован ремонт, но может выезжать на рейсы
    const STATUS_SYSTEM = 'system';

    const TYPE_SEDAN = 'sedan';
    const TYPE_MINIVEN = 'miniven';
    const TYPE_UNIVERSAL = 'universal';
    const TYPE_BUS = 'bus';

    const TYPES = [
        self::TYPE_SEDAN,
        self::TYPE_MINIVEN,
        self::TYPE_UNIVERSAL,
        self::TYPE_BUS,

    ];

    const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_DISABLE,
        self::STATUS_REPAIR,
        self::STATUS_OF_REPAIR,
        self::STATUS_SYSTEM,
    ];

    const STATUSES_FOR_REPAIR_AREA = [
        self::STATUS_ACTIVE,
        self::STATUS_REPAIR,
        self::STATUS_OF_REPAIR,
    ];

    const LOCATION_ON_LINE = 'on_line';
    const LOCATION_IN_GARAGE = 'in_garage';
    const LOCATION_IN_REPAIR = 'in_repair';
    const LOCATION_STATUS_DEFAULT = self::LOCATION_ON_LINE;

    const LOCATION_STATUSES = [
        self::LOCATION_ON_LINE,
        self::LOCATION_IN_GARAGE,
        self::LOCATION_IN_REPAIR
    ];

    const DRIVER_CATEGORIES = [
        'B', 'C', 'D'
    ];

    const TIRES = [
        'winter', 'summer', 'all-season'
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
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_bus');
    }

    public function user_taken()
    {
        return $this->hasMany(UserTakenBus::class);
    }

    public function type(){
        return $this->hasOne(BusType::class,'id','bus_type_id');
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function driver()
    {
        return $this->hasOne(Driver::class);
    }

    public function tours()
    {
        return $this->hasMany(Tour::class);
    }

    public function repairs()
    {
        return $this->hasMany(Repair::class);
    }

    public function cities()
    {
        return $this->belongsToMany(City::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'bus_amenities');
    }

    public function monitoring()
    {
        return $this->hasMany(MonitoringBus::class);
    }

    public function variables()
    {
        return $this->hasMany(BusVariable::class, 'bus_id', 'id');
    }

    public function bus_drivers()
    {
        return $this->morphedByMany(Driver::class, 'imageable', 'user_bus');
    }

    public function bus_users()
    {
        return $this->morphedByMany(User::class, 'imageable','user_bus');
    }

    public function getOwnersAttribute(){
        $bus_drivers = $this->bus_drivers()->get();
        $bus_users = $this->bus_users()->get();
        return $bus_drivers->concat($bus_users);

    }

    public function diagnostic_card_template()
    {
        return $this->hasOne(DiagnosticCardTemplate::class, 'id', 'diagnostic_card_template_id');
    }

    public function diagnostic_cards()
    {
        return $this->hasMany(DiagnosticCard::class, 'bus_id', 'id');
    }

    public function repair_card_template()
    {
        return $this->hasOne(RepairCardType::class, 'id', 'repair_card_type_id');
    }

    public function upcomingRepairs()
    {
        return $this->repairs()->where('date_to', '>=', Carbon::now()->format('Y-m-d'))->ofStatus(Repair::STATUS_REPAIR)->orderBy('date_to');
    }

    public function car_color()
    {
        return $this->hasOne(CarColor::class, 'slug', 'color');
    }

    public function car_customer_department()
    {

        return $this->hasOne(CustomerDepartment::class, 'slug', 'customer_department');
    }

    public function car_fact_referral()
    {

        return $this->hasOne(CustomerDepartment::class, 'slug', 'fact_referral');
    }

    public function car_customer_company()
    {

        return $this->hasOne(CustomerCompany::class, 'slug', 'customer_company');
    }

    public function car_customer_director()
    {

        return $this->hasOne(CustomerPersonality::class, 'slug', 'customer_director');
    }

    public function getMaxRentTimeAttribute($date)
    {
        return empty($this->attributes['max_rent_time']) || $this->attributes['max_rent_time'] == 0 ? 24 : $this->attributes['max_rent_time'];
    }

    public function setInsuranceDayAttribute($date)
    {
        return $this->attributes['insurance_day'] = $date ? Carbon::createFromFormat('d.m.Y', $date) : null;
    }

    public function setRevisionDayAttribute($date)
    {
        return $this->attributes['revision_day'] = $date ? Carbon::createFromFormat('d.m.Y', $date) : null;
    }

    public function setUniversalDayAttribute($date)
    {
        return $this->attributes['universal_day'] = $date ? Carbon::createFromFormat('Y-m-d H:i:s', $date) : null;
    }

    public static function getRentBuses()
    {
        return self::where('is_rent', true)->get();
    }

    public static function getSystemBus()
    {
        return self::whereStatus(self::STATUS_SYSTEM)->first();
    }

    public function getOdometerAttribute()
    {
        return $this->getLastVariables()->odometer;
    }
    public function getFuelAttribute()
    {
        return $this->getLastVariables()->fuel;
    }

    public function getLastVariables()
    {
        if ($this->variables && $this->variables->count() > 0) {
            return $this->variables->sortBy('created_at')->last();
        } else {
            $variable = collect();
            $variable->odometer = 0;
            $variable->fuel = 0;
            return $variable;
        }
    }

    static public function getYearsList()
    {
        $years = [];
        $years[] = '-';
        for ($i = 1990; $i <= Carbon::now()->year; $i++) {
            $years[] = $i;
        }
        return $years;
    }

    public function setLocationStatusByType($type)
    {
        if ($type == UserBus::TYPE_TAKE) {
            $this->location_status = Bus::LOCATION_ON_LINE;
        }
        if ($type == UserBus::TYPE_PUT) {
            $this->location_status = Bus::LOCATION_IN_GARAGE;
        }
        $this->save();
    }

    //Mutators
    public function setPasswordAttribute($value)
    {
        if ($value) $this->attributes['password'] = bcrypt($value);
    }

    public function setVehiclePassportDateAttribute($value)
    {
        if ($value) $this->attributes['vehicle_passport_date'] = Carbon::parse($value)->format('Y-m-d');
    }

    public function setDiagnosticCardDateAttribute($value)
    {
        if ($value) $this->attributes['diagnostic_card_date'] = Carbon::parse($value)->format('Y-m-d');
    }

    public function setCommissioningDateAttribute($value)
    {
        if ($value) $this->attributes['commissioning_date'] = Carbon::parse($value)->format('Y-m-d');
    }

    public function setRegistrationCertificateDateAttribute($value)
    {
        if ($value) $this->attributes['registration_certificate_date'] = Carbon::parse($value)->format('Y-m-d');
    }

    public function getFilterFields()
    {
        $fields['all'] = [
            'status', 'name', 'vin', 'driver_category', 'type', 'year', 'engine_model', 'engine_number', 'engine_power', 'chassis_number', 'body_number',
            'color', 'weight_allowed', 'weight_empty', 'manufacturer', 'vehicle_passport', 'vehicle_passport_date',
            'registration_certificate', 'registration_certificate_date', 'insurance_policy', 'insurance_day', 'diagnostic_card_number',
            'diagnostic_card_date',
            'number', 'garage_number', 'company', 'inventory_number', 'commissioning_date', 'operating_mileage',
            'balance_price', 'residual_price', 'transport_tax', 'owner_legally', 'property_tax',
            'structure_department',
            'departments', 'bus_drivers',
            'location_status', 'customer_director', 'customer_company', 'customer_department', 'fact_referral',
            'tires'
        ];
        $fields['constants'] = ['status', 'location_status', 'tires'];
        $fields['relations'] = ['company', 'type'];
        $fields['dates'] = ['vehicle_passport_date', 'registration_certificate_date', 'insurance_day', 'diagnostic_card_date', 'commissioning_date'];
        $fields['custom'] = ['departments', 'color',
            'customer_director', 'customer_company',
            'customer_department', 'fact_referral', 'bus_drivers'];
        return $fields;
    }

    //Scopes
    public function scopeFilter($query, $data)
    {
        $status = array_get($data, 'status');
//        $data['bus_type_id'] = array_get($data, 'type');
//        $companyId = array_get($data, 'company_id');
//        $templateId = array_get($data, 'template_id');
        $buses = array_get($data, 'buses');
        $companies = array_get($data, 'companies');
        $departments = array_get($data, 'departments');
        $busDrivers = array_get($data, 'bus_drivers');
        $data['bus_type_id'] = array_get($data, 'type');
        $data['company_id'] = array_get($data, 'company');
//        dd($data);
        $fieldsWhereLike = array_only($data, [
            'name', 'engine_number', 'balance_price', 'residual_price', 'transport_tax', 'owner_legally', 'property_tax',
            'inventory_number', 'operating_mileage', 'engine_power', 'number', 'garage_number', 'vin', 'chassis_number',
            'body_number', 'vehicle_passport', 'registration_certificate', 'insurance_policy', 'diagnostic_card_number'
        ]);
        $fieldsWhere = array_only($data, [
            'driver_category', 'company_id', 'template_id', 'bus_type_id', 'year', 'engine_model', 'color',
            'weight_allowed', 'weight_empty', 'manufacturer', 'structure_department', 'location_status',
            'customer_director', 'customer_company', 'customer_department', 'fact_referral', 'tires'
        ]);
        $fieldsWhereDate = array_only($data, [
            'vehicle_passport_date', 'registration_certificate_date', 'insurance_day', 'diagnostic_card_date', 'commissioning_date'
        ]);

        foreach ($fieldsWhereDate as $key => $value) {
            $query->when($value, function ($q) use ($key, $value) {
                if (is_array($value)) {
                    return $q->where(function ($query) use ($value, $key) {
                        for ($i = 0; $i < count($value); $i++) {
                            $query->orWhere($key, 'like', '%' . Carbon::parse($value[$i])->format('Y-m-d') . '%');
                        }
                    });
                } else {
                    return $q->whereDate($key, Carbon::parse($value));
                }

            });
        }
        foreach ($fieldsWhereLike as $key => $value) {
            $query->when($value, function ($q) use ($key, $value) {
                if (is_array($value)) {
                    return $q->where(function ($query) use ($value, $key) {
                        for ($i = 0; $i < count($value); $i++) {
                            $query->orWhere($key, 'like', '%' . $value[$i] . '%');
                        }
                    });
                } else {
                    return $q->where($key, 'like', "%$value%");
                }
            });
        }
        foreach ($fieldsWhere as $key => $value) {
            $query->when($value, function ($q) use ($key, $value) {
                return is_array($value) ? $q->whereIn($key, $value) : $q->where($key, $value);
            });
        }

        $query
            ->when($buses, function ($q) use ($buses) {
                return $q->whereIn('id', $buses);
            })
            ->when($companies, function ($q) use ($companies) {
                return $q->whereIn('company_id', $companies);
            })
            ->when($status, static function ($q) use ($status) {
                if (!\is_array($status)) {
                    return $q->where('status', $status);
                }
                return $q->whereIn('status', $status);
            });
        $query->when($departments, function ($q) use ($departments) {
            return $q->whereHas('departments', function ($que) use ($departments) {
//                    dd($que->whereIn('id', $departments));
                return $que->whereIn('department_bus.department_id', $departments);
            });
        });
        $query->when($busDrivers, function ($q) use ($busDrivers) {
            return $q->whereHas('bus_drivers', function ($que) use ($busDrivers) {
//                    dd($que->whereIn('id', $departments));
//                dd($que->whereIn('drivers.id', $busDrivers));
                return $que->whereIn('user_bus.imageable_id', $busDrivers);
            });
        });

        return $query;
    }
}
