<?php

namespace App\Providers;

use App\Models\Agreement;
use App\Models\Bus;
use App\Models\City;
use App\Models\Client;
use App\Models\Company;
use App\Models\Driver;
use App\Models\Order;
use App\Models\OrderPlace;
use App\Models\Rent;
use App\Models\Sale;
use App\Models\Repair;
use App\Models\Coupon;
use App\Models\Review;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\Setting;
use App\Models\Status;
use App\Models\Station;
use App\Models\Tour;
use App\Models\User;
use App\Observers\AgreementObserver;
use App\Observers\BusObserver;
use App\Observers\CityObserver;
use App\Observers\ClientObserver;
use App\Observers\CompanyObserver;
use App\Observers\CouponObserver;
use App\Observers\DriverObserver;
use App\Observers\OrderObserver;
use App\Observers\OrderPlaceObserver;
use App\Observers\RentObserver;
use App\Observers\RepairObserver;
use App\Observers\ReviewObserver;
use App\Observers\RouteObserver;
use App\Observers\SaleObserver;
use App\Observers\ScheduleObserver;
use App\Observers\SettingObserver;
use App\Observers\StationObserver;
use App\Observers\StatusObserver;
use App\Observers\TourObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    public function boot()
    {
        User::observe(UserObserver::class);
        Bus::observe(BusObserver::class);
        Repair::observe(RepairObserver::class);
        Driver::observe(DriverObserver::class);
        Client::observe(ClientObserver::class);
        Order::observe(OrderObserver::class);
        Company::observe(CompanyObserver::class);
        Route::observe(RouteObserver::class);
        City::observe(CityObserver::class);
        Schedule::observe(ScheduleObserver::class);
        Tour::observe(TourObserver::class);
        Review::observe(ReviewObserver::class);
        Station::observe(StationObserver::class);
        Status::observe(StatusObserver::class);
        Sale::observe(SaleObserver::class);
        Coupon::observe(CouponObserver::class);
        Setting::observe(SettingObserver::class);
        OrderPlace::observe(OrderPlaceObserver::class);
        Rent::observe(RentObserver::class);
        Agreement::observe(AgreementObserver::class);
    }
    
    public function register()
    {
        //
    }
}
