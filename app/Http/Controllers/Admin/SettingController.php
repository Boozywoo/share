<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingRequest;
use App\Models\Setting;
use App\Models\Tour;
use App\Models\City;
use App\Models\Config;

class SettingController extends Controller
{
    public function edit()
    {
        $setting = Setting::first();
        $ticket_languages = [
          'ua' => 'Українська',
          'ru' => 'Русский',
          'bel' => 'Белорусский',
          'eng' => 'English'
        ];

        $ticket_types = [
            '1'=> 'Системный билет',
            '2'=> 'Официальный украинский билет',
            '3'=> 'Официальный билет Ижтрансфер',
            '4'=> 'Заказ-наряд Нортранс-Норильск',
            '5'=> 'Заказ-наряд Такси',
            '6'=> 'fly_shuttle_by',
            '7'=> 'Билет Тэшка',
            '8'=> 'Билет Флюгбус',
        ];
        $timezonelist = City::getTimezoneList();
        $phone_codes = \App\Models\Client::CODE_PHONES;
        $codes = $setting->phone_codes;
        $config['enable_transfer_api'] = Config::getValue('global', 'enable_transfer_api', true);

        return view('admin.settings.edit', compact('setting', 'ticket_languages', 'ticket_types','timezonelist', 'phone_codes', 'codes', 'config'));
    }

    public function setToursFieldsPopup()
    {
        $setting = Setting::first();
        return ['html' => view('admin.settings.popup', compact('setting'))->render()];
    }

    public function setToursFields()
    {
        Setting::first()->update(request()->all());
        return $this->responseSuccess();
    }
    
    public function store(SettingRequest $request)
    {
        $setting = Setting::first();
        Tour::AutoNoticeUpdate($setting->auto_turn_notification, $request->auto_turn_notification);
        $setting->update(['is_pay_on' => in_array($request->payment, ['online', 'both']), 'is_pay_cash' => in_array($request->payment, ['cash', 'both'])]);
        $setting->update(request()->except(['phone_codes']));
        $setting->update(['phone_codes' => implode(',', request()->get('phone_codes'))]);
        $setting->syncImages(request()->all());
        Config::store('global', ['enable_transfer_api' => $request->enable_transfer_api ?? true]);
        return $this->responseSuccess();
    }

    public function mobileEdit()
    {
        $mobileSettings = (object)Config::where('type', 'mobile_app')->pluck('value', 'key')->toArray();
        if (!isset($mobileSettings->show_place_numbers)) {
            $mobileSettings->show_place_numbers = true;
        }
        if (!isset($mobileSettings->calendar_days)) {
            $mobileSettings->calendar_days = 14;
        }

        return view('admin.settings.mobile_app.edit', compact('mobileSettings'));
    }

    public function mobileStore()
    {
        $settingsData = request()->except('_token');
        Config::store('mobile_app', $settingsData);

        return $this->responseSuccess();
    }


}
