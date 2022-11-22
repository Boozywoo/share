<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = 'App\Http\Controllers';

    public function boot()
    {
        parent::boot();
    }

    public function map()
    {
        $this->mapAdminRoutes();
        $this->mapDriverRoutes();
        $this->mapApiNorTransRoutes();
        $this->mapApiRoutes();
        $this->mapIndexRoutes();

    }

    protected function mapAdminRoutes()
    {
        Route::middleware(['web'])
            ->prefix('admin')
            ->as('admin.')
            ->namespace($this->namespace . '\Admin')
            ->group(base_path('routes/admin.php'));
    }

    protected function mapIndexRoutes()
    {
        Route::middleware(['web'])
            ->as('index.')
            ->namespace($this->namespace . '\Index')
            ->group(base_path('routes/index.php'));
    }

    protected function mapDriverRoutes()
    {
       Route::middleware(['driver'])
            ->prefix('driver')
            ->as('driver.')
            ->namespace($this->namespace . '\Driver')
            ->group(base_path('routes/driver.php'));
    }

    protected function mapApiRoutes()
    {
        Route::middleware('api')
            ->prefix('api')
            ->namespace($this->namespace . '\Api')
            ->group(base_path('routes/api.php'));
    }
    protected function mapApiNorTransRoutes()
    {
        Route::middleware('api-nor-trans')
            ->prefix('nortrans')
            ->namespace($this->namespace . '\Api\NorTrans')
            ->group(base_path('routes/nortrans.php'));
    }
}
