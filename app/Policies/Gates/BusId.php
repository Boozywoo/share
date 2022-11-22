<?php

namespace App\Policies\Gates;

use App\Models\Bus;
use App\Models\User;

class BusId
{
    public function index(User $user, $busId)
    {
        return Bus::whereId($busId)->filter(['companies' => $user->companyIds])->exists();
    }
}
