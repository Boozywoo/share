<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CouponRequest;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponController extends Controller
{
	protected $entity = 'settings.coupons';
	protected $select;

	public function index()
	{
		$coupons = Coupon::filter(request()->all())->latest()->paginate();
		if (request()->ajax() && !request('_pjax')) return $this->ajaxView($coupons);
		return view('admin.' . $this->entity . '.index', compact('coupons') + ['entity' => $this->entity]);
	}

	public function create()
	{
		$coupon = new Coupon();
		return view('admin.' . $this->entity . '.edit', compact('coupon') + ['entity' => $this->entity]);
	}

	public function edit(Coupon $coupon)
	{
		return view('admin.' . $this->entity . '.edit', compact('coupon') + ['entity' => $this->entity]);
	}

	public function store(CouponRequest $request)
	{
		$data = request()->all();
		$data['date_start'] = Carbon::createFromFormat('d.m.Y', $data['date_start']);
		$data['date_finish'] = Carbon::createFromFormat('d.m.Y', $data['date_finish']);
		if ($id = request('id')) {
			$coupon = Coupon::find($id);
			$coupon->update($data);
		} else {
			$coupon = Coupon::create($data);
		}

		return $this->responseSuccess();
	}

	protected function ajaxView($coupons)
	{
		return response([
			'view' => view('admin.' . $this->entity . '.index.table', compact('coupons') + ['entity' => $this->entity])->render(),
			'pagination' => view('admin.partials.pagination', ['paginator' => $coupons])->render(),
		])->header('Cache-Control', 'no-cache, no-store');
	}
}