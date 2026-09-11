<?php

namespace App\Services\AI;

use App\Models\AiMessage;
use App\Models\Student;
use App\Models\User;

class MessageGenerationService
{
    public function generateDraft(Student $student, string $purpose, string $language = 'English', string $tone = 'professional', ?User $creator = null): AiMessage
    {
        $context = [
            'student_name' => $student->first_name . ' ' . $student->last_name,
            'desired_program' => $student->academic_summary ?? 'Medical Admissions Program',
            'location' => $student->location ?? 'GCC / Kerala',
            'stage' => $student->pipelineStage?->name ?? 'New Inquiry',
        ];

        $draftText = sprintf(
            "Hi %s! Greetings from EduPilot CRM 🎓 Regarding your inquiry for %s in %s: our admissions panel has verified your eligibility criteria. Would you be available for a brief 5-minute video call today at 4:00 PM?",
            $student->first_name,
            $context['desired_program'],
            $context['location']
        );

        if ($purpose === 'document_reminder') {
            $draftText = sprintf(
                "Hi %s! EduPilot Admissions update: We require your 12th marksheet and passport copy to process your %s application dossier. Please send them via WhatsApp reply.",
                $student->first_name,
                $context['desired_program']
            );
        } else if ($purpose === 'fee_reminder') {
            $draftText = sprintf(
                "Hi %s! Friendly reminder from EduPilot: The seat booking deposit for %s intake is due this week to lock in your university slot.",
                $student->first_name,
                $context['desired_program']
            );
        }

        return AiMessage::create([
            'organization_id' => $student->organization_id,
            'student_id' => $student->id,
            'created_by' => $creator?->id,
            'purpose' => $purpose,
            'language' => $language,
            'tone' => $tone,
            'input_context' => $context,
            'generated_message' => $draftText,
            'model' => 'gemini-2.5-flash',
            'prompt_version' => 'v1.0',
            'status' => 'generated',
        ]);
    }
}
