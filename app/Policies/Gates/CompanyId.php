<?php

namespace App\Policies\Gates;

use App\Models\User;

class CompanyId
{
    public function index(User $user, $companyId)
    {
        return $companyId ? $user->companyIds->contains($companyId) : true;
    }
}
