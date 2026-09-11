<?php

namespace App\Services\Activity;

use App\Models\Activity;
use App\Models\Student;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ActivityService
{
    public function paginate(User $user, array $filters): LengthAwarePaginator
    {
        return Activity::query()
            ->with(['user', 'student'])
            ->when($filters['student_id'] ?? null, fn ($q, $id) => $q->where('student_id', $id))
            ->when($filters['type'] ?? null, fn ($q, $type) => $q->where('type', $type))
            ->latest()
            ->paginate((int) ($filters['per_page'] ?? 20));
    }

    public function create(User $user, array $data): Activity
    {
        return Activity::create([
            'organization_id' => $user->organization_id,
            'student_id' => $data['student_id'] ?? null,
            'lead_id' => $data['lead_id'] ?? null,
            'user_id' => $user->id,
            'type' => $data['type'],
            'subject' => $data['subject'] ?? null,
            'description' => $data['description'] ?? null,
            'metadata' => $data['metadata'] ?? null,
        ]);
    }

    public function forStudent(Student $student)
    {
        return $student->activities()->with('user')->latest()->paginate(20);
    }
}
