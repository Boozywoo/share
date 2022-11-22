<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TourFromPullRequest;
use App\Http\Requests\Admin\TourRequest;
use App\Http\Requests\Admin\TourRentRequest;
use App\Http\Requests\Admin\TourToPullRequest;
use App\Http\Requests\Request;
use App\Models\Agreement;
use App\Models\Bus;
use App\Models\City;
use App\Models\Client;
use App\Models\Driver;
use App\Models\Department;
use App\Models\Order;
use App\Models\Tour;
use App\Models\Station;
use App\Models\Rent;
use App\Models\User;
use App\Models\Role;
use App\Notifications\Order\ActiveOrderNotification;
use App\Notifications\Order\ChangeOrderNotification;
use App\Notifications\Rent\ActiveRentNotification;
use App\Repositories\SelectRepository;
use App\Services\Client\StoreClientService;
use App\Services\Rent\CalculatePrice;
use App\Services\Rent\DuplicateBusService;
use App\Services\Rent\DuplicateDriverService;
use App\Services\Rent\RentService;
use App\Services\Rent\TimeSlider;
use App\Services\Geo\GeoService;
use App\Services\Tour\DuplicateService;
use App\Services\Rent\DuplicateServiceRent;
use App\Services\Tour\TourPullService;
use App\Validators\Tour\StoreTourValidator;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;

