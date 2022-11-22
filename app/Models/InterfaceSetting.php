<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\Settings\Admin\InterfaceSettings\InterfaceThemeService;

class InterfaceSetting extends Model
{
    protected $fillable = ['font_color'];

    /**
     * Get the users that have it theme.
     */
    public function users()
    {
        return $this->hasMany('App\Models\User');
    }

    /**
     * Get the users unterface settings.
     *
     * @return integer
     */
    protected static function getUserInterfaceSettings()
    {
        $selectedColorTheme = InterfaceThemeService::getTheme();

        return $selectedColorTheme;
    }
}
