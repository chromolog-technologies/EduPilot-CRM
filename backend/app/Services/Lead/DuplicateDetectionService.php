<?php

namespace App\Services\Lead;

use App\Models\Lead;
use App\Models\Student;
use App\Models\LeadDuplicate;

class DuplicateDetectionService
{
    public function detectDuplicates(Lead $lead): array
    {
        $student = $lead->student;
        if (!$student) return [];

        $duplicates = [];

        // Search for matching phone or email
        $existingStudents = Student::where('organization_id', $lead->organization_id)
            ->where('id', '!=', $student->id)
            ->where(function ($query) use ($student) {
                if ($student->phone) {
                    $query->orWhere('phone', $student->phone);
                }
                if ($student->email) {
                    $query->orWhere('email', $student->email);
                }
            })->get();

        foreach ($existingStudents as $existingStudent) {
            $existingLead = Lead::where('student_id', $existingStudent->id)->first();
            if (!$existingLead) continue;

            $matchType = ($existingStudent->phone === $student->phone) ? 'phone' : 'email';
            $matchScore = ($matchType === 'phone') ? 95.00 : 85.00;

            $duplicateRecord = LeadDuplicate::create([
                'organization_id' => $lead->organization_id,
                'lead_id' => $lead->id,
                'duplicate_lead_id' => $existingLead->id,
                'match_type' => $matchType,
                'match_score' => $matchScore,
                'matched_fields' => [$matchType],
                'status' => 'pending',
            ]);

            $duplicates[] = $duplicateRecord;
        }

        return $duplicates;
    }
}
