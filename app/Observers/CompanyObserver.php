<?php

namespace App\Observers;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;

class CompanyObserver
{
	public function creating(Company $company)
	{
		$company->status = Company::STATUS_ACTIVE;
		$company->reputation = Company::REPUTATION_NEW;
	}

	public function created(Company $company)
	{
		$users = User::filter(['roles' => Role::ROLE_SUPER_ADMIN])->get();
		foreach ($users as $user) {
			$user->companies()->attach($company->id);
		}
	}

	public function updated(Company $company)
	{
		$changed = $company->getDirty();
		$changedStatus = array_get($changed, 'status');

		if ($changedStatus) {
			if ($changedStatus == Company::STATUS_DISABLE) {
				$users = User::filter(['company_id' => $company->id])->get();
				foreach ($users as $user) {
					$user->companies()->detach($company->id);
				}
			}
		}
	}
}