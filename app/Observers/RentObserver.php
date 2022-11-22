<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 031 31.07.19
 * Time: 23:46
 */

namespace App\Observers;

use App\Models\Agreement;
use App\Models\Rent;

class RentObserver
{
    public function created(Rent $rent)
    {
        if ($agreement = $rent->agreement) {
            $agreement->updateStatus();
        }
    }

    public function updated(Rent $rent)
    {
        if ($agreement = $rent->agreement) {
            $agreement->updateStatus();
        }
    }

    public function deleted(Rent $rent)
    {
        if ($agreement = $rent->agreement) {
            $agreement->updateStatus();
        }
    }
}