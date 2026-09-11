<?php

namespace App\Policies;

use App\Models\StudentDocument;
use App\Models\User;

class DocumentPolicy extends OrganizationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('documents.view');
    }

    public function view(User $user, StudentDocument $document): bool
    {
        return $user->hasPermission('documents.view') && $this->sameOrganization($user, $document);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('documents.create');
    }

    public function delete(User $user, StudentDocument $document): bool
    {
        return $user->hasPermission('documents.delete') && $this->sameOrganization($user, $document);
    }
}
