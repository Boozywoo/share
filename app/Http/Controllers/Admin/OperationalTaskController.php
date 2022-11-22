<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OperationalTasks\TaskCreateRequest;
use App\Http\Requests\Admin\OperationalTasks\TaskEditRequest;
use App\Services\OperationalTask\OperationalTaskService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OperationalTaskController extends Controller
{
    /**
     * @var OperationalTaskService
     */
    private $operationalTaskService;

    /**
     * @param OperationalTaskService $operationalTaskService
     */
    public function __construct(OperationalTaskService $operationalTaskService)
    {
        $this->operationalTaskService = $operationalTaskService;
    }

    /**
     * @return View
     */
    public function index(Request $request)
    {
        $status = $request->exists('status') ? $request->get('status') : 'new';

        $tasks = $this->operationalTaskService->getAll($status)->paginate(10);

        if (request()->ajax() && !request('_pjax')) {
            return $this->ajaxView($tasks, $status);
        }

        return view('admin.operational_tasks.index.index', compact('tasks', 'status'));
    }

    protected function ajaxView($tasks, $status)
    {
        return response(
            [
                'view' => view(
                    'admin.operational_tasks.index.table',
                    compact('tasks', 'status')
                    + ['entity' => 'operational_tasks', 'view' => 'settings.statuses']
                )->render(),
                'pagination' => view('admin.partials.pagination', ['paginator' => $tasks])->render(),
            ]
        )->header('Cache-Control', 'no-cache, no-store');
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $responsibles = $this->operationalTaskService->getResponsibles();

        return view('admin.operational_tasks.create.index', compact('responsibles'));
    }

    /**
     * @param TaskCreateRequest $request
     * @return array
     * @throws Exception
     */
    public function store(TaskCreateRequest $request): array
    {
        $task = $this->operationalTaskService->create($request->toArray());

        return $task ? $this->responseSuccess() : $this->responseError();
    }

    /**
     * @param int $taskId
     * @return View
     */
    public function detail(int $taskId): View
    {
        $task = $this->operationalTaskService->getTask($taskId);
        $responsibles = $this->operationalTaskService->getResponsibles();

        return view('admin.operational_tasks.detail.index', compact('task', 'responsibles'));
    }

    /**
     * @param int $taskId
     * @param TaskEditRequest $request
     * @return array
     */
    public function edit(int $taskId, TaskEditRequest $request): array
    {
        $task = $this->operationalTaskService->edit($taskId, $request->toArray());

        return $task ? $this->responseSuccess() : $this->responseError();
    }
}
