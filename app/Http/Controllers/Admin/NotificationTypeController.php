<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\NotificationTypeRequest;
use App\Models\Department;
use App\Models\NotificationType;
use App\Models\Role;
use App\Repositories\SelectRepository;
use App\Http\Controllers\Controller;

class NotificationTypeController extends Controller
{
    protected $select;
    protected $entity = 'settings.notifications';

    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
    }

    public function index()
    {
        $types = NotificationType::paginate(10);

        return view("admin.$this->entity.index", compact('types') + ['entity' => $this->entity]);
    }

    public function create()
    {
        $notification = new NotificationType();
        $roles = Role::all()->pluck('name', 'id');
        $readonly = false;

        $companyCarriers = $this->select->companyCarriers();
        $departments = Department::query()->whereIn('company_id', $companyCarriers->keys())->get()->all();

        $departmentsNotificationType =  [];
        $departmentsNotificationSelected = [];

        return view("admin.$this->entity.edit", compact('notification', 'departments', 'departmentsNotificationType', 'departmentsNotificationSelected', 'roles', 'readonly') + ['entity' => $this->entity]);
    }

    public function edit(NotificationType $notification)
    {
        $roles = Role::all()->pluck('name', 'id');
        $readonly = $notification->slug === 'default';

        $companyCarriers = $this->select->companyCarriers();
        $departments = Department::query()->whereIn('company_id', $companyCarriers->keys())->get()->all();

        $departmentsNotificationType = $notification->departments->count() ? $notification->departments->pluck('id')->toArray() : [];
        $departmentsNotificationSelected = $notification->departments_notification ?: [];

        return view("admin.$this->entity.edit", compact('notification','departments', 'departmentsNotificationType', 'departmentsNotificationSelected', 'roles', 'readonly') + ['entity' => $this->entity]);

    }

    public function store(NotificationTypeRequest $request)
    {
        $data = [];
        $data['name'] = $request->get('name');
        $data['slug'] = $request->get('slug');
        $data['approved'] = $request->get('approved') ?? 0;
        $data['denied'] = $request->get('denied') ?? 0;
        $data['read'] = $request->get('read') ?? 0;
        $data['view'] = $request->get('view') ?? 0;
        $data['role_id'] = $request->get('notification_role_id') ?? 0;

        if ($request->get('departments_notification')) {
            $data['departments_notification'] = array_keys($request->get('departments_notification'));
        }

        if ($request->get('notification_without_role')) {
            $data['role_id'] = null;
        }

        if ($id = $request->get('id')) {
            $type = NotificationType::find($id);
            if ($type->slug == 'default'){
                unset($data['name'],$data['slug'], $data['role_id']);
            }
            $type->update($data);
        } else {
            $type = NotificationType::create($data);
        }

        if ($request->get('departments')) {
            $type->departments()->sync(array_keys($request->get('departments')));
        }

        return $this->responseSuccess();
    }

    public function delete(NotificationType $notification)
    {
        if ($notification->delete()) {
            return $this->responseSuccess();
        }

        return $this->responseError();
    }

}
