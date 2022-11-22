<?php

namespace App\Observers;

use App\Jobs\Tour\CompletedTourJob;
use App\Models\Tour;
use App\Services\Order\StationIntervalsService;
use App\Services\Prettifier;
use App\Services\Rent\CheckFullData;
use Bus;
use Log;
use App\Models\Setting;

class TourObserver
{
    public function creating(Tour $tour)
    {
        if (!$tour->status) $tour->status = Tour::STATUS_ACTIVE;
        $tour->type_driver = Tour::TYPE_DRIVER_NEW;
        $tour->bus_main_id = $tour->bus_id;
        $tour->is_edit = Setting::getField('auto_turn_notification');
        if (!$tour->is_rent && $tour->route)
            $tour->time_finish = Prettifier::prettifyTime($tour->id ? $tour->time_start : $tour->time_start . ':00', $tour->route->interval);

        if ($tour->rent_id) {
            if ($tour->date_finish) {
                $tour->date_time_finish = $tour->date_finish->format('Y-m-d') . ' ' . $tour->time_finish;
            }

            if ($tour->date_start) {
                $tour->date_time_start = $tour->date_start->format('Y-m-d') . ' ' . $tour->time_start;
            }

            $tour->rent->is_full_data = CheckFullData::index($tour);
            $tour->rent->save();
        }
    }

    public function updating(Tour $tour)
    {
        $changed = $tour->getDirty();

        $dateStartStatus = $tour->date_start && $tour->date_start->format('Y-m-d') != $tour->getOriginal('date_start') ? $tour->date_start : false;
        $timeStartStatus = array_get($changed, 'time_start');
        $busIdStatus = array_get($changed, 'bus_id');

        $comment = array_get($changed, 'comment');
        $isEdit = array_get($changed, 'is_edit');

        if (!$tour->is_rent)
            if ($dateStartStatus || $timeStartStatus || $busIdStatus) {
                if ($dateStartStatus || $timeStartStatus) {
                    foreach ($tour->orders as $order) {
                        list($stationFromInterval, $stationToInterval) = StationIntervalsService::index($tour->route_id, $order->station_from_id, $order->station_to_id);
                        $station_from_time = $order->stationFrom->status === 'collect' ? $order->station_from_time
                            : Prettifier::prettifyTime($tour->time_start, $stationFromInterval) . ':00';

                        $order->station_from_time = $station_from_time;
                        $order->station_to_time = Prettifier::prettifyTime($tour->time_start, $stationToInterval) . ':00';

                        $order->save();
                    }
                }
                if ($tour->ordersReady()->count()) $tour->is_edit = 1;
            }

        if ($tour->rent_id) {
            $tour->date_time_finish = $tour->date_finish->format('Y-m-d') . ' ' . $tour->time_finish;
            $tour->date_time_start = $tour->date_start->format('Y-m-d') . ' ' . $tour->time_start;
            $tour->rent->is_full_data = CheckFullData::index($tour);
            $tour->rent->save();
        }
    }

    public function saved(Tour $tour)
    {
        $changed = $tour->getDirty();
        $changedStatus = array_get($changed, 'status');

        if ($changedStatus) {
            if ($changedStatus == Tour::STATUS_REPAIR || $changedStatus == Tour::STATUS_DISABLE) {
                $tour->ordersReady()->update(['pull' => 1]);
            }

            if ($changedStatus == Tour::STATUS_COMPLETED) {
                dispatch(new CompletedTourJob($tour));
            }
        }
    }

    public function saving(Tour $tour)
    {
        $changed = $tour->getDirty();
        $changedStatus = array_get($changed, 'status');

        if ($changedStatus) {
            if ($changedStatus == Tour::STATUS_ACTIVE) {
                $tour->type_duplicate = null;
            }
        }
    }
}
