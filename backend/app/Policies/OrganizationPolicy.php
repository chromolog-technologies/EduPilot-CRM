<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

abstract class OrganizationPolicy
{
    protected function sameOrganization(User $user, Model $model): bool
    {
        return (int) $user->organization_id === (int) $model->getAttribute('organization_id');
    }
}
