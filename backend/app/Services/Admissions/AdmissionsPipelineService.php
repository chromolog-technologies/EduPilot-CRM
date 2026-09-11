<?php

namespace App\Services\Admissions;

use App\Enums\ActivityType;
use App\Events\StudentStageChanged;
use App\Models\Activity;
use App\Models\PipelineStage;
use App\Models\Student;
use App\Models\StudentStageHistory;
use App\Models\User;
use App\Services\Audit\AuditLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdmissionsPipelineService
{
    public function __construct(private AuditLogger $auditLogger) {}

    public function stages(User $user)
    {
        return PipelineStage::query()
            ->where('organization_id', $user->organization_id)
            ->orderBy('sort_order')
            ->get();
    }

    public function createStage(User $user, array $data): PipelineStage
    {
        return PipelineStage::create([
            'organization_id' => $user->organization_id,
            'name' => $data['name'],
            'slug' => $data['slug'] ?? Str::slug($data['name']),
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    public function updateStage(PipelineStage $stage, array $data): PipelineStage
    {
        $stage->fill($data)->save();

        return $stage;
    }

    public function deleteStage(PipelineStage $stage): void
    {
        $stage->delete();
    }

    public function changeStudentStage(User $user, Student $student, int $toStageId, ?string $notes = null): Student
    {
        return DB::transaction(function () use ($user, $student, $toStageId, $notes) {
            $fromStageId = $student->pipeline_stage_id;

            StudentStageHistory::create([
                'organization_id' => $user->organization_id,
                'student_id' => $student->id,
                'from_stage_id' => $fromStageId,
                'to_stage_id' => $toStageId,
                'changed_by' => $user->id,
                'notes' => $notes,
                'created_at' => now(),
            ]);

            $student->update(['pipeline_stage_id' => $toStageId]);

            Activity::create([
                'organization_id' => $user->organization_id,
                'student_id' => $student->id,
                'user_id' => $user->id,
                'type' => ActivityType::StageChange,
                'subject' => 'Admission stage changed',
                'description' => $notes,
                'metadata' => [
                    'from_stage_id' => $fromStageId,
                    'to_stage_id' => $toStageId,
                ],
            ]);

            $this->auditLogger->log('student.stage_changed', $student, [
                'pipeline_stage_id' => $fromStageId,
            ], [
                'pipeline_stage_id' => $toStageId,
            ], $user);

            StudentStageChanged::dispatch($student, $fromStageId, $toStageId);

            return $student->fresh(['pipelineStage', 'stageHistory']);
        });
    }
}
