<?php

namespace App\Services\Notification\Providers;

use App\Models\Company;
use App\Models\Department;
use App\Models\Notification;
use App\Models\NotificationType;
use App\Models\User;
use App\Models\Wishes as WishesModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class WishesProvider extends AbstractNotifyProvider
{

    /**
     * @param WishesModel $model
     * @param array $data
     *
     * @return Notification
     */
    public function notify(Model $model, array $data): Notification
    {
        $typeId = $model->wishesType->notification_type_id;

        $notification = new Notification;
        $notification->user_id = $model->applicant_id;
        $notification->source_url = route('admin.wishes.edit', $model->id);
        $notification->source = $data['source'];
        $notification->small_text = $data['small_text'];
        $notification->text = ' ';
        $notification->approved = 0;
        $notification->read = 0;
        $notification->denied = 0;
        $notification->new = 1;
        $notification->for_all = 0;
        $notification->type_id = $typeId;
        $notification->save();

        $notification->responsible()->attach($this->getUserIds($model));

        return $notification;
    }


    private function getUserIds(WishesModel $wishes): Collection
    {
        // Получаем список всек пользователей у которых есть чекер "уведомление о заявках"
        $permissionsNotify = Company::getIdsNotifyWishes($wishes->applicant->company_id, auth()->id())->pluck('id', 'id')->toArray();

        /** @var NotificationType $notificationType */
        $notificationType = $wishes->wishesType->notificationType;
        $company = $wishes->applicant->company;

        $resultCollection = collect();

        // Уведомления учасниками заявки
        $resultCollection->put((int)$wishes->applicant->id, (int)$wishes->applicant->id);
        if ($wishes->delegate_id) {
            $resultCollection->put((int)$wishes->delegate_id, (int)$wishes->delegate_id);
        }

        if ($notificationType->role_id) {
            // Добавляем юзеров по роли
            $idsByRole = Company::getUserIdsByRole(
                $company->id,
                $notificationType->role_id,
                $resultCollection->toArray()
            )->pluck('id', 'id');

            $idsByRole->each(function ($id) use ($resultCollection, $permissionsNotify) {
                if (in_array($id, $permissionsNotify, true)) {
                    $resultCollection->put((int)$id, (int)$id);
                }
            });
        } else {
            // В таком случае добавляем юзеров по отделам

            // Разрешенные отделы
            $allowedDepartments = $notificationType->departments_notification ?: [];

            $directorsDepartments = $notificationType->departments->pluck('id')->toArray();

            $company->departments->each(function (Department $department) use ($resultCollection, $directorsDepartments, $allowedDepartments, $permissionsNotify) {
                if (in_array($department->id, $directorsDepartments, true) && $department->director && in_array($department->director->id, $permissionsNotify, true)) {
                    $resultCollection->put($department->director->id, $department->director->id);
                }

                if (in_array($department->id, $allowedDepartments, true)) {
                    $users = User::getDepartmentsUsers($department->id)->pluck('id', 'id');

                    foreach ($users as $userId) {
                        if ($department->director->id === $userId) {
                            continue;
                        }

                        if (in_array($userId, $permissionsNotify, true)) {
                            $resultCollection->put($userId, $userId);
                        }
                    }
                }
            });
        }

        // Отправлять ли уведомление начальнику заявляющего или руководителю его отдела
        if ($wishes->wishesType->notifi_supervisor) {
            if ($wishes->applicant->superior_id && !$resultCollection->get($wishes->applicant->superior_id)) {
                $resultCollection->put((int)$wishes->applicant->superior_id, (int)$wishes->applicant->superior_id);
            } else if (!$wishes->applicant->superior_id && $wishes->applicant->departament && $wishes->applicant->departament->director_id && !$resultCollection->get($wishes->applicant->departament->director_id)) {
                $resultCollection->put((int)$wishes->applicant->departament->director_id, (int)$wishes->applicant->departament->director_id);
            }
        }

        $accessCollection = collect();
        foreach ($resultCollection as $item) {
            $user = User::find($item);
            if($wishes->access($user)){
                $accessCollection->put((int)$user->id, (int)$user->id);
            }
        }

        return $accessCollection;
    }
}
