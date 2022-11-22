<?php

namespace App\Traits;

use App\Services\Prettifier;

trait PhoneTrait
{
    protected $codes = ['375', '380', '7'];
    public function setPhoneAttribute($phone)
    {
        $this->attributes['phone'] = $this->clearPhone($phone);
    }

    public function getEditPhoneAttribute()
    {
        return substr($this->phone, strlen($this->code_phone));
    }

    public function getCodePhoneAttribute()
    {
        foreach ($this->codes as $code)
            if (strpos($this->phone, $code) === 0) return $code;
    }
    
    public function setPhoneSubAttribute($phone)
    {
        $this->attributes['phone_sub'] = $this->clearPhone($phone);
    }

    public function getEditPhoneSubAttribute()
    {
        return str_replace('375', '', $this->phone_sub);
    }

    public function getPrettyPhoneAttribute()
    {
        return Prettifier::prettifyPhone($this->phone);
    }
}