<?php

namespace App\Models;

use App\Services\Pays\ServicePayService;
use App\Services\Prettifier;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Services\Order\AddServicesPriceService;

/**
 * App\Models\Order
 *
 * @property string $slug
 * @property string $comment
 * @property int $station_from_id
 * @property int $station_to_id
 * @property bool $is_sale
 * @mixin \Eloquent
 */
class Order extends Model
{
    protected $fillable = [
        'slug', 'tour_id', 'client_id', 'operator_id', 'status', 'type', 'count_places', 'confirm',
        'source', 'pull', 'old_places', 'places_with_number', 'comment', 'return_order_id',
        'appearance', 'social_status_confirm', 'is_call', 'price', 'flight_number', 'partial_prepaid',
        'station_from_id', 'station_to_id', 'station_from_time', 'station_to_time', 'cnt_sms', 'uid', 'pay_id', 'type_pay', 'type_pay_alt',
        'pay_url', 'is_return_ticket', 'modified_user_id', 'created_user_id', 'canceled_user_id', 'from_date_time', 'to_date_time',
        'is_sale', 'custom_address_from', 'custom_address_to', 'address_to_street', 'address_to_house', 'address_to_building', 'address_to_apart',
        'address_from_street', 'address_from_house', 'address_from_building', 'address_from_apart', 'latitude', 'longitude', 'bbv_receipt',
    ];

    protected $dates = ['from_date_time', 'to_date_time'];

    const TYPE_PAY_WAIT = 'waiting';
    const TYPE_PAY_CANCEL = 'cancel';
    const TYPE_PAY_SUCCESS = 'success';
    const TYPE_PAY_CASH_PAYMENT = 'cash-payment';
    const TYPE_PAY_CASH_PAYMENT_OFFICE = 'cash-payment-office';
    const TYPE_CHECKING_ACCOUNT = 'checking-account';
    const TYPE_CHECKING_ACCOUNT_WAIT = 'checking-account-wait';
    const TYPE_PAY_CASHLESS_PAYMENT = 'cashless-payment';

    const TYPE_PAYS = [
        self::TYPE_PAY_CASH_PAYMENT,
        self::TYPE_PAY_CANCEL,
        self::TYPE_PAY_WAIT,
        self::TYPE_PAY_SUCCESS,
        self::TYPE_CHECKING_ACCOUNT,
        self::TYPE_CHECKING_ACCOUNT_WAIT,
        self::TYPE_PAY_CASHLESS_PAYMENT,
        self::TYPE_PAY_CASH_PAYMENT_OFFICE
    ];

    const ALT_TYPE_PAYS = [
        'cash-payment',
        'paid_to_card',
        'diveevo',
        'yunitiki',
        'busfor',
        'rosbilet',
        'paid_office',
        'health_academy',
        'nika_spring',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_RESERVE = 'reserve';
    const STATUS_DISABLE = 'disable';

    const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_RESERVE,
        self::STATUS_DISABLE,
    ];

    const TYPE_NO_COMPLETED = 'no_completed';
    const TYPE_EDITED = 'edited';
    const TYPE_COMPLETED = 'completed';
    const TYPE_WAITING = 'waiting';

    const TYPES = [
        self::TYPE_NO_COMPLETED,
        self::TYPE_COMPLETED,
        self::TYPE_WAITING,
    ];

    const SOURCE_OPERATOR = 'operator';
    const SOURCE_SITE = 'site';
    const SOURCE_DRIVER = 'driver';
    const SOURCE_APP = 'application';
    const SOURCE_CLIENT_APP = 'client_app';
    const SOURCE_SYSTEM = 'system';

    const SOURCES = [
        self::SOURCE_OPERATOR,
        self::SOURCE_SITE,
        self::SOURCE_DRIVER,
        self::SOURCE_APP,
        self::SOURCE_CLIENT_APP,
        self::SOURCE_SYSTEM,
    ];

    protected $casts = [
        'old_places' => 'array',
    ];

