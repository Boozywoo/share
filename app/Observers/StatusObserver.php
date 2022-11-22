<?php

namespace App\Observers;

use App\Models\Status;

class StatusObserver
{
	public function creating(Status $status)
	{
		$status->status = Status::STATUS_ACTIVE;
	}
}