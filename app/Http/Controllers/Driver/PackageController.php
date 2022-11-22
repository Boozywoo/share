<?php

namespace App\Http\Controllers\Driver;

use App\Models\Package;

class PackageController extends Controller
{
    public function tourPackages($tour_id)
    {
        $packages = Package::where('tour_id', $tour_id)->get();

        return ['html' => view('driver.popups.packagesOftour.content', compact('packages'))->render()];
    }

    public function setStatus($id, $status)
    {
        $package = Package::find($id);
        if ($package->status == 'completed' || $package->status == 'returned') {
            $package->status = 'awaiting';
        } else {
            $package->status = $status;
        }
        $package->save();
    }
}
