<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 001 01.08.19
 * Time: 11:16
 */

namespace App\Observers;


use App\Models\Agreement;

class AgreementObserver
{
    public function updating(Agreement $agreement)
    {
        if ($agreement->date_end->timestamp > (time() - 24 * 60 * 60) && $agreement->limit > $agreement->AmountRents) {
            $agreement->enabled = true;
        } else {
            $agreement->enabled = false;
        }
    }
}