class RentController extends Controller
{
    protected $entity = 'rents';
    protected $select;

    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
    }

    public function index()
    {
        $dataFilter = request()->except('routes', 'date');
        $date = request('date', date('Y-m-d'));
        $date = date('Y-m-d', strtotime($date));

        $tours = Tour::filter($dataFilter)
            ->orderBy('time_start')
            ->with('driver', 'bus', 'rent')
            ->where('is_rent', true)
            ->where('date_start', '<=', $date)
            ->where('date_finish', '>=', $date)
            ->where('date_finish', '>=', $date);

        if (\Auth::user()->isMethodist) {
            $tours = $tours->whereHas('rent', function ($q) {
                $q->where('methodist_id', \Auth::id());
            });
        }

        $tours = $tours->get();
        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($tours);

        $buses = $this->select->buses(auth()->user()->companyIds, [Bus::STATUS_ACTIVE, Bus::STATUS_OF_REPAIR], true);
        $drivers = $this->select->drivers(auth()->user()->companyIds);
        $gmapsApi = true;
        return view('admin.' . $this->entity . '.index', compact('tours', 'buses', 'drivers', 'gmapsApi') + ['entity' => $this->entity]);
    }

    public function prepareData($data)
    {
        if ($tour = Tour::where('id', $data['id'])->first()) {
            $data['time_start'] = isset($data['time_start']) ? $data['time_start'] : $tour->time_start;

            $data['date_start'] = isset($data['date_start']) ? $data['date_start'] : $tour->date_start->format('d.m.Y');
            $data['time_finish'] = isset($data['time_finish']) ? $data['time_finish'] : $tour->time_finish;
            $data['date_finish'] = isset($data['date_finish']) ? $data['date_finish'] : $tour->date_finish->format('d.m.Y');
            $data['bus_id'] = isset($data['bus_id']) ? $data['bus_id'] : $tour->bus_id;
            $data['route_id'] = isset($data['route_id']) ? $data['route_id'] : $tour->route_id;
        } else {
            $data['id'] = null;
            $data['route_id'] = isset($data['route_id']) ? $data['route_id'] : null;
        }

        if (strlen($data['time_start']) == 5) $data['time_start'] .= ':00';
        if (strlen($data['time_finish']) == 5) $data['time_finish'] .= ':00';
        return $data;
    }

    public function store()
    {
        try {
            \DB::beginTransaction();
            $data = $this->prepareData(request()->all());
            if (isset($data['driver_id']) && !empty($data['driver_id'])) {
                $this->authorize('driver-id', request('driver_id'));
                $tourDuplicate = DuplicateDriverService::index($data['id'], $data['driver_id'], $data['time_start'], $data['date_start'], $data['time_finish'], $data['date_finish']);
                if ($tourDuplicate) {
                    if ($tourDuplicate->driver_id && $tourDuplicate->driver_id == $data['driver_id'])
                        return $this->responseError([
                            'message' => 'Водитель занят на это время',
                            'view' => view('admin.rents.schedule.ajaxContent', $this->scheduleData() + ['entity' => 'rents'])->render()
                        ]);
                }
            }
            if ((isset($data['bus_id']) && !empty($data['bus_id'])) || request('timeSliderBus')) {
                if ($timeSlider = request('timeSliderBus')) $data = TimeSlider::setBusByTimeSlider($timeSlider, $data);
                $this->authorize('bus-id', $data['bus_id']);
                $tourDuplicate = DuplicateBusService::index($data['id'], $data['bus_id'], $data['time_start'], $data['date_start'], $data['time_finish'], $data['date_finish']);
                if ($tourDuplicate) {
                    if ($tourDuplicate->bus_id && $tourDuplicate->bus_id == $data['bus_id'])
                        return $this->responseError([
                            'message' => 'Автобус занят на это время',
                            'view' => view('admin.rents.schedule.ajaxContent', $this->scheduleData() + ['entity' => 'rents'])->render()
                        ]);
                }
            }
            foreach (['bus_id', 'driver_id', 'route_id'] as $item)
                if (isset($data[$item]) && !$data[$item]) $data[$item] = null;//$data['client_id'] = StoreClientService::index($data);
            if ($data['id']) {
                $tour = Tour::find($data['id']);
                $tour->update($data);
            } else {
                $rent = Rent::create([]);
                $tour = Tour::create($data + ['rent_id' => $rent->id, 'is_rent' => true]);
            }
            RentService::index($tour, $data);
            if ($agreement = $tour->rent->agreement) {
                if ($agreement->balance < 0) {
                    \DB::rollBack();
                    return $this->responseError([
                        'message' => 'Недостаточно лимита на ' . $agreement->balance,
                    ]);
                }
            }
            /*if (empty(request()->get('tariff_id')) && $tour->bus_id) {
                CalculatePrice::index($tour);
            }*/
            \DB::commit();
            if ($tour->rent->methodist) {
                $tour->rent->methodist->notify(new ActiveRentNotification($tour));
            }
            if (request('timeSliderBus') || request('timeSliderDriver') || request('getView') == 'yes')
                return $this->schedule();

            return $this->responseSuccess();
        } catch (\Exception $e) {
        }
    }

    public function showPopup(Tour $tour)
    {
        $buses = $this->select->buses(auth()->user()->companyIds, [Bus::STATUS_ACTIVE, Bus::STATUS_OF_REPAIR], true);
        $routes = $this->select->routes(auth()->id(), true, true);
        $drivers = $this->select->drivers(auth()->user()->companyIds);
        $operators = $this->select->users();
        $busTypes = $this->select->busTypes();
        $cities = $this->select->citiesRent();
        $stations = Station::with('city')->whereStatus(Station::STATUS_ACTIVE)->get()->pluck('name_and_city', 'id');
        $stationsCoords = Station::with('city')->whereStatus(Station::STATUS_ACTIVE)->get()->pluck('coordinates', 'id');
        $coordinates = [];
        foreach ($stationsCoords as $key => $val)   {
            $coordinates[$key] = ['data-coords' => $val];
        }
        $companyCarriers = $this->select->companyCarriers();
        $companyCustomers = $this->select->companyCustomers();
        $companyCarrierId = \Auth::user()->company_id;


        if ($tour->rent && $tour->rent->agreement) {
            $companyCarrier = $tour->rent->agreement->customer_company_id;
            $companyCustomer = $tour->rent->agreement->service_company_id;
        } else {
            $companyCarrier = $companyCarriers->count() ? $companyCarriers->keys()->first() : null;
            $companyCustomer = $companyCustomers->count() ? $companyCustomers->keys()->first() : null;

        }
        $customerUsers =  User::whereHas('roles', function ($q) {
            $q->whereNotIn('slug', [Role::ROLE_SUPER_ADMIN]);
        })->get()->load('roles')->pluck('name_and_phone', 'id');
        $tours = Tour::whereDate('date_start', '=', date('Y-m-d'))
            ->whereStatus(Tour::STATUS_ACTIVE)->get()->pluck('info', 'id');

        $methodists = $this->select->methodists($companyCarrier);
        $agreements = Agreement::with('tariffs')
            ->where('service_company_id', $companyCarrier)
            ->where('customer_company_id', $companyCustomer)
            ->where('date_start', '<=', Carbon::now()->format('Y-m-d'))
            ->where('date_end', '>=', Carbon::now()->format('Y-m-d'))
            ->get();

        $tariffs = $agreements->count() ? $agreements->first()->tariffs->pluck('name', 'id') : [];
        $agreements = $agreements->count() ? $agreements->pluck('name', 'id') : [];

        return ['html' => view('admin.' . $this->entity . '.popups.edit.content',
            compact('tour', 'tariffs', 'agreements', 'buses', 'routes', 'drivers', 'operators', 'coordinates', 'companyCustomers',
                'tours', 'customerUsers', 'companyCarriers', 'busTypes', 'stations', 'companyCarrierId', 'methodists') +
            ['entity' => $this->entity])->render()];
    }

    protected function ajaxView($tours)
    {
        return response([
            'view' => view('admin.' . $this->entity . '.index.table', compact('tours') + ['entity' => $this->entity])->render(),
        ])->header('Cache-Control', 'no-cache, no-store');
    }

    protected function ajaxViewSchedule($data)
    {
        return response(['view' => view('admin.rents.schedule.ajaxContent', $data + ['entity' => 'rents'])->render()])
            ->header('Cache-Control', 'no-cache, no-store');
    }

    public function delete(Tour $tour)
    {
        $tour->delete();
        return $this->responseSuccess();
    }

    public function schedule()
    {
        $data = $this->scheduleData();
        if (request()->ajax()) return $this->ajaxViewSchedule($data);
        return view('admin.rents.schedule', $data);
    }

    protected function scheduleData()
    {
        $date = request('date', date('Y-m-d'));
        $date = date('Y-m-d', strtotime($date));
        $drivers = Driver::get();
        $rents = Tour::where('is_rent', true)
            ->where('date_start', '<=', $date)
            ->where('date_finish', '>=', $date)
            ->get();

        $FreeRents = Tour::where('is_rent', true)->where('date_start', '>=', date('Y-m-d'))->where('bus_id', null)->get();
        $buses = Bus::getRentBuses();
        $timeSliders = TimeSlider::index($buses, $rents, $date);
        return compact('date', 'drivers', 'rents', 'FreeRents', 'buses', 'timeSliders') + ['entity' => 'rents'];
    }

    public function getClientInfo()
    {
        $type = 'success';
        $clientPhone = request('phone');
        $client = Client::filter(['phone' => $clientPhone])->first();
        $message = trans('messages.admin.order.client_loaded');
        if (!$client) {
            $message = trans('messages.admin.order.client_created');
            $client = null;
        } else {
            if ($client->status == Client::STATUS_DISABLE) {
                $type = 'error';
                $message = trans('messages.admin.order.client_blacklisted');
            }
        }

        return $this->responseSuccess([
            'viewClientInfo' => view('admin.rents.popups.edit.user-info', compact('client'))->render(),
            'message' => $message,
            'type' => $type
        ]);
    }

    public function getAgreementTariffs()
    {
        $companyCarriers = $this->select->companyCarriers();
        $companyCustomers = $this->select->companyCustomers();

        $operators = $this->select->users();

        $companyCarrierId = request()->get('company_carrier_id');
        $companyCustomerId = request()->get('company_customer_id');
        $methodists = $companyCustomerId ? $this->select->methodists($companyCustomerId) : [];
        $agreementId = request()->get('agreement_id');

        $agreements = Agreement::with('tariffs')
            ->where('customer_company_id', $companyCustomerId)
            ->where('service_company_id', $companyCarrierId)
            ->where('date_start', '<=', Carbon::now()->format('Y-m-d'))
            ->where('date_end', '>=', Carbon::now()->format('Y-m-d'))
            ->get();

        $agreementTariffs = $agreements->where('id', $agreementId)->first();
        if ($agreementTariffs) {
            $tariffs = $agreementTariffs->tariffs->pluck('name', 'id');
        } else {
            $tariffs = $agreements->count() ? $agreements->first()->tariffs->pluck('name', 'id') : [];
            $agreementId = null;
        }
        $agreements = $agreements->count() ? $agreements->pluck('name', 'id') : [];
        return view('admin.rents.popups.edit.tariffs', compact('companyCustomers', 'companyCarriers',
            'agreements', 'tariffs', 'companyCarrierId', 'companyCustomerId', 'agreementId', 'operators', 'methodists'));
    }

    public function getAgreementInfo()
    {
        if ($agreementId = \request()->get('agreement_id')) {
            if ($agreement = Agreement::find($agreementId)) {
                return ['html' => view('admin.rents.popups.edit.agreement-info', compact('agreement'))->render()];
            }
        }
        return ['html' => ''];
    }

    public function getCoordinates()
    {
        if ($address = \request()->get('address')) {
            $address =  GeoService::getCoordinates($address);
        }
        if ($address_to = \request()->get('address_to')) {
            $address_to = GeoService::getCoordinates($address_to);
        }
        return compact('address', 'address_to');
    }

    public function getCoordinate($address)
    {
        $prepAddr = str_replace(' ', '+', $address);
        $geocode = file_get_contents('https://geocode-maps.yandex.ru/1.x/?geocode=' . $prepAddr);
        $string = <<<XML
$geocode
XML;
        $xml = simplexml_load_string($string);
        $data = explode(' ', $xml->GeoObjectCollection->featureMember->GeoObject->Point->pos);
        if (isset($data[1])) {
            return [$data[1], $data[0]];
        }
        return null;
    }
}
