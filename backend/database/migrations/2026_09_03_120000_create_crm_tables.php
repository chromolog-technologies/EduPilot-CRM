<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();

            $table->unique(['organization_id', 'slug']);
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('module');
            $table->timestamps();
        });

        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->unique(['role_id', 'permission_id']);
        });

        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->unique(['user_id', 'role_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles')->nullOnDelete();
        });

        Schema::create('lead_sources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code');
            $table->string('status')->default('active');
            $table->timestamps();

            $table->unique(['organization_id', 'code']);
            $table->index('organization_id');
        });

        Schema::create('pipeline_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['organization_id', 'slug']);
            $table->index(['organization_id', 'sort_order']);
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamps();

            $table->unique(['organization_id', 'key']);
        });

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('pipeline_stage_id')->nullable()->constrained('pipeline_stages')->nullOnDelete();
            $table->string('student_code');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('alternate_phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('nationality')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->text('academic_summary')->nullable();
            $table->string('status')->default('inquiry');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['organization_id', 'student_code']);
            $table->index(['organization_id', 'phone']);
            $table->index(['organization_id', 'email']);
            $table->index('pipeline_stage_id');
        });

        Schema::create('parent_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('relationship')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('occupation')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->index(['organization_id', 'student_id']);
        });

        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lead_source_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assigned_counselor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('lead_status')->default('new');
            $table->string('priority')->default('medium');
            $table->string('temperature')->default('warm');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('organization_id');
            $table->index('assigned_counselor_id');
            $table->index('lead_status');
            $table->index('priority');
            $table->index('temperature');
            $table->index('created_at');
            $table->index(['organization_id', 'assigned_counselor_id']);
            $table->index(['organization_id', 'created_at']);
        });

        Schema::create('student_stage_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('from_stage_id')->nullable()->constrained('pipeline_stages')->nullOnDelete();
            $table->foreignId('to_stage_id')->constrained('pipeline_stages')->cascadeOnDelete();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['organization_id', 'student_id']);
        });

        Schema::create('counselor_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('counselor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('assigned_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['organization_id', 'student_id', 'is_active']);
        });

        Schema::create('followups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('lead_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('followup_type')->default('call');
            $table->timestamp('scheduled_at');
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['organization_id', 'assigned_to']);
            $table->index(['organization_id', 'scheduled_at']);
            $table->index('status');
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('priority')->default('medium');
            $table->timestamp('due_at')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['organization_id', 'assigned_to']);
            $table->index(['organization_id', 'due_at']);
            $table->index('status');
        });

        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('lead_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type');
            $table->string('subject')->nullable();
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'student_id']);
            $table->index(['organization_id', 'created_at']);
        });

        Schema::create('document_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_required')->default(true);
            $table->string('status')->default('active');
            $table->timestamps();

            $table->index('organization_id');
        });

        Schema::create('student_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('document_requirement_id')->nullable()->constrained()->nullOnDelete();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('status')->default('uploaded');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('uploaded_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['organization_id', 'student_id']);
        });

        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('channel')->default('whatsapp');
            $table->string('external_conversation_id')->nullable();
            $table->string('status')->default('open');
            $table->timestamps();

            $table->index(['organization_id', 'student_id']);
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('conversation_id')->constrained()->cascadeOnDelete();
            $table->string('sender_type');
            $table->unsignedBigInteger('sender_id')->nullable();
            $table->string('message_type')->default('text');
            $table->text('body')->nullable();
            $table->string('external_message_id')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'conversation_id']);
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('title');
            $table->text('message');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'user_id', 'read_at']);
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');
            $table->string('entity_type')->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['organization_id', 'created_at']);
            $table->index(['entity_type', 'entity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversations');
        Schema::dropIfExists('student_documents');
        Schema::dropIfExists('document_requirements');
        Schema::dropIfExists('activities');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('followups');
        Schema::dropIfExists('counselor_assignments');
        Schema::dropIfExists('student_stage_history');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('parent_contacts');
        Schema::dropIfExists('students');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('pipeline_stages');
        Schema::dropIfExists('lead_sources');
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
        });
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
