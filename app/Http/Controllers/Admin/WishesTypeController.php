<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\WishesTypeRequest;
use App\Models\Department;
use App\Models\NotificationType;
use App\Models\WishesType;
use App\Models\Role;
use App\Repositories\SelectRepository;
use App\Http\Controllers\Controller;

class WishesTypeController extends Controller
{
    protected $select;
    protected $entity = 'settings.wishes';

    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
    }

    public function index()
    {
        $types = WishesType::paginate(10);

        return view("admin.$this->entity.index", compact('types') + ['entity' => $this->entity]);
    }

    public function create()
    {
        $notifications = NotificationType::all()->pluck('name', 'id');
        $readonly = false;
        $wishesType = new WishesType();

        $companyCarriers = $this->select->companyCarriers();
        $departments = Department::query()->whereIn('company_id', $companyCarriers->keys())->get()->all();

        $departmentsWishesType =  [];
        $departmentsNotifiCheck =  [];

        return view("admin.$this->entity.edit", compact('notifications','departments', 'departmentsWishesType',  'departmentsNotifiCheck', 'wishesType', 'readonly') + ['entity' => $this->entity]);
    }

    public function edit(WishesType $wishesType)
    {
        $notifications = NotificationType::all()->pluck('name', 'id');
        $readonly = false;

        $companyCarriers = $this->select->companyCarriers();
        $departments = Department::query()->whereIn('company_id', $companyCarriers->keys())->get()->all();

        $departmentsWishesType = $wishesType->departments->count() ? $wishesType->departments->pluck('id')->toArray() : [];
        $departmentsNotifiCheck = $wishesType->getCheckDepartmentsNotification();

        return view("admin.$this->entity.edit", compact('notifications','departments', 'departmentsWishesType',  'departmentsNotifiCheck', 'wishesType', 'readonly') + ['entity' => $this->entity]);
    }

    public function store(WishesTypeRequest $request)
    {
        $data = [];
        $data['name'] = $request->get('name');
        $data['status'] = $request->get('status') ?? 0;
        $data['notification_type_id'] = $request->get('notification') ?? 0;
        $data['notifi_supervisor'] = $request->get('notifi_supervisor') ?? 0;
        $data['denied'] = $request->get('denied') ?? 0;
        $data['view'] = $request->get('view') ?? 0;
        $data['role_id'] = $request->get('role_id') ?? 0;
        if($request->get('departments_users')){
            $data['departments_notification'] = json_encode(array_keys($request->get('departments_users')));
        }

        if ($id = $request->get('id')) {
            $type = WishesType::find($id);
            $type->update($data);
        } else {
            $type = WishesType::create($data);
        }
        if($request->get('departments')){
            $type->departments()->sync(array_keys($request->get('departments')));
        }
        return $this->responseSuccess(['redirect' => route('admin.settings.wishes.index')]);
    }

    public function delete(WishesType $wishesType)
    {
        if ($wishesType->delete()) {
            return $this->responseSuccess();
        } else {
            return $this->responseError();
        }
    }

}
