<?php

namespace App\Observers;

use App\Models\Route;
use App\Models\User;
use App\Models\Role;

class RouteObserver
{
	public function creating(Route $route)
	{
		$route->status = Route::STATUS_ACTIVE;
	}

	public function created(Route $route)
	{
		$users = User::filter(['roles' => Role::ROLE_SUPER_ADMIN])->get();
		foreach ($users as $user) {
			$user->routes()->attach($route->id);
		}
	}

	public function updated(Route $route)
	{
		$changed = $route->getDirty();
		$changedStatus = array_get($changed, 'status');

        if ($changedStatus) {
            foreach ($route->tours as $tour) {
                if ($tour->ordersReady->count() == 0) {
                    $tour->status = $changedStatus;
                    $tour->save();
                }
            }
        }

//		if ($changedStatus) {
//			if ($changedStatus == Route::STATUS_DISABLE) {
//				$users = User::filter(['route_id' => $route->id])->get();
//				foreach ($users as $user) {
//					$user->routes()->detach($route->id);
//				}
//
//			}
//		}
	}
}