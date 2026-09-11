<?php

namespace App\Services\Lead;

use App\Enums\ActivityType;
use App\Models\Activity;
use App\Models\Lead;
use App\Models\User;
use App\Services\Audit\AuditLogger;

class LeadStatusService
{
    public function __construct(private AuditLogger $auditLogger) {}

    public function updateStatus(User $user, Lead $lead, string $status): Lead
    {
        $old = $lead->lead_status?->value;
        $lead->update(['lead_status' => $status]);

        Activity::create([
            'organization_id' => $user->organization_id,
            'student_id' => $lead->student_id,
            'lead_id' => $lead->id,
            'user_id' => $user->id,
            'type' => ActivityType::Other,
            'subject' => 'Lead status updated',
            'description' => "Status changed from {$old} to {$status}.",
        ]);

        $this->auditLogger->log('lead.status_updated', $lead, ['lead_status' => $old], ['lead_status' => $status], $user);

        return $lead->fresh(['student', 'source', 'counselor']);
    }

    public function updatePriority(User $user, Lead $lead, string $priority): Lead
    {
        $lead->update(['priority' => $priority]);

        return $lead->fresh(['student', 'source', 'counselor']);
    }
}
