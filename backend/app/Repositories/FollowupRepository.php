<?php

namespace App\Repositories;

use App\Models\Followup;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FollowupRepository
{
    public function paginateForOrganization(User $user, array $filters): LengthAwarePaginator
    {
        return Followup::query()
            ->where('followups.organization_id', $user->organization_id)
            ->with(['student', 'lead', 'assignee'])
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($filters['assigned_to'] ?? null, fn ($q, $id) => $q->where('assigned_to', $id))
            ->when($filters['student_id'] ?? null, fn ($q, $id) => $q->where('student_id', $id))
            ->latest('scheduled_at')
            ->paginate((int) ($filters['per_page'] ?? 20));
    }
}
