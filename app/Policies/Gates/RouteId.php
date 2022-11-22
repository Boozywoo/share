<?php

namespace App\Policies\Gates;

use App\Models\User;

class RouteId
{
    public function index(User $user, $routeId)
    {
        return $routeId ? $user->routeIds->contains($routeId) : true;
    }
}
