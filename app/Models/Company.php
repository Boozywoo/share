<?php

namespace App\Models;

use App\Traits\ClearPhone;
use App\Traits\PhoneTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Company extends Model
{
    use PhoneTrait, ClearPhone, Notifiable;

    protected $fillable = [
        'name', 'responsible', 'position', 'phone', 'phone_resp',
        'phone_sub', 'status', 'reputation', 'requisites', 'is_customer', 'is_carrier', 'dispatcher'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_DISABLE = 'disable';

    const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_DISABLE,
    ];

    const REPUTATION_NEW = 'new';
    const REPUTATION_RELIABLE = 'reliable';
    const REPUTATION_PROBLEM = 'problem';

    const REPUTATIONS = [
        self::REPUTATION_NEW,
        self::REPUTATION_RELIABLE,
        self::REPUTATION_PROBLEM,
    ];

    //Relationships
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function drivers()
    {
        return $this->hasMany(Driver::class,'company_id','id');
    }

    public function buses()
    {
        return $this->hasMany(Bus::class);
    }

    public function customerAgreements()
    {
        return $this->belongsToMany(Agreement::class, 'agreements', 'customer_company_id');
    }

    public function serviceAgreements()
    {
        return $this->belongsToMany(Agreement::class, 'agreements', 'service_company_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }


    public function reviewsPositive()
    {
        return $this->reviews()->filter(['type' => Review::TYPE_POSITIVE]);
    }

    public function reviewsNegative()
    {
        return $this->reviews()->filter(['type' => Review::TYPE_NEGATIVE]);
    }

    public function repair_card_templates()
    {
        return $this->hasMany(RepairCardTemplate::class, 'company_id', 'id');
    }
    public function review_act_templates()
    {

        return $this->hasMany(ReviewActTemplate::class, 'company_id', 'id');
    }

    public function diagnostic_card_templates()
    {

        return $this->hasMany(DiagnosticCardTemplate::class, 'company_id', 'id');
    }

    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    public function car_breakages()
    {
        return $this->hasMany(CarBreakages::class);
    }

    //Mutators
    public function getCountRegisterAttribute()
    {
        return $this->created_at->diffInDays(Carbon::now());
    }

    //Scopes
    public function scopeFilter($query, $data)
    {
        $name = array_get($data, 'name');
        $status = array_get($data, 'status');
        $reputation = array_get($data, 'reputation');
        $userId = array_get($data, 'user_id');
        $isCarrier = array_get($data, 'is_carrier');
        $isCustomer = array_get($data, 'is_customer');
        $query
            ->when($name, function ($q) use ($name) {
                return $q->where('name', 'like', "%$name%");
            })
            ->when($status, function ($q) use ($status) {
                return $q->where('status', $status);
            })
            ->when($isCarrier, function ($q) use ($isCarrier) {
                return $q->where('is_carrier', $isCarrier);
            })
            ->when($isCustomer, function ($q) use ($isCustomer) {
                return $q->where('is_customer', $isCustomer);
            })
            ->when($reputation, function ($q) use ($reputation) {
                return $q->where('reputation', $reputation);
            })
            ->when($userId, function ($q) use ($userId) {
                return $q->whereHas('users', function ($q) use ($userId) {
                    $q->where('id', $userId);
                });
            });
        return $query;
    }

    public static function getIdsHrCompany($company_id){
        $users = DB::table('users')
            ->join('role_user','users.id', '=', 'role_user.user_id')
            ->join('roles','roles.id', '=', 'role_user.role_id')
            ->join('permission_role','permission_role.role_id', '=', 'roles.id')
            ->join('permissions','permission_role.permission_id', '=', 'permissions.id')
            ->where('permissions.slug', '=', 'view.notifications.hr')
            ->where('users.company_id', '=',$company_id)
            ->select('users.id', 'users.first_name')
            ->get()
            ->pluck('id');

        return $users;
    }
    public static function getIdsNotifyWishes($company_id, $ignore_user){
        $users = DB::table('users')
            ->join('role_user','users.id', '=', 'role_user.user_id')
            ->join('roles','roles.id', '=', 'role_user.role_id')
            ->join('permission_role','permission_role.role_id', '=', 'roles.id')
            ->join('permissions','permission_role.permission_id', '=', 'permissions.id')
            ->where('permissions.slug', '=', 'view.wishes.notify')
            ->where('users.company_id', '=',$company_id)
            ->where('users.id', '!=', $ignore_user)
            ->select('users.id', 'users.first_name')
            ->get();

        return $users;
    }
    public static function getUserIdsByRole($company_id, $role_id, $ignore_user){
        $users = DB::table('users')
            ->join('role_user','users.id', '=', 'role_user.user_id')
            ->where('role_user.role_id', '=',$role_id)
            ->where('users.company_id', '=',$company_id)
            ->whereNotIn('users.id', (array)$ignore_user)
            ->select('users.id', 'users.first_name')
            ->get();

        return $users;
    }
}
