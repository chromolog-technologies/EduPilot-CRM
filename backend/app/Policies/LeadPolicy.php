<?php

namespace App\Policies;

use App\Models\Lead;
use App\Models\User;

class LeadPolicy extends OrganizationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('leads.view');
    }

    public function view(User $user, Lead $lead): bool
    {
        return $user->hasPermission('leads.view') && $this->sameOrganization($user, $lead);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('leads.create');
    }

    public function update(User $user, Lead $lead): bool
    {
        return $user->hasPermission('leads.update') && $this->sameOrganization($user, $lead);
    }

    public function delete(User $user, Lead $lead): bool
    {
        return $user->hasPermission('leads.delete') && $this->sameOrganization($user, $lead);
    }

    public function assign(User $user, Lead $lead): bool
    {
        return $user->hasPermission('leads.assign') && $this->sameOrganization($user, $lead);
    }
}
