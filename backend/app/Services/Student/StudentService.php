<?php

namespace App\Services\Student;

use App\Events\StudentCreated;
use App\Models\PipelineStage;
use App\Models\Student;
use App\Models\User;
use App\Repositories\StudentRepository;
use App\Services\Audit\AuditLogger;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class StudentService
{
    public function __construct(
        private StudentRepository $students,
        private AuditLogger $auditLogger
    ) {}

    public function paginate(User $user, array $filters): LengthAwarePaginator
    {
        return $this->students->paginateForOrganization($user, $filters);
    }

    public function create(User $user, array $data): Student
    {
        return DB::transaction(function () use ($user, $data) {
            $stage = PipelineStage::query()
                ->where('organization_id', $user->organization_id)
                ->orderBy('sort_order')
                ->first();

            $count = Student::withoutGlobalScopes()->where('organization_id', $user->organization_id)->count() + 1;

            $student = Student::create([
                'organization_id' => $user->organization_id,
                'branch_id' => $data['branch_id'] ?? $user->branch_id,
                'pipeline_stage_id' => $data['pipeline_stage_id'] ?? $stage?->id,
                'student_code' => $data['student_code'] ?? 'STU-'.str_pad((string) $count, 5, '0', STR_PAD_LEFT),
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'] ?? null,
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'],
                'alternate_phone' => $data['alternate_phone'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'gender' => $data['gender'] ?? null,
                'nationality' => $data['nationality'] ?? null,
                'address' => $data['address'] ?? null,
                'city' => $data['city'] ?? null,
                'country' => $data['country'] ?? null,
                'academic_summary' => $data['academic_summary'] ?? null,
                'status' => $data['status'] ?? 'inquiry',
            ]);

            if (! empty($data['parents']) && is_array($data['parents'])) {
                foreach ($data['parents'] as $parent) {
                    $student->parentContacts()->create([
                        'organization_id' => $user->organization_id,
                        ...$parent,
                    ]);
                }
            }

            $this->auditLogger->log('student.created', $student, null, $student->toArray(), $user);
            StudentCreated::dispatch($student);

            return $student->load(['parentContacts', 'pipelineStage']);
        });
    }

    public function update(User $user, Student $student, array $data): Student
    {
        $old = $student->toArray();
        $student->fill(collect($data)->except(['parents', 'organization_id'])->all())->save();
        $this->auditLogger->log('student.updated', $student, $old, $student->fresh()->toArray(), $user);

        return $student->fresh(['parentContacts', 'pipelineStage', 'lead']);
    }

    public function delete(User $user, Student $student): void
    {
        $this->auditLogger->log('student.deleted', $student, $student->toArray(), null, $user);
        $student->delete();
    }
}
