<?php

namespace App\Http\Controllers\Admin;

use App;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClientRequest;
use App\Jobs\Client\ClientImportJob;
use App\Models\Client; 
use App\Models\Cron;
use App\Models\Order;
use App\Models\Route;
use App\Models\Tour;
use App\Repositories\SelectRepository;
use App\Services\Prettifier;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\City;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected $entity = 'clients';
    protected $select;

    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
    }

    public function index()
    {
        $clients = Client::filter(request()->all())->latest()->paginate();
        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($clients);
        return view('admin.' . $this->entity . '.index', compact('clients') + ['entity' => $this->entity]);
    }

    protected function ajaxView($clients)
    {
        return response([
            'view' => view('admin.' . $this->entity . '.index.table', compact('clients') + ['entity' => $this->entity])->render(),
            'pagination' => view('admin.partials.pagination', ['paginator' => $clients])->render(),
        ])->header('Cache-Control', 'no-cache, no-store');
    }

    public function create()
    {
        $client = new Client();
        $timezonelist = City::getTimezoneList();
        $statuses = $this->select->socialStatuses();
        $companies = $this->select->companies();
        return view('admin.' . $this->entity . '.edit', compact('client', 'statuses', 'companies', 'timezonelist') + ['entity' => $this->entity]);
    }

    public function edit(Client $client)
    {

        $timezonelist = City::getTimezoneList();
        $statuses = $this->select->socialStatuses();
        $companies = $this->select->companies();
        return view('admin.' . $this->entity . '.edit', compact('client', 'statuses', 'companies', 'timezonelist') + ['entity' => $this->entity]);
    }

    public function store(ClientRequest $request)
    {

        $data = request()->all();
        $data['date_social'] = $data['date_social'] ? Carbon::createFromFormat('d.m.Y', $data['date_social']) : null;
        $data['birth_day'] = $data['birth_day'] ? Carbon::createFromFormat('d.m.Y', $data['birth_day']) : null;
        $data['company_id'] = empty($data['company_id']) ? null : $data['company_id'];

        if ($id = request('id')) {
            $client = Client::find($id);
            $client->update($data);
        } else {
            $client = Client::create($data);
        }
        $client->syncImages($data);
        return $this->responseSuccess();
    }

    public function delete(Client $client)
    {
        $client->delete();
        return $this->responseSuccess();
    }

    public function statics()
    {
        if (!$dateFrom = request('date_from')) $dateFrom = Carbon::now()->subMonths(1)->format('Y-m-d');
        if (!$dateTo = request('date_to')) $dateTo = Carbon::now()->addDay()->format('Y-m-d');

        $clients = Client::filter(request()->all())
            ->with(['reviewsPositive', 'reviewsNegative'])
            ->latest()
            ->paginate();

        foreach ($clients as $client) {
            $client->orders = Order::filter([
                'client_id' => $client->id,
                'between' => ['dateFrom' => $dateFrom, 'dateTo' => $dateTo]
            ])
                ->select([
                    'orders.*', 'tours.route_id', 'tours.id', 'routes.name as route_name',
                    \DB::raw('sum(case when orders.status="active" then count_places else 0 end) as active'),
                    \DB::raw('sum(case when orders.status="active" && orders.type="waiting" then count_places else 0 end) as waiting'),
                    \DB::raw('sum(case when orders.status="active" && orders.type="completed" then count_places else 0 end) as completed'),
                    \DB::raw('sum(case when orders.status="disable" then count_places else 0 end) as disable'),
                ])->join('tours', 'orders.tour_id', '=', 'tours.id')
                ->join('routes', 'tours.route_id', '=', 'routes.id')
                ->groupBy('route_id')
                ->get();
        }

        $routes = Route::filter([auth()->id()])->get();

        if (request()->ajax() && !request('_pjax')) {
            return response([
                'view' => view('admin.' . $this->entity . '.statics.table', compact('clients') + ['entity' => $this->entity])->render(),
                'pagination' => view('admin.partials.pagination', ['paginator' => $clients])->render(),
            ])->header('Cache-Control', 'no-cache, no-store');
        }
        return view('admin.' . $this->entity . '.statics', compact('clients', 'routes') + ['entity' => $this->entity]);
    }

    public function import(Request $request)
    {
        if($request->hasFile('file')) {
            $file = $request->file('file')->getRealPath();
            $rows = 0;
            
            \Excel::filter('chunk')->load($file)->chunk(10000, function ($data) use (&$rows){
                \DB::beginTransaction();
                try {
                    foreach ($data as $item) {
                        $firstName = $item['imya'] ?? '';
                        $middleName = $item['otchestvo'] ?? '';
                        $lastName = $item['familiya'] ?? '';
                        $passport = $item['pasport'] ?? '';
                        $bonuses = $item['bonusy'] ?? '';
                        $phone = isset($item['telefon']) ? Prettifier::prettifyPhoneClear($item['telefon']) : '';

                        if (empty($phone)) {
                            continue;
                        }

                        Client::updateOrCreate(['phone' => $phone], [
                            'phone' => $phone,
                            'first_name' => $firstName,
                            'middle_name' => $middleName,
                            'last_name' => $lastName,
                            'passport' => $passport,
                            'bonus' => $bonuses,
                            'status' => 'active',
                        ]);
                       $rows++;
                    }
                    \DB::commit();
                    \Log::info('Clients import chunk complete. Rows processed: '.$rows);
                } catch (\Exception $e) {
                    \DB::rollBack();
                    return $this->responseError(['error' => $e->getMessage()]);
                }
            }, false);

            return $this->responseSuccess(['message' => 'Успешно обработано ' . $rows . ' записей']);
        }
    }


    public function importcron()
    {

        ini_set('max_execution_time', 900);


        $results = \Excel::load(request()->file('file'))->get();
        dispatch(new ClientImportJob($results, auth()->user()));
        return $this->responseSuccess(['message' => trans('validation.index.load_clients')]);

    }

    public function export()
    {
        $routes = \DB::table('orders')
            ->where('orders.status', 'active')
            ->where('orders.type', 'completed')
            ->join('clients', 'orders.client_id', '=', 'clients.id')
            ->join('tours', 'orders.tour_id', '=', 'tours.id')
            ->join('routes', 'tours.route_id', '=', 'routes.id')
            ->join('order_places', 'orders.id', '=', 'order_places.order_id')
            ->groupBy('tours.route_id')
            ->groupBy('orders.client_id')
            ->select(
                \DB::raw('sum(orders.count_places_appearance) as order_success'),
                \DB::raw('sum(orders.count_places_no_appearance) as order_error'),
                'routes.name_tr as routeName',
                'clients.first_name',
                'clients.middle_name',
                'clients.last_name',
                'clients.phone',
                'clients.passport',
                'clients.email'
            )->get()
            ->sortByDesc('order_success')
            ->groupBy('routeName');

        $response = array();
        foreach ($routes as $routeName => $clients) {
            $routeName = mb_substr($routeName, 0, 30);

            if (!isset($response[$routeName])) {
                $response[$routeName][] = [
                    trans('admin.clients.surname'),
                    trans('admin.clients.name'),
                    trans('admin.clients.patronymic'),
                    trans('admin.auth.tel'),
                    'Email',
                    trans('admin.auth.passport'),
                    trans('admin.users.per_of_success'),
                    trans('admin.users.per_of_not_success'),
                ];
            }

            foreach ($clients as $client) {
                $response[$routeName][] = [
                    str_replace("=","",$client->last_name),
                    str_replace("=","",$client->first_name),
                    str_replace("=","",$client->middle_name),
                    $client->phone,
                    $client->email,
                    str_replace("=","",$client->passport),
                    $client->order_success,
                    $client->order_error,
                ];

            }

        }

        \Excel::create('clients', function ($excel) use ($response) {

            foreach ($response as $key => $route) {
                //$key = substr($key, 0, 31);
                $excel->sheet($key, function ($sheet) use ($route) {
                    $sheet->fromArray($route, null, 'A1', false, false);
                });
            }
        })->download('xlsx');
    }

    protected function delete_client(Client $client)
    {
        Order::where('client_id', $client->id)->delete();
        $client->delete();
        return $this->responseSuccess();
    }

    protected function changeStatus(Client $client)
    {
        $client = Client::find(request('id'));
        $client->status_id = request('status_id');
        $client->save();
        return $this->responseSuccess();
    }

    protected function changeStatusDuration(Client $client)
    {
        $date = strtotime(request('date_social'));
        $client = Client::find(request('id'));
        $client->date_social = date('Y-m-d', $date);
        $client->save();
        return $this->responseSuccess();
    }

    protected function getSelectSocialStatus(Client $client)
    {
        $client = Client::where('phone', request('phone'))->first();

        if ($client)
            return $this->responseSuccess([
                'status_id' => $client->status_id,
                'date_social' => date("d.m.Y", strtotime($client->date_social)),
            ]);
        return [];
    }

    public function print_page_template_excel()
    {
        \Excel::create("шаблон для импорта клиентов", function ($excel) {
            $excel->sheet('Клиенты', function ($sheet) {

                $places[] = [
                    'фамилия' => '',
                    'имя' => '',
                    'отчество' => '',
                    'телефон' => '',
                    'бонусы' => '',
                    'паспорт' => '',
                ];
                $sheet->fromArray($places);
            });
        })->export('xlsx');
    }
}