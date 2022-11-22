<?php

namespace App\Services\Code;

use App\Models\Code;
use App\Services\Prettifier;

class CheckCodeService
{
    public static function index($phone, $code)
    {
        $result = false;
        
        $phone = Prettifier::prettifyPhoneClear($phone);
        $code = Code::wherePhone($phone)->whereCode($code)->first();

        if ($code) {
            $code->delete();
            $result = true;
        }

        return $result;
    }
}