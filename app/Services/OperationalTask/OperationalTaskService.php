<?php

namespace App\Services\OperationalTask;

use App\Helpers\FileHelper;
use App\Models\Department;
use App\Models\OperationalTask\OperationalTask;
use App\Models\OperationalTask\OperationalTaskStatus;
use Exception;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class OperationalTaskService
{
    /**
     * @var Application|mixed
     */
    private $directory;

    public function __construct()
    {
        $this->directory = config('filesystems.paths.operational_tasks');
    }

    /**
     * @param string $status
     * @return OperationalTask|\Illuminate\Database\Eloquent\Builder
     */
    public function getAll(string $status)
    {
        $userId = Auth::id();

        return OperationalTask::where('status', $status)->where(function($query) use ($userId){
            $query->orWhere('applicant_id', $userId)->orWhere('responsible_id', $userId);
        })
            ->with(['applicant', 'responsible', 'applicant.departament', 'lastComment']);
    }

    /**
     * @param array $data
     * @return OperationalTask
     * @throws Exception
     */
    public function create(array $data): OperationalTask
    {
        $applicantId = Auth::id();

        try {
            $task = OperationalTask::create(
                [
                    'applicant_id' => $applicantId,
                    'responsible_id' => $data['responsible'],
                    'subject' => $data['subject'],
                    'description' => $data['description'],
                    'status' => OperationalTaskStatus::STATUS_NEW
                ]
            );

            if (!empty($data['files'])) {
                foreach ($data['files'] as $file) {
                    $savedFile = FileHelper::saveFile($file, $this->directory);
                    $fileIds[] = $savedFile->id;
                }
                $task->files()->attach($fileIds);
            }
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(), $exception->getCode());
        }

        return $task;
    }

    /**
     * @return array
     */
    public function getResponsibles(): array
    {
        return Department::with('users')
            ->where('director_id', Auth::id())
            ->firstOrFail()
            ->users()
            ->pluck('first_name', 'id')
            ->toArray();
    }

    /**
     * @param int $id
     * @return OperationalTask
     */
    public function getTask(int $id): OperationalTask
    {
        $task = OperationalTask::with(
            [
                'applicant',
                'responsible',
                'files',
                'comments',
                'comments.user',
                'comments.files',
                'statuses',
                'statuses.user'
            ]
        )->find($id);

        $comments = $task->comments()->with(['user', 'files'])->get();
        $task->statuses()->with('user')->get()->each(function ($status) use ($comments) {
            $comments->push($status);
        });
        $task->setHistory($comments->sortBy('created_at'));

        return $task;
    }

    /**
     * @param int $id
     * @param array $data
     * @return OperationalTask
     */
    public function edit(int $id, array $data): OperationalTask
    {
        $userId = Auth::id();

        $task = OperationalTask::find($id);
        $task->responsible_id = $data['responsible'];
        $task->subject = $data['subject'];
        $task->description = $data['description'];
        $task->status = $data['status'];

        if ($task->isDirty('status')) {
            $task->statuses()->create(
                [
                    'task_id' => $task->id,
                    'user_id' => $userId,
                    'status' => $data['status']
                ]
            );
        }

        if ($task->isDirty()) {
            $task->save();
        }

        if ($data['comment']) {
            $comment = $task->comments()->create(
                [
                    'task_id' => $task->id,
                    'user_id' => $userId,
                    'comment' => $data['comment']
                ]
            );
            if (!empty(array_filter($data['comment-files']))) {
                foreach ($data['comment-files'] as $file) {
                    $savedFile = FileHelper::saveFile($file, $this->directory);
                    $fileIds[] = $savedFile->id;
                }
                $comment->files()->attach($fileIds);
            }
        }

        return $task;
    }
}