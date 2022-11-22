<?php

namespace App\Providers;

use App\Auth\Guards\TokenGuard;
use Carbon\Carbon;
use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
//         UserBusId::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        Gate::define('company-id', 'App\Policies\Gates\CompanyId@index');
        Gate::define('bus-id', 'App\Policies\Gates\BusId@index');
        Gate::define('route-id', 'App\Policies\Gates\RouteId@index');
        Gate::define('driver-id', 'App\Policies\Gates\DriverId@index');

        Passport::routes();
        Passport::enableImplicitGrant();


        \Auth::extend('token', function ($app, $name, array $config) {
            return new TokenGuard(\Auth::createUserProvider($config['provider']), request());
        });
    }
}
