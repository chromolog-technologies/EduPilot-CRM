<?php

namespace App\Services\Task;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskService
{
    public function paginate(User $user, array $filters): LengthAwarePaginator
    {
        return Task::query()
            ->with(['student', 'assignee'])
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($filters['assigned_to'] ?? null, fn ($q, $id) => $q->where('assigned_to', $id))
            ->latest()
            ->paginate((int) ($filters['per_page'] ?? 20));
    }

    public function create(User $user, array $data): Task
    {
        return Task::create([
            'organization_id' => $user->organization_id,
            'student_id' => $data['student_id'] ?? null,
            'assigned_to' => $data['assigned_to'] ?? $user->id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'priority' => $data['priority'] ?? 'medium',
            'due_at' => $data['due_at'] ?? null,
            'status' => TaskStatus::Pending,
        ])->load(['student', 'assignee']);
    }

    public function update(Task $task, array $data): Task
    {
        $task->fill($data)->save();

        return $task->fresh(['student', 'assignee']);
    }

    public function complete(Task $task): Task
    {
        $task->update([
            'status' => TaskStatus::Completed,
            'completed_at' => now(),
        ]);

        return $task->fresh(['student', 'assignee']);
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }
}
