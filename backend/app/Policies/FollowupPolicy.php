<?php

namespace App\Policies;

use App\Models\Followup;
use App\Models\User;

class FollowupPolicy extends OrganizationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('followups.view');
    }

    public function view(User $user, Followup $followup): bool
    {
        return $user->hasPermission('followups.view') && $this->sameOrganization($user, $followup);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('followups.create');
    }

    public function update(User $user, Followup $followup): bool
    {
        return $user->hasPermission('followups.update') && $this->sameOrganization($user, $followup);
    }

    public function delete(User $user, Followup $followup): bool
    {
        return $user->hasPermission('followups.update') && $this->sameOrganization($user, $followup);
    }
}
