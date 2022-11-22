<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ScheduleRequest;
use App\Models\Bus;
use App\Models\Order;
use App\Models\Route;
use App\Models\Driver;
use App\Models\Schedule;
use App\Models\ScheduleDay;
use App\Models\Tour;
use App\Repositories\SelectRepository;
use App\Services\Tour\DuplicateService;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    protected $entity = 'schedules';
    protected $select;

    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
    }

    public function index()
    {
        //?orderBY
        $schedules = Schedule::filter(request()->except('routes') + ['routes' => auth()->user()->routeIds])
            ->with('bus', 'route')
            ->orderByRaw("DATE_FORMAT(date_start, '%H') ASC")
            ->paginate();
        if (!empty(request('mass_price_update')))    {
            $schedules->each(function ($schedule){
                $schedule->scheduleDays()->update(['price' => request('mass_price_update')]);
            });
        }
        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($schedules);
        $buses = $this->select->buses(auth()->user()->companyIds);
        $routes = $this->select->routes(auth()->id(), true, true);
        return view('admin.' . $this->entity . '.index', compact('schedules', 'buses', 'routes') + ['entity' => $this->entity]);
    }

    public function create()
    {
        $schedule = new Schedule();
        $buses = $this->select->buses(auth()->user()->companyIds);
        $routes = $this->select->routes(auth()->id(), true, true);
        $drivers = $this->select->drivers(auth()->user()->companyIds);
        $system = Driver::whereStatus('system')->first() ?? null;
        return view('admin.' . $this->entity . '.create', compact('schedule', 'buses', 'routes', 'drivers', 'system') + ['entity' => $this->entity]);
    }

    public function edit(Schedule $schedule, $copyMode = false)
    {
        $this->authorize('route-id', $schedule->route_id);
        $buses = $this->select->buses(auth()->user()->companyIds);
        $routes = $this->select->routes(auth()->id(), true, true);
        $drivers = $this->select->drivers(auth()->user()->companyIds);
        $rotateDays = [];
        if ($schedule->is_days_rotate) {
            $diffInDays = $schedule->date_start->startOfDay()->diffInDays(Carbon::now(), false);
            $diffInDays = ($diffInDays < 0) ? 0 : $diffInDays;
            foreach (['10', '11'] as $dayName) {    // расписание по четным-нечетным
                $rotateDays[$dayName] = $schedule->scheduleDays()->where('day', $dayName)->first();
                if ($rotateDays[$dayName]) {
                    $dateStart = $schedule->date_start->addDays($diffInDays + $dayName % 2 - $diffInDays % 2);
                    if (($dayName % 2 - $diffInDays % 2) < 0) {
                        $dateStart->addDays(2);
                    }
                    $rotateDays[$dayName]->date_start = $dateStart;
                }
            }
        }
        return view('admin.' . $this->entity . '.edit', compact('schedule', 'buses', 'routes', 'drivers', 'rotateDays', 'copyMode') + ['entity' => $this->entity]);
    }

    public function store(ScheduleRequest $request)
    {
        $this->authorize('route-id', request('route_id'));
        $this->authorize('bus-id', request('bus_id'));
        $data = request()->all();
        $route = Route::find(request('route_id'));
        $dateTimeStart = Carbon::createFromFormat('d.m.Y H:i', $data['date_start_date'] . ' ' . $data['date_start_time']);
        $dateTimeFinish = Carbon::createFromFormat('d.m.Y H:i', $data['date_finish_date'] . ' ' . $data['date_start_time'])->addMinutes($route->interval);
        $data += [
            'date_start' => $dateTimeStart,
            'date_finish' => $dateTimeFinish,
        ];

        if ($dateTimeFinish < $dateTimeStart) return $this->responseError(['message' => 'Дата финиша должна быть больше старта']);

        if ($id = request('id')) {
            $schedule = Schedule::find($id);
            $this->authorize('route-id', $schedule->route_id);

            $newDays = $schedule->date_finish->diffInDays($dateTimeFinish, false);           // При изменении расписания увеличилась дата окончания на это кол-во дней, если со знаком минус, то уменьшилась
            if ($newDays > 0)  {   // увеличилась дата окончания
                $this->generateTours($schedule, $schedule->date_finish->addDays(1), $data);
            } else {                // уменьшилась дата окончания
                $tryDelOrders = $this->deleteToursAndOrders($schedule, $dateTimeFinish);
                if ($tryDelOrders) {
                    return $this->responseError(['message' => $tryDelOrders]);
                }
            };
            $schedule->update($data);

        } else {
            $schedule = Schedule::create($data);
        }

        $timeStart = $schedule->date_start->format('H:i');
        $timeFinish = $schedule->date_finish->format('H:i');

        if (request('id')) {        // Обновление расписания
            $tours = $schedule->tours()->filter(['date_to' => Carbon::now()->subDay()->format('Y-m-d')])->get();
            foreach ($tours as $tour) {
                $diffInDays = $schedule->date_start->startOfDay()->diffInDays($tour->date_start);
                $dayNumber = $schedule->is_days_rotate ? 10 + $diffInDays%2 : $tour->date_start->dayOfWeek;
                $driverId = array_get($data, 'days.' . $dayNumber . '.driver_id');
                $price = array_get($data, 'days.' . $dayNumber . '.price');
                $date_time_start = Carbon::createFromFormat('d.m.Y H:i', $tour->date_start->format('d.m.Y').' '.$timeStart);
                $tourDuplicate = DuplicateService::index($tour->id, $driverId, $schedule->bus_id, $tour->route_id, $timeStart . ':00', $tour->date_start->format('d.m.Y'));

                $dataStore = [
                    'time_start' => $timeStart,
                    'time_finish' => $timeFinish,
                    'bus_id' => $schedule->bus_id,
                    'route_id' => $schedule->route_id,
                    'price' => $price,
                    'driver_id' => $driverId,
                    'reservation_by_place' => $schedule->reservation_by_place,
                    'is_collect' => $schedule->is_collect,
                    'status' => $schedule->status,
                    'date_finish' => $date_time_start->copy()->addMinutes($schedule->route->interval)->format('d.m.Y'),
                    'date_time_start' => $date_time_start,
                    'date_time_finish' => $date_time_start->copy()->addMinutes($schedule->route->interval),
                ];

                if ($tourDuplicate) {
                    $dataStore['status'] = Tour::STATUS_DUPLICATE;
                } elseif ($tour->status == Tour::STATUS_ACTIVE) {
                    $dataStore['status'] = $schedule->status;
                }

                $tour->update($dataStore);
            }
            $schedule->scheduleDays()->delete();
            foreach ($request->days as $day => $dayData) {
                if  ($dayData['active'])    {
                    ScheduleDay::create([
                        'day' => $day,
                        'schedule_id' => $schedule->id,
                        'price' => $dayData['price'],
                        'driver_id' => $dayData['driver_id'],
                    ]);
                }
            }

        } else {        // Создание нового расписания
            $this->generateTours($schedule, $schedule->date_start, $data);
        }

        if ($route->is_egis) {
            app('App\Http\Controllers\Admin\RouteController')->sendEgis();
        }
        return $this->responseSuccess();
    }

    private function generateTours(Schedule $schedule, $dateStart, $data)    {
        $tours = [];
        $scheduleDays = [];
        $diffInDays = $dateStart->diffInDays($data['date_finish']);
        for ($i = 0; $i <= $diffInDays; $i++) {
            $currentDay = $dateStart->copy()->addDays($i);
            $dayNumber = $schedule->is_days_rotate ? 10 + $i%2 : $currentDay->dayOfWeek;
            if (array_get($data, "days.$dayNumber.active")) {
                $arr = array_get($data, "days.$dayNumber");
                $status = $schedule->status;
                $date_time_start = Carbon::createFromFormat('d.m.Y H:i', $currentDay->format('d.m.Y').' '.$schedule->date_start->format('H:i'));
                $tourDuplicate = DuplicateService::index(null, $arr['driver_id'], $schedule->bus_id, $schedule->route_id, $schedule->date_start->format('H:i') . ':00', $currentDay->format('d.m.Y'));
                if ($tourDuplicate) $status = Tour::STATUS_DUPLICATE;
                $tours[] = new Tour([
                    'bus_id' => $schedule->bus_id,
                    'route_id' => $schedule->route_id,
                    'driver_id' => $arr['driver_id'],
                    'price' => $arr['price'],
                    'date_start' => $currentDay->format('d.m.Y'),
                    'date_finish' => $date_time_start->copy()->addMinutes($schedule->route->interval)->format('d.m.Y'),
                    'time_start' => $schedule->date_start->format('H:i'),
                    'time_finish' => $schedule->date_finish->format('H:i'),
                    'date_time_start' => $date_time_start,
                    'date_time_finish' => $date_time_start->copy()->addMinutes($schedule->route->interval),
                    'status' => $status,
                    'reservation_by_place' => $schedule->reservation_by_place,
                    'is_collect' => $schedule->is_collect,
                ]);
                if (!isset($scheduleDays[$dayNumber])) {
                    $scheduleDays[$dayNumber] = new ScheduleDay([
                        'day' => $dayNumber,
                        'price' => $arr['price'],
                        'driver_id' => $arr['driver_id'],
                    ]);
                }
            }
        }
        $schedule->tours()->saveMany($tours);
        $schedule->scheduleDays()->saveMany($scheduleDays);
    }

    private function deleteToursAndOrders(Schedule $schedule, $afterDate) {
        $orders = Order::with('tour')
            ->whereHas('tour', function ($q) use ($schedule, $afterDate) {
                $q->filter([
                    'schedule_id' => $schedule->id,
                    'date_to' => $afterDate->format('Y-m-d')
                ]);
            });
        $dates = '';
        $orders->each(function ($order) use (&$dates) {
            if ($order->isActive) {
                $dates .= $order->tour->date_start->format('d.m') . ', ';
            }
        });
        $dates = substr($dates, 0, -2);

        if ($dates) {
            return 'Нельзя изменить конечную дату. Имеются активные брони на следующие даты: '.$dates;
        } else {
            $orders->delete();
            Tour::filter([
                'schedule_id' => $schedule->id,
                'date_to' => $afterDate->format('Y-m-d')
            ])->delete();
            return 0;
        }
    }

    public function getDriverId(Bus $bus)
    {
        //if ($bus->driver) return ['val' => $bus->driver->id];
    }

    public function delete(Schedule $schedule)
    {
        $activeOrders = Order::whereHas('tour', function ($q) use ($schedule) {
            $q->filter([
                'schedule_id' => $schedule->id,
                'status' => Tour::STATUS_ACTIVE,
            ])->future();
        })->active()->get();

        $activeTours = [];
        if ($activeOrders->count()) {
            $activeTours = $activeOrders->pluck('tour_id')->toArray();
            Tour::whereIn('id', $activeTours)->update(['schedule_id' => null]);
            //return $this->responseError(['message' => trans('messages.admin.schedules.delete.error')]); Не давало удалить расписание, т.к. были активные брони
        }

        $schedule->tours()
            ->whereNotIn('id', $activeTours)
            ->where('date_start', '>=', Carbon::now()
                ->format('Y-m-d'))
            ->delete();
        $schedule->delete();

        return $this->responseSuccess();
    }

    protected function ajaxView($schedules)
    {
        return response([
            'view' => view('admin.' . $this->entity . '.index.table', compact('schedules') + ['entity' => $this->entity])->render(),
            'pagination' => view('admin.partials.pagination', ['paginator' => $schedules])->render(),
        ])->header('Cache-Control', 'no-cache, no-store');
    }
}