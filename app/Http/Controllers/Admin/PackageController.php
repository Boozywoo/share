<?php

namespace App\Http\Controllers\Admin;

use App\Notifications\Package\PackageNotification;

use Illuminate\Http\Request;
use App\Http\Requests\Admin\PackageRequest;
use App\Repositories\SelectRepositoryIndex;
use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Tour;
use App\Models\Route;
use Carbon\Carbon;


class PackageController extends Controller
{

    protected $entity = 'packages';
    protected $select;
    protected $selectIndex;


    public function __construct(SelectRepositoryIndex $selectRepositoryIndex)
    {
        $this->selectIndex = $selectRepositoryIndex;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(PackageRequest $request)
    {
        $package = Package::create(request()->all());

        if (request()->send_sms) {
            $package->phone = $package->phone_sender;
            $start = Carbon::parse($package->tour->date_time_start)->format('d.m.Y H:i');
            $bus = $package->tour->bus->name;
            $bus_numder = $package->tour->bus->number;
            $driver_phone = $package->tour->driver->phone;

            if (!$package->from_station_id) {
                $from = $package->package_from;
            } else {
                $from = $package->stationFrom->name;
            }

            $message = 'Zakaz posylki: ' . $package->id . "\n";
            $message .= 'Data: ' . $start . "\n";
            $message .= 'Avto: ' . $bus_numder . ' ' . $bus . "\n";
            $message .= 'Telefon Voditelya: ' . $driver_phone . "\n";
            $message .= 'Ot: ' . $from;


            $package->notify(new PackageNotification($message));
        }

        return $this->responseSuccess();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function showPopup(Package $package)
    {
        $currencies = $this->selectIndex->currencies();
//        $currencies->prepend('---');

        return ['html' => view('admin.packages.popups.add.content', compact('package', 'currencies') +
            ['entity' => $this->entity])->render()];
    }

    public function tourPackages($tour_id)
    {
        $tour = Tour::find($tour_id);
        $dateStartTime = $tour->date_time_start;
        $routeName = $tour->route->name;

        $packages = Package::where('tour_id', $tour_id)->get();

        return ['html' => view('admin.packages.popups.index.content', compact('packages', 'dateStartTime', 'routeName'))->render()];
    }

    public function getRoutes($date, $route = null)
    {
        $stations = null;
        $date = Carbon::createFromFormat('d.m.Y', $date)->format('Y-m-d');

        $tours = Tour::where('date_start', $date);

        if ($route) {
            $tours = $tours->where('tours.route_id', $route)->get();
            $stations = Route::find($route)->with('stations')->first();
        }
        else $tours = $tours->get();

        foreach ($tours as $tour) {
            $tour['start'] = $tour->time_start;
            $tour['route_id'] = $tour->route->id;
            $tour['route_name'] = $tour->route->name;
            $tour['bus_name'] = $tour->bus->name;
            $tour['driver_name'] = $tour->driver->full_name;
            $tour['driver_last_name'] = $tour->driver->last_name;
            $tour['driver_middle_name'] = $tour->driver->middle_name;
        }

        return ['tours' => $tours, 'stations' => $stations];
    }

    public function IndexPackagesByDate($date = null)
    {
        $date = Carbon::parse($date)->format('Y-m-d');

        $packages = Package::whereHas('tour', function ($query) use ($date) {
            return $query->where('tours.date_start', '=', $date);
        })->get();


        return ['html' => view('admin.packages.index.table', compact('packages') +
            ['entity' => $this->entity])->render()];
    }

}
