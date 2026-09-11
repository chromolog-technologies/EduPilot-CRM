<?php

namespace App\Repositories;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LeadRepository
{
    public function paginateForOrganization(User $user, array $filters): LengthAwarePaginator
    {
        return Lead::query()
            ->where('leads.organization_id', $user->organization_id)
            ->with(['student', 'source', 'counselor'])
            ->when($filters['lead_status'] ?? null, fn ($q, $status) => $q->where('lead_status', $status))
            ->when($filters['priority'] ?? null, fn ($q, $priority) => $q->where('priority', $priority))
            ->when($filters['temperature'] ?? null, fn ($q, $temperature) => $q->where('temperature', $temperature))
            ->when($filters['counselor_id'] ?? null, fn ($q, $id) => $q->where('assigned_counselor_id', $id))
            ->when($filters['source_id'] ?? null, fn ($q, $id) => $q->where('lead_source_id', $id))
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->whereHas('student', function ($studentQuery) use ($search) {
                    $studentQuery->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate((int) ($filters['per_page'] ?? 20));
    }
}
