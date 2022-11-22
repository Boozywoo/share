<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cron;
use App\Repositories\SelectRepository;


class CronController extends Controller
{
    protected $entity = 'cron';

    protected $select;

    public function __construct()
    {


    }

    public function index()
    {

        $cron = Cron::where('is_active', 1)->orderBy("is_active", 'desc')->get();

        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($cron);
        return view('admin.' . $this->entity . '.index', compact('cron') + ['entity' => $this->entity]);
    }

    protected function ajaxView($cron)
    {
        return response([
            'view' => view('admin.' . $this->entity . '.index.table', compact('cron') + ['entity' => $this->entity])->render(),
        ])->header('Cache-Control', 'no-cache, no-store');
    }

    public function delete(Cron $cron)
    {

        $storage = storage_path();
        unlink($storage."/app/cron/cron.".$cron->type.".lockfile");
        unlink($storage . $cron->params);
        $cron->delete();
        return $this->responseSuccess();


    }


}

