<?php

namespace App\Services\Student;

use App\Models\Student;

class StudentProfileService
{
    public function profile(Student $student): Student
    {
        return $student->load([
            'parentContacts',
            'pipelineStage',
            'lead.source',
            'lead.counselor',
            'activeCounselorAssignment.counselor',
            'documents.requirement',
        ]);
    }
}
