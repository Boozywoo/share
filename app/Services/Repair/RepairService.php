<?php

namespace App\Services\Repair;

use App\Models\RepairSparePart;

class RepairService
{

    public function getFinishedStatus($repairSpareParts)
    {
        $result = $repairSpareParts->map(function ($group) {
            $group = $group->sortByDesc('created_at');
            if ($group->first()->is_finished) {
                return 'finished';
            } elseif ($group->first()->status == RepairSparePart::STATUS_ORDERED) {
                return 'ordered';
            } else {
                return 'in_process';
            }
        });
        return $result->unique()->values()->toArray();
    }

    public function getSpareList($repairOrder)
    {
        return $repairOrder->spare_parts()->with('item.parent')->get()
            ->groupBy('spare_part_id')
            ->map(function ($group) {
                $group = $group->sortByDesc('created_at');
                $group->first()->is_finished = in_array($group->first()->status, RepairSparePart::STATUSES_FINISHED);
                return $group;
            });
    }

}