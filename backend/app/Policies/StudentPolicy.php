<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;

class StudentPolicy extends OrganizationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('students.view');
    }

    public function view(User $user, Student $student): bool
    {
        return $user->hasPermission('students.view') && $this->sameOrganization($user, $student);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('students.create');
    }

    public function update(User $user, Student $student): bool
    {
        return $user->hasPermission('students.update') && $this->sameOrganization($user, $student);
    }

    public function delete(User $user, Student $student): bool
    {
        return $user->hasPermission('students.delete') && $this->sameOrganization($user, $student);
    }
}
