<?php

namespace App\Models;

use App\Services\Prettifier;
use App\Traits\ClearPhone;
use App\Traits\ImageableTrait;
use App\Traits\PhoneTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable
{
    use Notifiable, ClearPhone, PhoneTrait, ImageableTrait;

    protected $fillable = [
        'first_name', 'middle_name', 'last_name', 'passport', 'doc_type', 'doc_number', 'status_id',
        'phone', 'email', 'password', 'status', 'reputation', 'order_success', 'order_error', 'status',
        'register', 'date_social', 'status_id', 'birth_day', 'card', 'company_id', 'timezone', 'gender', 'country_id', 'bonus',
    ];

    protected $dates = ['date_social', 'birth_day'];

    const STATUS_STATE_NEW = 'new';
    const STATUS_STATE_DRIVER_OK = 'driver_ok';
    const STATUS_STATE_DRIVER_CANCEL = 'driver_cancel';
    const STATUS_STATE_OPERATOR_OK = 'operator_ok';

    const IMAGE_TYPE_IMAGE = 'image';

    CONST CODE_PHONES = [
        'by' => '375',
        'ua' => '380',
        'ru' => '7',
        'de' => '49',
        'cz' => '420',
        'il' => '972',
        'us' => '1',
        'fi' => '358',
        'no' => '47',
        'pl' => '48',
        'uz' => '998',
        'tm' => '993',
        'md' => '373',
        'az' => '994',
        'tj' => '992',
        'fr' => '33',
        'gr' => '30',
    ];

    const IMAGES_PARAMS = [
        self::IMAGE_TYPE_IMAGE => [
            'multiple' => true,
            'params' => [
                'admin' => [
                    'w' => 50,
                    'fit' => 'max',
                ],
            ],
        ],
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_DISABLE = 'disable';
    const STATUS_SYSTEM = 'system';

    const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_DISABLE
    ];

    const REPUTATION_NEW = 'new';
    const REPUTATION_RELIABLE = 'reliable';
    const REPUTATION_PROBLEM = 'problem';

    const REPUTATIONS = [
        self::REPUTATION_NEW,
        self::REPUTATION_RELIABLE,
        self::REPUTATION_PROBLEM,
    ];

    //Accessors & Mutators
    public function setPasswordAttribute($value)
    {
        if ($value) $this->attributes['password'] = bcrypt($value);
    }

    public function setEmailAttribute($value)
    {
        if ($value) $this->attributes['email'] = $value;
    }

    public function setStatusIdAttribute($status)
    {
        if (!$status) {
            $this->attributes['status_id'] = null;
        } else {
            $this->attributes['status_id'] = $status;
        }
    }

    public function getFullNameAttribute()
    {
        return $this->last_name . " " . $this->first_name . " " . $this->middle_name;
    }

    public function getInitialsAttribute()
    {
        return $this->last_name . " " . mb_substr($this->first_name,0, 1) . "." . mb_substr($this->middle_name,0, 1).".";
    }

    public function FIO()
    {
        $name = $this->last_name . " " . $this->first_name . " " . $this->middle_name;
        return trim($name);
    }

    public function getFieldsByName()
    {
        $user_fillable = array();
        $exceptFields = ['register', 'order_success', 'order_error', 'reputation', 'password', 'status', 'timezone', 'company_id', 'date_social'];
        foreach ($this->getFillable() as $item) {
            if (!in_array($item,$exceptFields)) {
                $user_fillable[$item] = trans('admin_labels.' . $item);
            }
        };
        return $user_fillable;
    }

    //Relationships
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function token()
    {
        return $this->hasOne(Token::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class)->latest();
    }

    public function orderSecond()
    {
        return $this->hasMany(Order::class)->orderBy('updated_at', 'desc')->take(2);
    }

    public function socialStatus()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function reviewsPositive()
    {
        return $this->reviews()->filter(['type' => Review::TYPE_POSITIVE]);
    }

    public function reviewsNegative()
    {
        return $this->reviews()->filter(['type' => Review::TYPE_NEGATIVE]);
    }

    //Scopes
    public function scopeFilter($query, $data)
    {
        $name = array_get($data, 'first_name');
        $last_name = array_get($data, 'last_name');
        $passport = array_get($data, 'passport');
        $phone = array_get($data, 'phone');
        $status = array_get($data, 'status');
        $query
            ->when($name, function ($q) use ($name) {
                return $q->where('first_name', 'like', "%$name%");
            })
            ->when($last_name, function ($q) use ($last_name) {
                return $q->where('last_name', 'like', "%$last_name%");
            })
            ->when($passport, function ($q) use ($passport) {
                return $q->where('passport', 'like', "%$passport%");
            })
            ->when($status, function ($q) use ($status) {
                return $q->where('status', $status);
            })
            ->when($phone, function ($q) use ($phone) {
                $phone = Prettifier::prettifyPhoneClear($phone);
                return $q->where('phone', 'like', "%$phone%");
            });
        return $query;
    }

    public function getActiveOrderPlacesAttribute()
    {
        $client = $this;
        return \App\Models\OrderPlace::whereHas('order', function ($q) use ($client) {
            $q->where('status', \App\Models\Order::STATUS_ACTIVE);
            $q->where('client_id', $client->id);
        })->count();
    }
}
