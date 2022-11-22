<?php

namespace App\Observers;

class SettingObserver
{
	public function saved()
	{
		cache()->forget('setting');
	}
}