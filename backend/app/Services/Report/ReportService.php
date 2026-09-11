<?php

namespace App\Services\Report;

use App\Models\Followup;
use App\Models\Lead;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function leads(User $user, array $filters)
    {
        return $this->baseLeadQuery($filters)
            ->selectRaw('lead_status, count(*) as total')
            ->groupBy('lead_status')
            ->get();
    }

    public function sources(User $user, array $filters)
    {
        return $this->baseLeadQuery($filters)
            ->selectRaw('lead_source_id, count(*) as total')
            ->groupBy('lead_source_id')
            ->with('source')
            ->get();
    }

    public function counselors(User $user, array $filters)
    {
        return $this->baseLeadQuery($filters)
            ->selectRaw('assigned_counselor_id, count(*) as total')
            ->groupBy('assigned_counselor_id')
            ->with('counselor')
            ->get();
    }

    public function pipeline(User $user, array $filters)
    {
        return Student::query()
            ->when($filters['from'] ?? null, fn ($q, $from) => $q->whereDate('created_at', '>=', $from))
            ->when($filters['to'] ?? null, fn ($q, $to) => $q->whereDate('created_at', '<=', $to))
            ->selectRaw('pipeline_stage_id, count(*) as total')
            ->groupBy('pipeline_stage_id')
            ->with('pipelineStage')
            ->get();
    }

    public function followups(User $user, array $filters)
    {
        return Followup::query()
            ->when($filters['from'] ?? null, fn ($q, $from) => $q->whereDate('scheduled_at', '>=', $from))
            ->when($filters['to'] ?? null, fn ($q, $to) => $q->whereDate('scheduled_at', '<=', $to))
            ->when($filters['counselor_id'] ?? null, fn ($q, $id) => $q->where('assigned_to', $id))
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->get();
    }

    public function conversions(User $user, array $filters)
    {
        $query = $this->baseLeadQuery($filters);

        $total = (clone $query)->count();
        $converted = (clone $query)->where('lead_status', 'converted')->count();

        return [
            'total_leads' => $total,
            'converted' => $converted,
            'conversion_rate' => $total > 0 ? round(($converted / $total) * 100, 2) : 0,
        ];
    }

    private function baseLeadQuery(array $filters)
    {
        return Lead::query()
            ->when($filters['from'] ?? null, fn ($q, $from) => $q->whereDate('created_at', '>=', $from))
            ->when($filters['to'] ?? null, fn ($q, $to) => $q->whereDate('created_at', '<=', $to))
            ->when($filters['counselor_id'] ?? null, fn ($q, $id) => $q->where('assigned_counselor_id', $id))
            ->when($filters['source_id'] ?? null, fn ($q, $id) => $q->where('lead_source_id', $id));
    }
}
