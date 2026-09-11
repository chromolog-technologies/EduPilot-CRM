<?php

namespace App\Services\Followup;

use App\Enums\FollowupStatus;
use App\Models\Activity;
use App\Models\Followup;
use App\Models\User;
use App\Repositories\FollowupRepository;
use App\Enums\ActivityType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FollowupService
{
    public function __construct(private FollowupRepository $followups) {}

    public function paginate(User $user, array $filters): LengthAwarePaginator
    {
        return $this->followups->paginateForOrganization($user, $filters);
    }

    public function create(User $user, array $data): Followup
    {
        $followup = Followup::create([
            'organization_id' => $user->organization_id,
            'student_id' => $data['student_id'] ?? null,
            'lead_id' => $data['lead_id'] ?? null,
            'assigned_to' => $data['assigned_to'] ?? $user->id,
            'followup_type' => $data['followup_type'] ?? 'call',
            'scheduled_at' => $data['scheduled_at'],
            'status' => FollowupStatus::Pending,
            'notes' => $data['notes'] ?? null,
        ]);

        Activity::create([
            'organization_id' => $user->organization_id,
            'student_id' => $followup->student_id,
            'lead_id' => $followup->lead_id,
            'user_id' => $user->id,
            'type' => ActivityType::Other,
            'subject' => 'Follow-up scheduled',
            'description' => $followup->notes,
        ]);

        return $followup->load(['student', 'assignee']);
    }

    public function update(Followup $followup, array $data): Followup
    {
        $followup->fill($data)->save();

        return $followup->fresh(['student', 'assignee']);
    }

    public function complete(User $user, Followup $followup): Followup
    {
        $followup->update([
            'status' => FollowupStatus::Completed,
            'completed_at' => now(),
        ]);

        return $followup->fresh(['student', 'assignee']);
    }

    public function cancel(Followup $followup): Followup
    {
        $followup->update(['status' => FollowupStatus::Cancelled]);

        return $followup->fresh(['student', 'assignee']);
    }

    public function delete(Followup $followup): void
    {
        $followup->delete();
    }
}
