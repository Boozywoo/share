<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TemplateRequest;
use App\Models\Template;
use App\Models\TemplatePlace;
use App\Models\Tour;

class TemplateController extends Controller
{
	protected $entity = 'buses.templates';

	public function index()
	{
		$templates = Template::filter(request()->all())
			->latest()
			->paginate();
		if (request()->ajax() && !request('_pjax')) return $this->ajaxView($templates);
		return view('admin.' . $this->entity . '.index', compact('templates') + ['entity' => $this->entity]);
	}

	public function create()
	{
		$template = new Template();
		return view('admin.' . $this->entity . '.edit', compact('template') + ['entity' => $this->entity]);
	}

	public function edit(Template $template)
	{
		return view('admin.' . $this->entity . '.edit', compact('template') + ['entity' => $this->entity]);
	}

	public function store(TemplateRequest $request)
	{
		$templatePlaces = [];
		$seatPlaces = 0;
		$uniquePlaces = collect();
		foreach (request('placeTypes') as $placeType) {
			if ($placeType == TemplatePlace::TYPE_DRIVER || $placeType == TemplatePlace::TYPE_DELETE) {
				$arr = ['type' => $placeType];
			} else {
				if ($uniquePlaces->contains($placeType)) {
					return $this->responseError(['message' => 'Место №'. $placeType .' уже выбрано.']);
				} else {
					$uniquePlaces->push($placeType);
				}
				$arr = ['type' => TemplatePlace::TYPE_NUMBER, 'number' => $placeType];
				$seatPlaces++;
			}
			$templatePlaces[] = new TemplatePlace($arr);
		}
		if ($id = request('id')) {
			$template = Template::find($id);

			if ($template->buses->count() && $template->buses->first()->places != $seatPlaces) return $this->responseError(['message' => 'Нельзя изменить количество мест, т. к. шаблон используют автобусы ' . $template->buses->implode('number', ', ')]);
			//?Добавить проверка на будущие рейсы

			$template->update(request()->except('count_places') + ['count_places' => $seatPlaces]);
		} else {
			$template = Template::create(request()->except('count_places') + ['count_places' => $seatPlaces]);
		}
		$template->templatePlaces()->delete();
		$template->templatePlaces()->saveMany($templatePlaces);

		return $this->responseSuccess();
	}

	public function delete(Template $template)
	{
		$buses = $template->buses()->count();

		if (!$buses) {
			$template->delete();
			return $this->responseSuccess();
		}
		return $this->responseError(['message' => 'Нельзя удалить шаблон. Он назначен на автобус']);
	}

	protected function ajaxView($templates)
	{
		return response([
			'view' => view('admin.' . $this->entity . '.index.table', compact('templates') + ['entity' => $this->entity])->render(),
			'pagination' => view('admin.partials.pagination', ['paginator' => $templates])->render(),
		])->header('Cache-Control', 'no-cache, no-store');
	}
}