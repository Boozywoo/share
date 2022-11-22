<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Route;
use App\Models\Tour;
use App\Repositories\SelectRepository;
use Carbon\Carbon;

class PullController extends Controller
{
	protected $entity = 'pulls';
	protected $select;

	public function __construct(SelectRepository $selectRepository)
	{
		$this->select = $selectRepository;
	}

	public function count()
	{
		$count = Order::filter(['routes' => auth()->user()->routeIds])->wherePull(1)->count();
		return $this->responseSuccess(['view' => view('admin.partials.countPull', compact('count'))->render()]);
	}

	public function tours()
	{
		$dataFilter = request()->except('routes');
		$dataFilter['pull'] = true;
		if (request('date')) $dataFilter['date'] = Carbon::createFromFormat('d.m.Y', request('date'));
		$tours = Tour::filter($dataFilter + ['routes' => auth()->user()->routeIds])
			->orderBy('time_start')
			->with('route', 'driver', 'bus.template', 'ordersReady', 'ordersPull')
			->paginate();
		if (request()->ajax() && !request('_pjax')) return $this->ajaxView($tours);
		$routes = $this->select->routes(auth()->id());
		$buses = $this->select->buses(auth()->user()->companyIds);
		return view('admin.' . $this->entity . '.tours', compact('tours', 'routes', 'buses') + ['entity' => $this->entity]);
	}

	public function orders()
	{
		$dataFilter = request()->except('routes');
		if (request('date')) $dataFilter['date'] = Carbon::createFromFormat('d.m.Y', request('date', date('d.m.Y')));
		$orders = Order::filter($dataFilter + ['routes' => auth()->user()->routeIds])
			->wherePull(1)
			->with('tour.route', 'client')
			->latest()
			->paginate();
		if (request()->ajax() && !request('_pjax')) return $this->ajaxView(null, $orders, 'orders');
		$routes = $this->select->routes(auth()->id());
		return view('admin.' . $this->entity . '.orders', compact('orders', 'routes') + ['entity' => $this->entity]);
	}

	protected function ajaxView($tours = null, $orders = null, $type = 'tours')
	{
		return response([
			'view' => view('admin.' . $this->entity . '.' . $type. '.table', compact($type) + ['entity' => $this->entity])->render(),
			'pagination' => view('admin.partials.pagination', ['paginator' => ${$type}])->render(),
		])->header('Cache-Control', 'no-cache, no-store');
	}
}