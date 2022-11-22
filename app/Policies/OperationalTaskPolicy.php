<?php

namespace App\Policies;

use App\Models\OperationalTask\OperationalTask;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OperationalTaskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the operationalTask.
     *
     * @param User $user
     * @param OperationalTask $operationalTask
     * @return mixed
     */
    public function view(User $user, OperationalTask $operationalTask)
    {
        return $operationalTask->applicant_id === $user->id || $operationalTask->responsible_id === $user->id;
    }

    /**
     * Determine whether the user can create operationalTasks.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return OperationalTask::where('director_id', $user)->exists();
    }

    /**
     * Determine whether the user can update the operationalTask.
     *
     * @param User $user
     * @param OperationalTask $operationalTask
     * @return mixed
     */
    public function update(User $user, OperationalTask $operationalTask)
    {
        return $operationalTask->applicant_id === $user->id;
    }

    /**
     * Determine whether the user can delete the operationalTask.
     *
     * @param User $user
     * @param OperationalTask $operationalTask
     * @return mixed
     */
    public function delete(User $user, OperationalTask $operationalTask)
    {
        return $operationalTask->applicant_id === $user->id;
    }
}
