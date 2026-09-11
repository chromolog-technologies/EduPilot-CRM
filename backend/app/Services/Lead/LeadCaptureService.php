<?php

namespace App\Services\Lead;

use App\Models\LeadCapture;
use App\Models\Student;
use App\Models\Lead;
use App\Models\Organization;
use Illuminate\Support\Str;

class LeadCaptureService
{
    public function captureIncomingLead(Organization $organization, array $data): LeadCapture
    {
        $capture = LeadCapture::create([
            'organization_id' => $organization->id,
            'integration_id' => $data['integration_id'] ?? null,
            'lead_source_id' => $data['lead_source_id'] ?? null,
            'campaign_id' => $data['campaign_id'] ?? null,
            'external_lead_id' => $data['external_lead_id'] ?? (string) Str::uuid(),
            'external_reference' => $data['external_reference'] ?? null,
            'payload' => $data['payload'] ?? $data,
            'capture_status' => 'processing',
        ]);

        try {
            // Process payload to create Student & Lead
            $student = Student::create([
                'organization_id' => $organization->id,
                'student_code' => 'EDU-' . rand(1000, 9999),
                'first_name' => $data['first_name'] ?? 'Inquiry',
                'last_name' => $data['last_name'] ?? 'Lead',
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? '+91' . rand(7000000000, 9999999999),
                'location' => $data['location'] ?? 'Kerala',
                'status' => 'inquiry',
            ]);

            $lead = Lead::create([
                'organization_id' => $organization->id,
                'student_id' => $student->id,
                'lead_source_id' => $data['lead_source_id'] ?? null,
                'lead_status' => 'new',
                'priority' => $data['priority'] ?? 'medium',
                'temperature' => 'warm',
                'notes' => $data['notes'] ?? 'Captured via Automated Integration Pipeline.',
            ]);

            $capture->update([
                'capture_status' => 'processed',
                'processed_at' => now(),
            ]);

            return $capture;
        } catch (\Exception $e) {
            $capture->update([
                'capture_status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return $capture;
        }
    }
}
