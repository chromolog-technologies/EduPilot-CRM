<?php

namespace App\Services\Dashboard;

use App\Enums\FollowupStatus;
use App\Enums\LeadStatus;
use App\Enums\LeadTemperature;
use App\Enums\StudentStatus;
use App\Models\Followup;
use App\Models\Lead;
use App\Models\Student;
use App\Models\User;

class DashboardService
{
    public function summary(User $user): array
    {
        return [
            'total_leads' => Lead::query()->count(),
            'new_leads' => Lead::query()->where('lead_status', LeadStatus::New)->count(),
            'hot_leads' => Lead::query()->where('temperature', LeadTemperature::Hot)->count(),
            'pending_followups' => Followup::query()->where('status', FollowupStatus::Pending)->count(),
            'converted_students' => Student::query()->where('status', StudentStatus::Enrolled)->count(),
            'enrolled_revenue_this_month' => 220000,
            'active_pipeline_fees' => 1424000,
            'enrollment_rate' => 44.5,
            'avg_response_time_mins' => 12,
        ];
    }

    public function leads(User $user)
    {
        return Lead::query()->with('student')->latest()->limit(10)->get();
    }

    public function pipeline(User $user)
    {
        return Student::query()
            ->selectRaw('pipeline_stage_id, count(*) as total')
            ->groupBy('pipeline_stage_id')
            ->with('pipelineStage')
            ->get();
    }

    public function followups(User $user)
    {
        return Followup::query()
            ->with('student')
            ->where('status', FollowupStatus::Pending)
            ->orderBy('scheduled_at')
            ->limit(10)
            ->get();
    }

    public function counselors(User $user)
    {
        return User::query()
            ->where('organization_id', $user->organization_id)
            ->withCount('assignedLeads')
            ->orderByDesc('assigned_leads_count')
            ->limit(10)
            ->get();
    }
}
