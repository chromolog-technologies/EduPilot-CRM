<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Extend Organizations with plan
        if (!Schema::hasColumn('organizations', 'plan')) {
            Schema::table('organizations', function (Blueprint $table) {
                $table->string('plan', 30)->default('gold')->after('status');
            });
        }

        // 2. organization_features
        Schema::create('organization_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('feature_key', 100);
            $table->boolean('enabled')->default(true);
            $table->json('configuration')->nullable();
            $table->timestamps();

            $table->unique(['organization_id', 'feature_key']);
        });

        // 3. campaigns
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('code', 100);
            $table->string('platform', 50)->default('facebook');
            $table->string('campaign_type', 50)->default('lead_gen');
            $table->string('external_campaign_id', 150)->nullable();
            $table->string('status', 30)->default('active');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('budget', 12, 2)->nullable();
            $table->json('metadata')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['organization_id', 'platform']);
            $table->index(['organization_id', 'status']);
        });

        // 4. lead_captures
        Schema::create('lead_captures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('integration_id')->nullable();
            $table->foreignId('lead_source_id')->nullable()->constrained('lead_sources')->nullOnDelete();
            $table->foreignId('campaign_id')->nullable()->constrained('campaigns')->nullOnDelete();
            $table->string('external_lead_id', 150)->nullable();
            $table->string('external_reference', 200)->nullable();
            $table->json('payload')->nullable();
            $table->string('capture_status', 30)->default('received');
            $table->timestamp('processed_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'capture_status']);
            $table->index(['organization_id', 'created_at']);
        });

        // 5. lead_assignment_rules
        Schema::create('lead_assignment_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('name', 150);
            $table->integer('priority')->default(1);
            $table->boolean('is_active')->default(true);
            $table->string('assignment_type', 50)->default('round_robin');
            $table->json('conditions')->nullable();
            $table->json('counselor_ids')->nullable();
            $table->integer('max_active_leads')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['organization_id', 'is_active', 'priority']);
        });

        // 6. counselor_workloads
        Schema::create('counselor_workloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('counselor_id')->constrained('users')->cascadeOnDelete();
            $table->integer('active_leads')->default(0);
            $table->integer('pending_followups')->default(0);
            $table->integer('overdue_followups')->default(0);
            $table->integer('capacity')->default(50);
            $table->timestamp('last_assigned_at')->nullable();
            $table->string('status', 30)->default('available');
            $table->timestamps();

            $table->unique(['organization_id', 'counselor_id']);
        });

        // 7. lead_duplicates
        Schema::create('lead_duplicates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
            $table->foreignId('duplicate_lead_id')->constrained('leads')->cascadeOnDelete();
            $table->string('match_type', 50)->default('phone');
            $table->decimal('match_score', 5, 2)->default(90.00);
            $table->json('matched_fields')->nullable();
            $table->string('status', 30)->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'status']);
        });

        // 8. followup_sequences
        Schema::create('followup_sequences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->string('trigger_type', 50)->default('lead_created');
            $table->string('status', 30)->default('active');
            $table->boolean('stop_on_reply')->default(true);
            $table->boolean('stop_on_conversion')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['organization_id', 'status']);
        });

        // 9. followup_sequence_steps
        Schema::create('followup_sequence_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sequence_id')->constrained('followup_sequences')->cascadeOnDelete();
            $table->integer('step_order')->default(1);
            $table->integer('delay_value')->default(0);
            $table->string('delay_unit', 20)->default('hours');
            $table->string('channel', 30)->default('whatsapp');
            $table->string('action_type', 50)->default('send_message');
            $table->unsignedBigInteger('template_id')->nullable();
            $table->text('message_content')->nullable();
            $table->json('conditions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['sequence_id', 'step_order']);
        });

        // 10. followup_instances
        Schema::create('followup_instances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sequence_id')->constrained('followup_sequences')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->foreignId('current_step_id')->nullable()->constrained('followup_sequence_steps')->nullOnDelete();
            $table->string('status', 30)->default('active');
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('next_action_at')->nullable();
            $table->timestamp('last_action_at')->nullable();
            $table->string('stop_reason', 100)->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'status', 'next_action_at']);
        });

        // 11. message_templates
        Schema::create('message_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('category', 50)->default('first_outreach');
            $table->string('channel', 30)->default('whatsapp');
            $table->string('language', 20)->default('English');
            $table->text('content');
            $table->json('variables')->nullable();
            $table->string('external_template_id', 150)->nullable();
            $table->string('status', 30)->default('approved');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['organization_id', 'category']);
        });

        // 12. quick_replies
        Schema::create('quick_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('shortcut', 50);
            $table->text('content');
            $table->string('language', 20)->default('English');
            $table->string('category', 50)->default('general');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 30)->default('active');
            $table->timestamps();

            $table->index(['organization_id', 'shortcut']);
        });

        // 13. conversation_tags & pivot
        Schema::create('conversation_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('slug', 100);
            $table->string('color', 30)->default('#6366f1');
            $table->timestamps();

            $table->unique(['organization_id', 'slug']);
        });

        Schema::create('conversation_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('conversations')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('conversation_tags')->cascadeOnDelete();

            $table->unique(['conversation_id', 'tag_id']);
        });

        // 14. integrations & credentials
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('type', 50)->default('whatsapp');
            $table->string('name', 100);
            $table->string('provider', 100)->default('meta');
            $table->string('status', 30)->default('connected');
            $table->json('configuration')->nullable();
            $table->timestamp('last_synced_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['organization_id', 'type']);
        });

        Schema::create('integration_credentials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('integration_id')->constrained('integrations')->cascadeOnDelete();
            $table->string('credential_type', 50);
            $table->text('encrypted_value');
            $table->timestamp('expires_at')->nullable();
            $table->string('status', 30)->default('active');
            $table->timestamps();
        });

        // 15. webhook_events
        Schema::create('webhook_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('integration_id')->nullable()->constrained('integrations')->nullOnDelete();
            $table->string('event_id', 200)->nullable();
            $table->string('event_type', 100)->default('lead.created');
            $table->json('payload')->nullable();
            $table->text('signature')->nullable();
            $table->string('processing_status', 30)->default('received');
            $table->timestamp('processed_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('received_at')->useCurrent();
            $table->timestamps();

            $table->index(['organization_id', 'processing_status']);
        });

        // 16. ai_messages
        Schema::create('ai_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->foreignId('conversation_id')->nullable()->constrained('conversations')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('purpose', 50)->default('followup');
            $table->string('language', 30)->default('English');
            $table->string('tone', 30)->default('professional');
            $table->json('input_context')->nullable();
            $table->text('generated_message');
            $table->string('model', 100)->default('gemini-2.5-flash');
            $table->string('prompt_version', 50)->default('v1.0');
            $table->string('status', 30)->default('generated');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'student_id']);
        });

        // 17. automation_logs
        Schema::create('automation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('automation_type', 50);
            $table->string('entity_type', 50)->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->string('action', 100);
            $table->string('status', 30)->default('success');
            $table->json('details')->nullable();
            $table->timestamp('executed_at')->useCurrent();
            $table->timestamps();

            $table->index(['organization_id', 'automation_type']);
            $table->index(['organization_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('automation_logs');
        Schema::dropIfExists('ai_messages');
        Schema::dropIfExists('webhook_events');
        Schema::dropIfExists('integration_credentials');
        Schema::dropIfExists('integrations');
        Schema::dropIfExists('conversation_tag');
        Schema::dropIfExists('conversation_tags');
        Schema::dropIfExists('quick_replies');
        Schema::dropIfExists('message_templates');
        Schema::dropIfExists('followup_instances');
        Schema::dropIfExists('followup_sequence_steps');
        Schema::dropIfExists('followup_sequences');
        Schema::dropIfExists('lead_duplicates');
        Schema::dropIfExists('counselor_workloads');
        Schema::dropIfExists('lead_assignment_rules');
        Schema::dropIfExists('lead_captures');
        Schema::dropIfExists('campaigns');
        Schema::dropIfExists('organization_features');

        if (Schema::hasColumn('organizations', 'plan')) {
            Schema::table('organizations', function (Blueprint $table) {
                $table->dropColumn('plan');
            });
        }
    }
};
