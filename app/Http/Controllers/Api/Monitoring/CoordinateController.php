<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 006 06.02.19
 * Time: 23:05
 */

namespace App\Http\Controllers\Api\Monitoring;

use App\Http\Controllers\Controller;
use App\Models\MonitoringBus;
use App\Models\MonitoringSetting;
use App\Models\Bus;
use Carbon\Carbon;
use App\Models\Tour;
use App\Models\Setting;
use App\Channels\SmsChannel;
use Illuminate\Contracts\Queue\Monitor;

class CoordinateController extends Controller
{
    public function save()
    {
        $busNumber = request('number_bus');
        $bus = Bus::whereNumber($busNumber)->first();
        if (!$bus) {
            return $this->responseJsonError();
        }
        $location = request('location');
        $location_pr = json_decode($location, true);

        $speed = $location_pr['speed'] * 3.6;
        if (MonitoringSetting::first() != null) {
            $highSpeed = MonitoringSetting::pluck('high_speed')->first();
        } else {
            $highSpeed = 100;
        }

        // \Log::info(print_r(request()->all(), 1));
        if (!empty($location_pr)) {
            MonitoringBus::updateOrCreate(['bus_id' => $bus->id], [
                'bus_id' => $bus->id,
                'latitude' => $location_pr['latitude'],
                'longitude' => $location_pr['longitude'],
                'speed' => $location_pr['speed'],
            ]);
        }
        $now = Carbon::now(Setting::pluck('default_timezone')->first());
        $dateTimeTours = Tour::where('status', 'active')->where('date_time_start', '<', $now)
            ->where('date_time_finish', '>', $now)->where('bus_id', $bus->id)->first();

        if ($speed >= $highSpeed && !empty($dateTimeTours)) {
            $message = "Автобус " . $busNumber . " привысил скорость " . $speed . " км/ч с координатами: широта - "
                . $location_pr['latitude'] . ", долгота - " . $location_pr['longitude'];
            $sms = new SmsChannel();
            $sms->send(null, null, $message, $bus->company->phone_resp);
        }

    }
}