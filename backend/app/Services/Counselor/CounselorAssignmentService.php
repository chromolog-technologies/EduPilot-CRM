<?php

namespace App\Services\Counselor;

use App\Enums\ActivityType;
use App\Models\Activity;
use App\Models\CounselorAssignment;
use App\Models\Student;
use App\Models\User;

class CounselorAssignmentService
{
    public function assign(Student $student, int $counselorId, User $assignedBy): CounselorAssignment
    {
        CounselorAssignment::query()
            ->where('student_id', $student->id)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        $assignment = CounselorAssignment::create([
            'organization_id' => $student->organization_id,
            'student_id' => $student->id,
            'counselor_id' => $counselorId,
            'assigned_by' => $assignedBy->id,
            'assigned_at' => now(),
            'is_active' => true,
        ]);

        Activity::create([
            'organization_id' => $student->organization_id,
            'student_id' => $student->id,
            'user_id' => $assignedBy->id,
            'type' => ActivityType::Assignment,
            'subject' => 'Counselor assignment',
            'description' => 'Counselor assignment updated.',
            'metadata' => ['counselor_id' => $counselorId],
        ]);

        return $assignment;
    }

    public function reassign(Student $student, int $counselorId, User $assignedBy): CounselorAssignment
    {
        return $this->assign($student, $counselorId, $assignedBy);
    }
}
