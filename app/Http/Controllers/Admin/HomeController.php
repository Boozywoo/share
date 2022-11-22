<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class HomeController extends Controller
{
    protected $entity = 'home';

    public function index()
    {
        return view('admin.home.index', ['entity' => $this->entity]);
    }

    public function reorder()
	{
		$data = request('data');
		$model = request('class');
		foreach ($data as $item) {
			$model::find($item['id'])->update(['order' => $item['order']]);
		}
		return $this->responseSuccess();
	}

    public function localization($locale)
    {
        \App::setLocale($locale);
        //store the locale in session so that the middleware can register it
        session()->put('locale', $locale);
        return redirect()->back();
    }

    public function isPaySystem()
    {
        $setting = Setting::first();
        $data = [
            'is_paid' =>  $setting->is_system_paid,
        ];

        return $data;
    }

}