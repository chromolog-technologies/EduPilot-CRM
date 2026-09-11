<?php

namespace App\Services\Lead;

use App\Enums\ActivityType;
use App\Enums\LeadPriority;
use App\Enums\LeadStatus;
use App\Enums\LeadTemperature;
use App\Events\LeadAssigned;
use App\Events\LeadCreated;
use App\Models\Activity;
use App\Models\Lead;
use App\Models\PipelineStage;
use App\Models\Student;
use App\Models\User;
use App\Repositories\LeadRepository;
use App\Services\Audit\AuditLogger;
use App\Services\Counselor\CounselorAssignmentService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LeadService
{
    public function __construct(
        private LeadRepository $leads,
        private CounselorAssignmentService $counselorAssignmentService,
        private AuditLogger $auditLogger
    ) {}

    public function paginate(User $user, array $filters): LengthAwarePaginator
    {
        return $this->leads->paginateForOrganization($user, $filters);
    }

    public function create(User $user, array $data): Lead
    {
        return DB::transaction(function () use ($user, $data) {
            $this->assertNotDuplicate($user->organization_id, $data['phone'], $data['email'] ?? null);

            $student = $this->findOrCreateStudent($user, $data);

            $lead = Lead::create([
                'organization_id' => $user->organization_id,
                'branch_id' => $data['branch_id'] ?? $user->branch_id,
                'student_id' => $student->id,
                'lead_source_id' => $data['lead_source_id'] ?? null,
                'assigned_counselor_id' => $data['assigned_counselor_id'] ?? null,
                'lead_status' => $data['lead_status'] ?? LeadStatus::New->value,
                'priority' => $data['priority'] ?? LeadPriority::Medium->value,
                'temperature' => $data['temperature'] ?? LeadTemperature::Warm->value,
                'notes' => $data['notes'] ?? null,
            ]);

            if (! empty($data['assigned_counselor_id'])) {
                $this->counselorAssignmentService->assign($student, (int) $data['assigned_counselor_id'], $user);
            }

            Activity::create([
                'organization_id' => $user->organization_id,
                'student_id' => $student->id,
                'lead_id' => $lead->id,
                'user_id' => $user->id,
                'type' => ActivityType::Other,
                'subject' => 'Lead created',
                'description' => 'Lead record created.',
            ]);

            $this->auditLogger->log('lead.created', $lead, null, $lead->toArray(), $user);
            LeadCreated::dispatch($lead);

            return $lead->load(['student', 'source', 'counselor']);
        });
    }

    public function update(User $user, Lead $lead, array $data): Lead
    {
        $old = $lead->toArray();
        $lead->fill(collect($data)->only([
            'branch_id',
            'lead_source_id',
            'lead_status',
            'priority',
            'temperature',
            'notes',
        ])->all())->save();

        if (! empty($data['first_name']) || ! empty($data['phone']) || array_key_exists('email', $data)) {
            $lead->student->fill(collect($data)->only([
                'first_name',
                'last_name',
                'email',
                'phone',
                'alternate_phone',
            ])->all())->save();
        }

        $this->auditLogger->log('lead.updated', $lead, $old, $lead->fresh()->toArray(), $user);

        return $lead->fresh(['student', 'source', 'counselor']);
    }

    public function delete(User $user, Lead $lead): void
    {
        $this->auditLogger->log('lead.deleted', $lead, $lead->toArray(), null, $user);
        $lead->delete();
    }

    private function assertNotDuplicate(int $organizationId, string $phone, ?string $email): void
    {
        $exists = Student::withoutGlobalScopes()
            ->where('organization_id', $organizationId)
            ->where(function ($q) use ($phone, $email) {
                $q->where('phone', $phone);
                if ($email) {
                    $q->orWhere('email', $email);
                }
            })
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'phone' => ['A student with this phone or email already exists in the organization.'],
            ]);
        }
    }

    private function findOrCreateStudent(User $user, array $data): Student
    {
        if (! empty($data['student_id'])) {
            return Student::findOrFail($data['student_id']);
        }

        $stage = PipelineStage::query()
            ->where('organization_id', $user->organization_id)
            ->orderBy('sort_order')
            ->first();

        $count = Student::withoutGlobalScopes()->where('organization_id', $user->organization_id)->count() + 1;

        $student = Student::create([
            'organization_id' => $user->organization_id,
            'branch_id' => $data['branch_id'] ?? $user->branch_id,
            'pipeline_stage_id' => $stage?->id,
            'student_code' => 'STU-'.str_pad((string) $count, 5, '0', STR_PAD_LEFT),
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'],
            'alternate_phone' => $data['alternate_phone'] ?? null,
        ]);

        return $student;
    }
}
