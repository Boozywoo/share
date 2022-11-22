<?php

use App\Models\InterfaceSetting;
use Illuminate\Database\Seeder;
use App\Models\User;

class InterfaceSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // чистим данные, если есть что-то лишнее
        $allUsers = User::all();
        foreach($allUsers as $user) {
            $user->interface_setting_id = null;
            $user->save();
        }
        
        // создаем данные для настроек интерфейса
        DB::table('interface_settings')->delete();
        $settings = [
            [
                'theme_name' => 'default',
                'fontColor' => 'lightFont',
                'bgColor' => 'blackBg',
                'bgHoverColor' => 'blackBgHover',
                'wrapperColor' => 'blackWrapper',
                'menuFocusStyle' => 'blackMenuFokus',
                'menuHoverStyle' => 'blackMenuHover',
                'phoneBtnBgStyle' => 'blackBgPhone',
                'bgWarning' => 'blackBgWarning',
                'confirmPopupBgColor' => '414141',
                'confirmPopupFontColor' => 'ffffff',
                'textLink' => 'textLink',
                'textLinkHover' => 'textLinkHover',
            ],
            [
                'theme_name' => 'light',
                'fontColor' => 'blackFont',
                'bgColor' => 'lightBg',
                'bgHoverColor' => 'lightBgHover',
                'wrapperColor' => 'lightWrapper',
                'menuFocusStyle' => 'lightMenuFokus',
                'menuHoverStyle' => 'lightMenuHover',
                'phoneBtnBgStyle' => 'lightBgPhone',
                'bgWarning' => 'lightBgWarning',
                'confirmPopupBgColor' => 'ffffff',
                'confirmPopupFontColor' => '000000',
                'textLink' => 'textLink',
                'textLinkHover' => 'textLinkHover',
            ],
            [
                'theme_name' => 'black',
                'fontColor' => 'lightFont',
                'bgColor' => 'blackBg',
                'bgHoverColor' => 'blackBgHover',
                'wrapperColor' => 'blackWrapper',
                'menuFocusStyle' => 'blackMenuFokus',
                'menuHoverStyle' => 'blackMenuHover',
                'phoneBtnBgStyle' => 'blackBgPhone',
                'bgWarning' => 'blackBgWarning',
                'confirmPopupBgColor' => '414141',
                'confirmPopupFontColor' => 'ffffff',
                'textLink' => 'textLink',
                'textLinkHover' => 'textLinkHover',
            ],
        ];
        foreach ($settings as $theme) {
            InterfaceSetting::create($theme);
        }

        // Всем юзерам устанавливаем тему интерфеса по умолчанию
        $defaultThemeId = InterfaceSetting::where('theme_name', 'black')->first()->id;
        $allUsers = User::all();
        foreach($allUsers as $user) {
            $user->interface_setting_id = null;
            $user->save();
            $user->interface_setting_id = $defaultThemeId;
            $user->save();
        }
    }
}
