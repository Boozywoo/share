<?php

namespace App\Services\Notification\Providers;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractNotifyProvider
{

    abstract public function notify(Model $model, array $data): Notification;

}
