<?php

namespace App\Repositories;

use App\Models\Student;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class StudentRepository
{
    public function paginateForOrganization(User $user, array $filters): LengthAwarePaginator
    {
        return Student::query()
            ->where('students.organization_id', $user->organization_id)
            ->with(['pipelineStage', 'lead', 'activeCounselorAssignment.counselor'])
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($filters['pipeline_stage_id'] ?? null, fn ($q, $id) => $q->where('pipeline_stage_id', $id))
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('student_code', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate((int) ($filters['per_page'] ?? 20));
    }
}
