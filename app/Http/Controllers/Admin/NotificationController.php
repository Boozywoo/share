<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CompanyRequest;
use App\Models\Company;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Route;
use App\Models\Tour;
use App\Models\User;
use App\Repositories\SelectRepository;
use Carbon\Carbon;

class NotificationController extends Controller
{
    protected $entity = 'notifications';

    protected $select;

    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
    }

    public function index()
    {
        $notifications = Notification::getRowsForIndex(request()->all());
        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($notifications);
        return view('admin.' . $this->entity . '.index', compact('notifications') + ['entity' => $this->entity]);

    }

    public function approvedUser(Notification $notification)
    {
        $notification->approved = 1;
        $notification->read = 1;
        $notification->denied = 0;
        $notification->new = 0;
        $notification->save();

        $notification->user->status = user::STATUS_ACTIVE;
        $notification->user->user_status = User::STATUS_CONFIRM;
        $notification->user->save();

        return $this->responseSuccess(['redirect' => route('admin.notifications.noti-index',['status' => '', 'create-date' => '', 'treatment-date'=>''])]);
    }

    public function denied(Notification $notification)
    {

        $notification->approved = 0;
        $notification->read = 1;
        $notification->denied = 1;
        $notification->new = 0;
        $notification->save();
        $notification->user->user_status = User::STATUS_DENIED;
        $notification->user->save();
        return $this->responseSuccess(['redirect' => route('admin.notifications.noti-index',['status' => '', 'create-date' => '', 'treatment-date'=>''])]);
    }

    public function read(Notification $notification)
    {
        $notification->approved = 0;
        $notification->read = 1;
        $notification->denied = 0;
        $notification->new = 0;
        $notification->save();
        return $this->responseSuccess();
    }

    public function count(){
        $count = Notification::whereHas('responsible', function ($q){
            $q->where('user_id', auth()->user()->id);
        })
        ->where('new', '1')
        ->count();

        $route = route('admin.notifications.noti-index', ['status' => '', 'create-date' => '', 'treatment-date'=>'']);
        return $this->responseSuccess(['view' => view('admin.partials.countRows', compact('count', 'route'))->render()]);
    }

    public function edit(Notification $notification)
    {
        return view('admin.' . $this->entity . '.edit', compact('notification') + ['entity' => $this->entity]);
    }

    protected function ajaxView($notifications)
    {
        return response([
            'view' => view('admin.' . $this->entity . '.index.table', compact('notifications') + ['entity' => $this->entity])->render(),
            'pagination' => view('admin.partials.pagination', ['paginator' => $notifications])->render(),
        ])->header('Cache-Control', 'no-cache, no-store');
    }
}