    //Relationships
    public function smsLog()
    {
        return $this->hasMany(SmsLog::class);
    }


    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id', 'id');
    }

    public function createdUser()
    {
        return $this->belongsTo(User::class, 'created_user_id', 'id');
    }

    public function modifiedUser()
    {
        return $this->belongsTo(User::class, 'modified_user_id', 'id');
    }

    public function canceledUser()
    {
        return $this->belongsTo(User::class, 'canceled_user_id', 'id');
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function stationFrom()
    {
        return $this->belongsTo(Station::class)->with('city');
    }

    public function stationTo()
    {
        return $this->belongsTo(Station::class)->with('city');
    }

    public function orderPlaces()
    {
        return $this->hasMany(OrderPlace::class);
    }

    public function returnOrder()
    {
        return $this->belongsTo(Order::class, 'return_order_id');
    }

    public function history()
    {
        return $this->hasMany(OrderHistory::class)->with('operator');
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function addServices()
    {
        return $this->belongsToMany(AddService::class, 'order_add_service')->withPivot('quantity');
    }

    public function bus()
    {
        return $this->tour->bus;
    }

    //Mutators
    public function setSlugAttribute($client_id) {
        if (empty($this->slug)) {
            $_letters = ['A','S','D','E','M','T','K','L','F','N'];

            $client_id_str = str_pad(substr($client_id, -2), 2, "0", STR_PAD_LEFT);
            $arr = str_split($client_id_str);

            $result = "";

            foreach ($arr as $value) {
                $result .= $_letters[$value];
            }

            $dt = Carbon::now();
            $result .= rand(0, 9).$dt->format('uihd').substr($dt->format('n'), -1);

            $this->attributes['slug'] = $result;
        }
    }

    public function getNumberAttribute()
    {
        return $this->id;
    }

    public function isAllNoApp() {
        $is_all = true;

        if ($is_all) {
            foreach($this->orderPlaces as $op) {
                if($op->appearance == 1 || $op->appearance === null) {
                    $is_all = false;
                    break;
                }
            }
        }

        return $is_all;
    }

    public function countApp() {
        $count = 0;
        foreach($this->orderPlaces as $op) {
            if($op->appearance == 1){
                $count += 1;
            }
        }

        return $count;
    }

    public function getStatusPayAttribute()
    {
        if ($this->type_pay == self::TYPE_PAY_WAIT) {
            $client = new ServicePayService();
            $typePay = $client->getStatus($this);
            if ($typePay != $this->type_pay) {
                if (in_array($typePay, [Order::TYPE_PAY_SUCCESS, Order::TYPE_PAY_CASH_PAYMENT])) {
                    $this->update(['type_pay' => $typePay, 'status' => Order::STATUS_ACTIVE, 'type' => Order::TYPE_WAITING, 'date_of_payment'=> date('Y-m-d')]);
                } else {
                    $this->update(['type_pay' => $typePay]);
                }
            }
        }
        return $this->type_pay;
    }

    public function getStatusPayDescriptionAttribute()
    {
        $client = new ServicePayService();
        return $client->getStatusDescription($this);
    }

    public function getPhoneWithoutCodeAttribute()
    {
        return $this->id;
    }

    public function priceApp() {
        $price = [];

        foreach($this->orderPlaces as $op) {
            if ($op->appearance !== 0) {
                array_push($price, $op->price);
            }
        }
        $sum = array_sum($price);
        $sum += $sum ? AddServicesPriceService::getPrice($this) : 0;
        return $sum;
    }

    public function getTotalPriceAttribute()
    {
        $price = 0;
        $is_app = false;

        foreach($this->orderPlaces as $op) {
            if($op->appearance == '1') {
                $price += $op->price;
                $is_app = true; 
            } elseif($op->appearance === null){
                $is_app = true;
            }
        }

        if($price == 0 && $is_app == true) {
            $price = $this->price;
        }

        /*if ($this->is_return_ticket) {
            $price = $this->tour->route->discount_return_ticket_type ?
                     Prettifier::percent($price, $this->tour->route->discount_return_ticket) :
                     $price - $this->tour->route->discount_return_ticket;
        }*/
        if ($coupon = $this->coupon) $price = Prettifier::percent($price, $coupon->percent);

        return $price;
    }

    public function getCurrencyAttribute()
    {
        return $this->tour->route->currency;
    }

    public function getUrlSchedulesAttribute()
    {
        return route('index.schedules.index', [
            'station_from_id' => $this->station_from_id,
            'station_to_id' => $this->station_to_id,
            'route_id' => $this->tour->route_id,
            'date' => $this->tour->date_start->format('d.m.Y'),
        ]);
    }

    public function getAltPaymentAttribute()
    {
        return $this->type_pay_alt ? trans('admin.orders.alt_pay_types.' . $this->type_pay_alt) : trans('admin.orders.pay_types.'.$this->type_pay);
    }

    public function getAddressFromAttribute() {
        return isset($this->address_from_street) ?  $this->address_from_street.
            (empty($this->address_from_house) ? '': ' д.'.$this->address_from_house).
            (empty($this->address_from_building) ? '': ' корп.'.$this->address_from_building).
            (empty($this->address_from_apart) ? '' : ' п.'.$this->address_from_apart) : $this->custom_address_from;
    }

    public function getAddressToAttribute() {
        return isset($this->address_to_street) ?  $this->address_to_street.
            (empty($this->address_to_house) ? '' :' д.'.$this->address_to_house).
            (empty($this->address_to_building) ? '' :' корп.'.$this->address_to_building).
            (empty($this->address_to_apart) ? '' : ' п.'.$this->address_to_apart) : $this->custom_address_to;
    }

    public function transferAddress($getCity = false) {
        if (empty($this->address_from_street))  {
            return $getCity ? $this->stationTo->city->name.', '.$this->addressTo : $this->addressTo;
        } else {
            return $getCity ? $this->stationFrom->city->name.', '.$this->addressFrom : $this->addressFrom;
        }
    }


    //Scopes
    public function scopeFilter($query, $data)
    {
        $which_date = array_get($data, 'which_date');

        $id = array_get($data, 'id');
        $slug = array_get($data, 'slug');
        $status = array_get($data, 'status');
        $operator_id = array_get($data, 'operator_id');
        $tourId = array_get($data, 'tour_id');
        $clientId = array_get($data, 'client_id');
        $driverId = array_get($data, 'driver_id');
        $busId = array_get($data, 'bus_id');
        $buses = array_get($data, 'buses');
        $date = array_get($data, 'date');
        $timeFrom = array_get($data, 'time_from');
        $timeTo = array_get($data, 'time_to');
        $routeId = array_get($data, 'route_id');
        $routes = array_get($data, 'routes');
        $ids = array_get($data, 'ids');
        $between = array_get($data, 'between');
        $phone = array_get($data, 'phone');
        $type = array_get($data, 'type');
        $typePay = array_get($data, 'type_pay');
        $createdUserId = array_get($data, 'created_user_id');

        $query
            ->when($id, function ($q) use ($id) {
                return $q->where('id', $id);
            })
            ->when($slug, function ($q) use ($slug) {
                return $q->where('slug', 'like', $slug.'%');
            })
            ->when($operator_id, function ($q) use ($operator_id) {
                return $q->where('operator_id', $operator_id);
            })
            ->when($createdUserId, static function ($q) use ($createdUserId) {
                return $q->where('created_user_id', $createdUserId);
            })
            ->when($buses, function ($q) use ($buses) {
                return $q->whereIn('bus_id', $buses);
            })
            ->when($status, function ($q) use ($status) {
                return $q->where('status', $status);
            })
            ->when($type, function ($q) use ($type) {
                return $q->where('type', $type);
            })
            ->when($typePay, function ($q) use ($typePay) {
                return $q->where('type_pay', $typePay);
            })
            ->when($tourId, function ($q) use ($tourId) {
                return $q->where('tour_id', $tourId);
            })
            ->when($clientId, function ($q) use ($clientId) {
                return $q->where('client_id', $clientId);
            })
            ->when($ids, function ($q) use ($ids) {
                return $q->whereIn('id', $ids);
            })
            ->when($date, function ($q) use ($which_date, $date) {
                if($which_date == 'tours_date') {
                    return $q->whereHas('tour', function ($q) use ($date) {
                        $q->filter(['date' => $date]);
                    });
                } else {
                    return $q->whereDate('created_at', $date->format('Y-m-d'));
                }
            })
            // ->when($timeTo, function ($q) use ($which_date, $timeTo, $timeFrom) {
            //     if($which_date == 'tours_date') {
            //         return $q->whereHas('tour', function ($q) use ($timeTo, $timeFrom) {
            //             $q->whereBetween('time_start', [$timeFrom, $timeTo]);
            //         });
            //     } else {
            //         return $q->whereTime('created_at', '>=', $timeFrom)
            //             ->whereTime('created_at', '<=', $timeFrom);
            //     }
            // })
            ->when($routeId, function ($q) use ($routeId) {
                return $q->whereHas('tour', function ($q) use ($routeId) {
                    $q->filter(['route_id' => $routeId]);
                });
            })
            ->when($driverId, function ($q) use ($driverId) {
                return $q->whereHas('tour', function ($q) use ($driverId) {
                    $q->filter(['driver_id' => $driverId]);
                });
            })
            ->when($busId, function ($q) use ($busId) {
                return $q->whereHas('tour', function ($q) use ($busId) {
                    $q->filter(['bus_id' => $busId]);
                });
            })
            ->when($between, function ($q) use ($between) {
                return $q->whereHas('tour', function ($q) use ($between) {
                    $q->filter(['between' => $between]);
                });
            })
            ->when($routes, function ($q) use ($routes) {
                return $q->whereHas('tour', function ($q) use ($routes) {
                    $q->filter(['routes' => $routes]);
                });
            })
            ->when($phone, function ($q) use ($phone) {
                return $q->whereHas('client', function ($q) use ($phone) {
                    $q->filter(['phone' => $phone]);
                });
            });

        return $query;
    }

    public function scopeActive($q)
    {
        return $q->whereStatus(Order::STATUS_ACTIVE);
    }

    public function getIsActiveAttribute() {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function getPrepaidHumanPriceAttribute() {
        return $this->prepaid_price.' '.trans('admin_labels.currencies_short.' . $this->tour->route->currency->alfa);
    }

    public function calcPrepaid() {
        return round($this->price*($this->tour->route->partial_prepaid/100), 2);
    }

    public function addServicesCost()   // Подсчет общей стоимости доп. сервисов
    {
        $total = 0;
        foreach ($this->addServices as $item) {
            $total += $item->value*$item->pivot->quantity;
        }
        return $total;
    }

}
