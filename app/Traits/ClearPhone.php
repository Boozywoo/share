<?php

namespace App\Traits;


use App\Services\Prettifier;

trait ClearPhone
{
    public function clearPhone($phone)
    {
        return Prettifier::prettifyPhoneClear($phone);
    }

    public function prettyPhone($phone)
    {
        return Prettifier::prettifyPhone($phone);
    }
}