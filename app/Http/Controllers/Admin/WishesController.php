<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WishesCompleteRequest;
use App\Http\Requests\Admin\WishesCreateRequest;
use App\Http\Requests\Admin\WishesDelegateRequest;
use App\Http\Requests\Admin\WishesRequest;
use App\Models\Wishes;
use App\Services\Notification\NotificationService;
use App\Services\Wishes\WishesService;
use Auth;

class WishesController extends Controller
{
    protected $entity = 'wishes';
    protected $select;
    protected $statuses = ['new'=>'new', 'work'=>'work', 'completed'=>'completed'];


    public function __construct()
    {
    }

    public function index($status = 'new')
    {
        $wishesList = Wishes::getListByStatus($status)->paginate(10);
        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($wishesList, $status);

        return view('admin.' . $this->entity . '.index', compact('wishesList', 'status') + ['entity' => $this->entity]);
    }

    public function create()
    {
        $service = WishesService::create();
        return view('admin.' . $this->entity . '.edit', [
            'entity'        =>$this->entity,
            'wishes'        =>$service->getWishes(),
            'wishesTypes'   => $service->getWishesTypes(),
            'status'        => '',
            'statuses'      =>$this->statuses,
            'readonly'      =>  ''
        ]);

    }

    public function edit(Wishes $wishes)
    {
        if(!$wishes->access()){
            abort(403);
            die();
        }
        if (Auth::user()->id == $wishes->applicant->id and !Auth::user()->isSuperadmin and !Auth::user()->isModerator){
            $readonly = 'readonly';
        }

        $service = WishesService::create($wishes->id);
        return view('admin.' . $this->entity . '.edit',
            [
                'entity'        => $this->entity,
                'wishes'        => $wishes,
                'history'       => $service->getHistory(),
                'wishesTypes'   => $service->getWishesTypes(),
                'status'        => '',
                'statuses'      => $this->statuses,
                'readonly'      => $readonly ?? ''
            ]);

    }
    public function changeStatus(Wishes $wishes, $status = 'new')
    {
        if(!$wishes->access()){
            abort(403);
            die();
        }
        $service = WishesService::create($wishes->id);
        $service->newComment(request()->comment);
        $service->changeStatus($status);
        $this->responseSuccess();
    }
    public function complete(Wishes $wishes){
        if(!$wishes->accessComplete()){
            abort(403);
            die();
        }
        return view('admin.' . $this->entity . '.complete',
            [
                'entity'    => $this->entity,
                'wishes'    => $wishes,
                'statuses'  => $this->statuses]);

    }
    public function newComment(Wishes $wishes){
        if(request()->id and request()->comment){
            $service = WishesService::create($wishes->id);
            $service->newComment(request()->comment);
        }
        $this->responseSuccess();
    }

    public function completeStore(Wishes $wishes, WishesCompleteRequest $request){
        if(!$wishes->accessComplete()){
            abort(403);
            die();
        }
        $service = WishesService::create($wishes->id);
        $service->complete($request);
        return $this->responseSuccess(['redirect' => route('admin.wishes.index')]);
    }

    public function delegate(Wishes $wishes)
    {
        if(!$wishes->access()){
            abort(403);
            die();
        }
        $service = WishesService::create($wishes->id);

        $data = $service->getDelegateData();
        return view('admin.' . $this->entity . '.delegate',
            [
                'entity'    => $this->entity,
                'wishes'    => $wishes,
                'statuses'  => $this->statuses] + $data);

    }

    public function delegateStore(WishesDelegateRequest $request, Wishes $wishes)
    {
        if(!$wishes->access()){
            abort(403);
            die();
        }
        $service = WishesService::create($wishes->id);
        $service->delegate($request);
        return $this->responseSuccess(['redirect' => route('admin.wishes.index')]);
    }
    public function store(WishesCreateRequest $request)
    {
        $service = WishesService::create($request->get('id'));
        $service->store($request);

        return $this->responseSuccess();
    }

    protected function ajaxView($wishesList, $status)
    {
        return response([
            'view' => view('admin.' . $this->entity . '.index.table', compact('wishesList', 'status') + ['entity' => $this->entity, 'view' => 'settings.statuses'])->render(),
            'pagination' => view('admin.partials.pagination', ['paginator' => $wishesList])->render(),
        ])->header('Cache-Control', 'no-cache, no-store');
    }
}
