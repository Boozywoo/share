<?php

namespace App\Models;

use App\Services\Prettifier;
use App\Traits\ClearPhone;
use App\Traits\ModelTableTrait;
use App\Traits\PhoneTrait;
use Bican\Roles\Contracts\HasRoleAndPermission as HasRoleAndPermissionContract;
use Bican\Roles\Traits\HasRoleAndPermission;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * @param int $id
 */
class User extends Authenticatable implements HasRoleAndPermissionContract
{
    use HasRoleAndPermission, Notifiable, ClearPhone, PhoneTrait, HasApiTokens, ModelTableTrait;

    protected $fillable = [
        'phone', 'first_name', 'middle_name', 'last_name', 'passport', 'email', 'password', 'birth_day', 'status', 'card',
        'company_id', 'sip', 'timezone', 'currency_id', 'bg_image_id', 'department_id', 'position_id', 'superior_id',
        'user_status', 'confirm'
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    protected $dates = ['date_change_password'];
    protected $casts = [
        'id' => 'integer',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_DISABLE = 'disable';

    const STATUS_CONFIRM = 'confrim';
    const STATUS_DENIED = 'denied';
    const STATUS_HOLIDAY = 'holiday';

    const APPEARANCE_TRUE = '1';
    const APPEARANCE_FALSE = '0';

    const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_DISABLE,
    ];

    const USER_STATUSES = [
        self::STATUS_CONFIRM,
        self::STATUS_DENIED,
        self::STATUS_HOLIDAY,
    ];

    const APPEARANCES = [
        self::APPEARANCE_TRUE,
        self::APPEARANCE_FALSE,
    ];

    //Accessors & Mutators

    public function getFieldsByName()
    {
        $user_fillable = array();
        foreach ($this->getFillable() as $item)
            $user_fillable[$item] = trans('admin_labels.' . $item);
        return $user_fillable;
    }

    public function setPasswordAttribute($value)
    {
        if ($value) $this->attributes['password'] = bcrypt($value);
    }

    public function setCompanyIdAttribute($value)
    {
        $this->attributes['company_id'] = empty($value) ? null : $value;
    }

    //Relationships
    public function companies()
    {
        return $this->belongsToMany(Company::class)->withPivot('pay_order_fix', 'pay_order_percent', 'pay_month_fix');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function director()
    {
        return $this->hasOne(Department::class, 'director_id', 'id');
    }

    public function departament()
    {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }

    /*
    Должность юзера
    */
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    /*
    Возвращает всех подчиненных юзера
    */
    public function subordinates()
    {
        return $this->hasMany(User::class, 'superior_id');
    }

    /*
    Возвращает непосредственного начальника юзера
    */
    public function superior()
    {
        return $this->belongsTo(User::class);
    }

    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function getIsAgentAttribute()
    {
        if ($this->roles && $this->roles->count()) {
            return $this->roles->first()->slug == 'agent' ? true : false;
        }
        return false;
    }

    public function getIsMediatorAttribute()
    {
        if ($this->roles && $this->roles->count()) {
            return $this->roles->first()->slug == 'mediator' ? true : false;
        }
        return false;
    }
    public function getIsSuperadminAttribute()
    {
        if ($this->roles && $this->roles->count()) {
            return $this->roles->first()->slug == 'superadmin' ? true : false;
        }
        return false;
    }

    public function getIsMethodistAttribute()
    {
        if ($this->roles && $this->roles->count()) {
            return $this->roles->first()->slug == 'methodist' ? true : false;
        }
        return false;
    }

    public function routes()
    {
        return $this->belongsToMany(Route::class)->withPivot('pay_order_fix', 'pay_order_percent', 'pay_month_fix', 'added_price');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function buses()
    {
        return $this->morphToMany(Bus::class, 'imageable', 'user_bus');
    }

    public function taken_buses()
    {
        return $this->morphMany(UserTakenBus::class, 'imageable');
    }

    public function pays($year_start, $month_start, $year_finish, $month_finish)
    {
        $between = [$year_start . '-' . $month_start . '-01 00:00:00', $year_start . '-' . $month_finish . '-31 23:59:59'];
        return [
            'month' => \DB::table('user_pay_month')
                ->where('user_id', $this->id)
                ->whereBetween('month', [$month_start, $month_finish])
                ->whereBetween('year', [$year_start, $year_finish])
                ->get(),
            'order' => \DB::table('user_pay_order')
                ->where('user_id', $this->id)
                ->join('orders', 'orders.id', '=', 'user_pay_order.order_id')
                ->whereBetween('orders.created_at', $between)
                ->get(),
        ];

        return $this->belongsTo(Client::class);
    }

     /**
     * Get the user interface theme.
     */
    public function interface_setting()
    {
        return $this->belongsTo('App\Models\InterfaceSetting');
    }

    /**
     * Get the user backgrouund image.
     */
    public function bg_image()
    {
        return $this->hasOne(BgImage::class);
    }

    public function getCompanyIdsAttribute()
    {
        $companyIds = $this->companies->pluck('id');
        return $companyIds->count() ? $companyIds : collect([-1]);
    }

    public function getFullNameAttribute()
    {
        return $this->last_name . ' ' . $this->first_name . ' ' . $this->middle_name;
    }

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getRouteIdsAttribute()
    {
        $routeIds = $this->routes->pluck('id');
        return $routeIds->count() ? $routeIds : collect([-1]);
    }

    public function getBusIdsAttribute()
    {
        $busesIds = Bus::filter(['companies' => auth()->user()->companyIds])->pluck('id');
        return $busesIds->count() ? $busesIds : collect([-1]);
    }

    public function setDepartmentIdAttribute($value)
    {
        return $this->attributes['department_id'] = (($value == 0) ? null : $value);
    }

    public function setPositionIdAttribute($value)
    {
        return $this->attributes['position_id'] = (($value == 0) ? null : $value);
    }

    public function getChiefUserAttribute()
    {
        if ($this->superior_id) {
            return $this->superior_id;
        }

        if ($this->departament && $this->departament->director) {
            // если юзер и так директор департамента, то прокидываем департаменту выше
            if ($this->departament->director_id === $this->id && $this->departament->superdepartment) {
                return $this->departament->superdepartment->director_id;
            }

            return $this->departament->director_id;
        }

        return 0;
    }

    public function getNameAndPhoneAttribute()
    {
        return $this->fullName . ' +' . $this->phone . ' ('. $this->roles()->first()->name . ')' ;
    }

    //Scopes
    public function scopeFilter($query, $data)
    {
        $name = array_get($data, 'first_name');
        $department_id = array_get($data, 'department_id');
        $phone = array_get($data, 'phone');
        $roles = array_get($data, 'roles');
        $status = array_get($data, 'status');
        $companyId = array_get($data, 'company_id');
        $routeId = array_get($data, 'route_id');
        $query
            ->when($name, function ($q) use ($name) {
                return $q->where('first_name', 'like', "%$name%");
            })
            ->when($status, function ($q) use ($status) {
                return $q->where('status', $status);
            })
            ->when($department_id, function ($q) use ($department_id) {
                return $q->where('department_id', $department_id);
            })
            ->when($phone, function ($q) use ($phone) {
                $phone = Prettifier::prettifyPhoneClear($phone);
                return $q->where('phone', 'like', "%$phone%");
            })
            ->when($roles, function ($q) use ($roles) {
                if (!is_array($roles)) $roles = [$roles];
                return $q->whereHas('roles', function ($q) use ($roles) {
                    $q->whereIn('slug', $roles);
                });
            })
            ->when($companyId, function ($q) use ($companyId) {
                return $q->whereHas('companies', function ($q) use ($companyId) {
                    $q->where('id', $companyId);
                });
            })
            ->when($routeId, function ($q) use ($routeId) {
                return $q->whereHas('routes', function ($q) use ($routeId) {
                    $q->where('id', $routeId);
                });
            });
        return $query;
    }
    public static function getUserCompany($company_id){
        return User::where('company_id', $company_id)->get()->pluck('name','id');
    }

    public static function getDepartmentsUsers($id){
        return User::where('department_id', '=', $id)->where('status', '=', self::STATUS_ACTIVE)->get();
    }
}

