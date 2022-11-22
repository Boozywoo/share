<?php

namespace App\Policies\Gates;

use App\Models\Driver;
use App\Models\User;

class DriverId
{
    public function index(User $user, $driverId)
    {
        return Driver::whereId($driverId)->filter(['companies' => $user->companyIds])->exists();
    }
}
