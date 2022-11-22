<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Repositories\SelectRepository;

class ReviewController extends Controller
{
	protected $entity = 'reviews';
	protected $select;

	public function __construct(SelectRepository $selectRepository)
	{
		$this->select = $selectRepository;
	}

	public function index()
	{
		$routes = $this->select->routes(auth()->id());

		$reviews = Review::filter(request()->except('routes') + ['routes' => auth()->user()->routeIds])
			->latest()
			->with('order.tour.driver', 'order.tour.route', 'client', 'order.tour.bus.company')
			->paginate();

		if (request()->ajax() && !request('_pjax')) return $this->ajaxView($reviews);

		return view('admin.' . $this->entity . '.index', compact('reviews', 'routes') + ['entity' => $this->entity]);
	}

	protected function ajaxView($reviews)
	{
		return response([
			'view' => view('admin.' . $this->entity . '.index.table', compact('reviews') + ['entity' => $this->entity])->render(),
			'pagination' => view('admin.partials.pagination', ['paginator' => $reviews])->render(),
		])->header('Cache-Control', 'no-cache, no-store');
	}
}