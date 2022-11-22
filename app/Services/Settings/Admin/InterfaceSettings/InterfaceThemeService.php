<?php

namespace App\Services\Settings\Admin\InterfaceSettings;

use App\Models\InterfaceSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class InterfaceThemeService implements InterfaceSettingsServices
{
    /**
     * Get the interface theme.
     *
     * @return App\Models\InterfaceSetting
     */
    public static function getTheme()
    {
        $user = Auth::user();
        if (Auth::check() && \Cache::has('user_' . $user->id . '_interface_setting')) {
            $theme = \Cache::get('user_' . $user->id . '_interface_setting');
        } else {
            if (isset($user->interface_setting_id)) {
                Cache::put('user_' . $user->id . '_interface_setting', $user->interface_setting, 60);
                $theme = $user->interface_setting;
            } else {
                $theme = InterfaceSetting::where('theme_name', 'default')->first();
            }
        }

        return $theme;
    }
}