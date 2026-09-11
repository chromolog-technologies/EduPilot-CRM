<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\FollowupSequence;
use App\Models\LeadAssignmentRule;
use App\Models\MessageTemplate;
use App\Models\Organization;
use App\Models\OrganizationFeature;
use App\Models\QuickReply;
use Illuminate\Database\Seeder;

class GoldFeatureSeeder extends Seeder
{
    public function run(): void
    {
        $organization = Organization::first();
        if (!$organization) return;

        $organization->update(['plan' => 'gold']);

        $goldFeatures = [
            'whatsapp_inbox',
            'followup_sequences',
            'ai_message_generation',
            'auto_assignment',
            'duplicate_detection',
            'campaigns',
            'lead_capture',
            'advanced_reports',
        ];

        foreach ($goldFeatures as $featureKey) {
            OrganizationFeature::firstOrCreate([
                'organization_id' => $organization->id,
                'feature_key' => $featureKey,
            ], [
                'enabled' => true,
                'configuration' => ['status' => 'active'],
            ]);
        }

        // Seed sample Campaign
        Campaign::firstOrCreate([
            'organization_id' => $organization->id,
            'code' => 'FB-MBBS-2026',
        ], [
            'name' => 'Facebook GCC MBBS Intake 2026',
            'platform' => 'facebook',
            'campaign_type' => 'lead_gen',
            'status' => 'active',
            'budget' => 50000.00,
        ]);

        // Seed sample Lead Assignment Rule
        LeadAssignmentRule::firstOrCreate([
            'organization_id' => $organization->id,
            'name' => 'Round Robin GCC NRI Leads',
        ], [
            'priority' => 1,
            'is_active' => true,
            'assignment_type' => 'least_loaded',
            'max_active_leads' => 50,
        ]);

        // Seed Message Templates
        MessageTemplate::firstOrCreate([
            'organization_id' => $organization->id,
            'name' => 'First Outreach - Medical Intake',
        ], [
            'category' => 'first_outreach',
            'channel' => 'whatsapp',
            'language' => 'English',
            'content' => 'Hi {{student_name}}! Congratulations on finishing 12th 🎓 EduPilot received your DM regarding MBBS admissions.',
            'status' => 'approved',
        ]);

        // Seed Quick Reply
        QuickReply::firstOrCreate([
            'organization_id' => $organization->id,
            'shortcut' => '.docs',
        ], [
            'name' => 'Request Documents Checklist',
            'content' => 'Please send your 12th marksheet, NEET scorecard, and passport copy to process your dossier.',
            'category' => 'general',
            'status' => 'active',
        ]);

        // Seed Followup Sequence
        $sequence = FollowupSequence::firstOrCreate([
            'organization_id' => $organization->id,
            'name' => 'Standard 7-Day Student Nurturing Sequence',
        ], [
            'description' => 'Automated WhatsApp nurturing for new medical intake leads.',
            'trigger_type' => 'lead_created',
            'status' => 'active',
            'stop_on_reply' => true,
            'stop_on_conversion' => true,
        ]);

        if ($sequence->steps()->count() === 0) {
            $sequence->steps()->createMany([
                [
                    'step_order' => 1,
                    'delay_value' => 0,
                    'delay_unit' => 'hours',
                    'channel' => 'whatsapp',
                    'action_type' => 'send_message',
                    'message_content' => 'Hi {{student_name}}! Welcome to EduPilot CRM. Let us know if you need help with university selection.',
                ],
                [
                    'step_order' => 2,
                    'delay_value' => 2,
                    'delay_unit' => 'days',
                    'channel' => 'whatsapp',
                    'action_type' => 'send_message',
                    'message_content' => 'Hi {{student_name}}! Following up on your MBBS application. Have you downloaded the brochure?',
                ],
            ]);
        }
    }
}
