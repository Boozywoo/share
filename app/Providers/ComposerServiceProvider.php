<?php namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    public function boot()
    {
		// Setting
		view()->composer(['*'], function ($view) {
			$setting = cache()->remember('setting', 30, function () {
				return Setting::first();
			});
			$view->with(compact('setting'));
		});

		// Presets of interface render for admin pages
		view()->composer('admin.*', 'App\Http\ViewComposers\Admin\AdminSettingsComposer');
    }

    public function register()
    {

    }
}