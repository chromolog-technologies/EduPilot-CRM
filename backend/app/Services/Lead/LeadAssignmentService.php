<?php

namespace App\Services\Lead;

use App\Models\Lead;
use App\Models\User;
use App\Models\CounselorWorkload;
use App\Models\CounselorAssignment;

class LeadAssignmentService
{
    public function autoAssignLead(Lead $lead): ?User
    {
        $organization = $lead->organization;

        // Find available counselors
        $counselors = User::where('organization_id', $organization->id)
            ->where('status', 'active')
            ->get();

        if ($counselors->isEmpty()) {
            return null;
        }

        // Pick counselor with lowest active leads (least loaded algorithm)
        $selectedCounselor = $counselors->sortBy(function ($counselor) use ($organization) {
            $workload = CounselorWorkload::firstOrCreate(
                ['organization_id' => $organization->id, 'counselor_id' => $counselor->id],
                ['active_leads' => 0, 'capacity' => 50]
            );
            return $workload->active_leads;
        })->first();

        if ($selectedCounselor) {
            $lead->update([
                'assigned_counselor_id' => $selectedCounselor->id,
            ]);

            CounselorAssignment::create([
                'organization_id' => $organization->id,
                'student_id' => $lead->student_id,
                'counselor_id' => $selectedCounselor->id,
                'assigned_at' => now(),
                'is_active' => true,
            ]);

            // Update workload record
            $workload = CounselorWorkload::where('counselor_id', $selectedCounselor->id)->first();
            if ($workload) {
                $workload->increment('active_leads');
                $workload->update(['last_assigned_at' => now()]);
            }
        }

        return $selectedCounselor;
    }
}
