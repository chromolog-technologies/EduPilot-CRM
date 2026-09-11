<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;

class ConversationPolicy extends OrganizationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('communications.view');
    }

    public function view(User $user, Conversation $conversation): bool
    {
        return $user->hasPermission('communications.view') && $this->sameOrganization($user, $conversation);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('communications.create');
    }
}
