<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy extends OrganizationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('tasks.view');
    }

    public function view(User $user, Task $task): bool
    {
        return $user->hasPermission('tasks.view') && $this->sameOrganization($user, $task);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('tasks.create');
    }

    public function update(User $user, Task $task): bool
    {
        return $user->hasPermission('tasks.update') && $this->sameOrganization($user, $task);
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->hasPermission('tasks.update') && $this->sameOrganization($user, $task);
    }
}
