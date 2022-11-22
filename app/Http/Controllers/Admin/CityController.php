<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CityRequest;
use App\Models\City;
use DateTimeZone;
use DateTime;

class CityController extends Controller
{
	protected $entity = 'routes.cities';

	public function index()
	{
		$cities = City::filter(request()->all())
			->orderBy('name')
			->paginate();
		if (request()->ajax() && !request('_pjax')) return $this->ajaxView($cities);
		return view('admin.' . $this->entity . '.index', compact('cities') + ['entity' => $this->entity]);
	}

	public function create()
	{
		$city = new City();
		$city->timezone = config('app.timezone');
		$timezonelist = City::getTimezoneList();
		return view('admin.' . $this->entity . '.edit', compact('city','timezonelist') + ['entity' => $this->entity]);
	}

	public function edit(City $city)
	{
	    $timezonelist = City::getTimezoneList();
	    
		return view('admin.' . $this->entity . '.edit', compact('city','timezonelist') + ['entity' => $this->entity]);
	}

    public function store(CityRequest $request)
    {

        if ($id = request('id')) {
            $city = City::find($id);
            $stations = $city->stations()->count();
            if ($stations && request('status') == City::STATUS_DISABLE) {
                return $this->responseError(['message' => trans('messages.admin.cities.statuses.disabled')]);
            }
            if (City::where('name', request('name'))->where('id','!=', $city->id)->count())  {
                return $this->responseError(['message' => trans('messages.admin.cities.exists')]);
            } else {
                $city->update(request()->all());
            }
        } else {
            if (City::where('name', request('name'))->count())  {       // Существует ли уже город с таким именем
                return $this->responseError(['message' => trans('messages.admin.cities.exists')]);
            } else {
                $city = City::create(request()->all());
            }
        }

        return $this->responseSuccess();
    }

	public function delete(City $city)
	{
		$stations = $city->streets()->count();

		if (!$stations) {
			$city->delete();
			return $this->responseSuccess();
		}
		return $this->responseError(['message' => trans('messages.admin.cities.deleted.error')]);
	}

	protected function ajaxView($cities)
	{
		return response([
			'view' => view('admin.' . $this->entity . '.index.table', compact('cities') + ['entity' => $this->entity])->render(),
			'pagination' => view('admin.partials.pagination', ['paginator' => $cities])->render(),
		])->header('Cache-Control', 'no-cache, no-store');
	}
}