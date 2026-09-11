<?php

use App\Http\Controllers\Api\V1\Activities\ActivityController;
use App\Http\Controllers\Api\V1\Admissions\PipelineController;
use App\Http\Controllers\Api\V1\AI\AiMessageController;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Automations\FollowupSequenceController;
use App\Http\Controllers\Api\V1\Automations\LeadAssignmentRuleController;
use App\Http\Controllers\Api\V1\Automations\LeadCaptureController;
use App\Http\Controllers\Api\V1\Automations\LeadDuplicateController;
use App\Http\Controllers\Api\V1\Campaigns\CampaignController;
use App\Http\Controllers\Api\V1\Communications\ConversationController;
use App\Http\Controllers\Api\V1\Communications\MessageTemplateController;
use App\Http\Controllers\Api\V1\Communications\QuickReplyController;
use App\Http\Controllers\Api\V1\Counselors\CounselorController;
use App\Http\Controllers\Api\V1\Dashboard\DashboardController;
use App\Http\Controllers\Api\V1\Documents\DocumentController;
use App\Http\Controllers\Api\V1\Followups\FollowupController;
use App\Http\Controllers\Api\V1\Integrations\IntegrationController;
use App\Http\Controllers\Api\V1\Leads\LeadController;
use App\Http\Controllers\Api\V1\Notifications\NotificationController;
use App\Http\Controllers\Api\V1\Reports\ReportController;
use App\Http\Controllers\Api\V1\Settings\SettingController;
use App\Http\Controllers\Api\V1\Students\StudentController;
use App\Http\Controllers\Api\V1\Tasks\TaskController;
use App\Http\Controllers\Api\V1\Webhooks\WebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('auth/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

    Route::get('documents/{document}/download', [DocumentController::class, 'download'])
        ->name('api.v1.documents.download');

    Route::middleware(['auth:sanctum', 'organization', 'throttle:api'])->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::post('auth/refresh', [AuthController::class, 'refresh']);
        Route::get('auth/me', [AuthController::class, 'me']);
        Route::put('auth/profile', [AuthController::class, 'profile']);
        Route::put('auth/password', [AuthController::class, 'password']);

        Route::get('dashboard/summary', [DashboardController::class, 'summary']);
        Route::get('dashboard/leads', [DashboardController::class, 'leads']);
        Route::get('dashboard/pipeline', [DashboardController::class, 'pipeline']);
        Route::get('dashboard/followups', [DashboardController::class, 'followups']);
        Route::get('dashboard/counselors', [DashboardController::class, 'counselors']);

        Route::apiResource('leads', LeadController::class);
        Route::patch('leads/{lead}/status', [LeadController::class, 'status']);
        Route::patch('leads/{lead}/priority', [LeadController::class, 'priority']);
        Route::patch('leads/{lead}/assign', [LeadController::class, 'assign']);

        Route::apiResource('students', StudentController::class);
        Route::get('students/{student}/profile', [StudentController::class, 'profile']);
        Route::get('students/{student}/activities', [StudentController::class, 'activities']);
        Route::get('students/{student}/followups', [StudentController::class, 'followups']);
        Route::get('students/{student}/documents', [StudentController::class, 'documents']);
        Route::get('students/{student}/conversations', [StudentController::class, 'conversations']);
        Route::post('students/{student}/documents', [DocumentController::class, 'store']);
        Route::get('students/{student}/documents/{document}', [DocumentController::class, 'show']);
        Route::delete('students/{student}/documents/{document}', [DocumentController::class, 'destroy']);
        Route::post('students/{student}/assign-counselor', [CounselorController::class, 'assign']);
        Route::patch('students/{student}/reassign-counselor', [CounselorController::class, 'reassign']);
        Route::get('students/{student}/stage-history', [PipelineController::class, 'stageHistory']);
        Route::patch('students/{student}/stage', [PipelineController::class, 'changeStage']);

        Route::get('counselors', [CounselorController::class, 'index']);
        Route::get('counselors/{counselor}', [CounselorController::class, 'show']);
        Route::get('counselors/{counselor}/students', [CounselorController::class, 'students']);
        Route::get('counselors/{counselor}/tasks', [CounselorController::class, 'tasks']);
        Route::get('counselors/{counselor}/followups', [CounselorController::class, 'followups']);

        Route::apiResource('followups', FollowupController::class);
        Route::patch('followups/{followup}/complete', [FollowupController::class, 'complete']);
        Route::patch('followups/{followup}/cancel', [FollowupController::class, 'cancel']);

        Route::apiResource('tasks', TaskController::class);
        Route::patch('tasks/{task}/complete', [TaskController::class, 'complete']);

        Route::get('pipeline/stages', [PipelineController::class, 'stages']);
        Route::post('pipeline/stages', [PipelineController::class, 'storeStage']);
        Route::put('pipeline/stages/{stage}', [PipelineController::class, 'updateStage']);
        Route::delete('pipeline/stages/{stage}', [PipelineController::class, 'destroyStage']);

        Route::get('activities', [ActivityController::class, 'index']);
        Route::post('activities', [ActivityController::class, 'store']);

        Route::get('document-requirements', [DocumentController::class, 'requirements']);
        Route::post('document-requirements', [DocumentController::class, 'storeRequirement']);
        Route::put('document-requirements/{requirement}', [DocumentController::class, 'updateRequirement']);
        Route::delete('document-requirements/{requirement}', [DocumentController::class, 'destroyRequirement']);

        Route::get('conversations/{conversation}/messages', [ConversationController::class, 'messages']);
        Route::post('conversations/{conversation}/messages', [ConversationController::class, 'storeMessage']);

        Route::get('reports/leads', [ReportController::class, 'leads']);
        Route::get('reports/sources', [ReportController::class, 'sources']);
        Route::get('reports/counselors', [ReportController::class, 'counselors']);
        Route::get('reports/pipeline', [ReportController::class, 'pipeline']);
        Route::get('reports/followups', [ReportController::class, 'followups']);
        Route::get('reports/conversions', [ReportController::class, 'conversions']);

        Route::get('settings', [SettingController::class, 'index']);
        Route::put('settings', [SettingController::class, 'update']);
        Route::get('users', [SettingController::class, 'users']);
        Route::post('users', [SettingController::class, 'storeUser']);
        Route::get('lead-sources', [SettingController::class, 'leadSources']);
        Route::post('lead-sources', [SettingController::class, 'storeLeadSource']);

        Route::get('notifications', [NotificationController::class, 'index']);
        Route::patch('notifications/{notification}/read', [NotificationController::class, 'markRead']);

        // ==========================================
        // GOLD VERSION REST ENDPOINTS
        // ==========================================
        Route::middleware('feature:campaigns')->group(function () {
            Route::apiResource('campaigns', CampaignController::class);
        });

        Route::middleware('feature:lead_capture')->group(function () {
            Route::get('lead-captures', [LeadCaptureController::class, 'index']);
            Route::post('lead-captures', [LeadCaptureController::class, 'store']);
        });

        Route::middleware('feature:auto_assignment')->group(function () {
            Route::get('assignment-rules', [LeadAssignmentRuleController::class, 'index']);
            Route::post('assignment-rules', [LeadAssignmentRuleController::class, 'store']);
        });

        Route::middleware('feature:duplicate_detection')->group(function () {
            Route::get('leads/duplicates', [LeadDuplicateController::class, 'index']);
            Route::post('leads/duplicates/{duplicate}/confirm', [LeadDuplicateController::class, 'confirm']);
            Route::post('leads/duplicates/{duplicate}/dismiss', [LeadDuplicateController::class, 'dismiss']);
        });

        Route::middleware('feature:followup_sequences')->group(function () {
            Route::get('followup-sequences', [FollowupSequenceController::class, 'index']);
            Route::post('followup-sequences', [FollowupSequenceController::class, 'store']);
        });

        Route::middleware('feature:whatsapp_inbox')->group(function () {
            Route::get('message-templates', [MessageTemplateController::class, 'index']);
            Route::post('message-templates', [MessageTemplateController::class, 'store']);

            Route::get('quick-replies', [QuickReplyController::class, 'index']);
            Route::post('quick-replies', [QuickReplyController::class, 'store']);

            Route::get('integrations', [IntegrationController::class, 'index']);
            Route::post('integrations', [IntegrationController::class, 'store']);
        });

        Route::middleware('feature:ai_message_generation')->group(function () {
            Route::post('ai/messages/generate', [AiMessageController::class, 'generate']);
            Route::post('ai/messages/{message}/approve', [AiMessageController::class, 'approve']);
        });

        Route::post('webhooks/whatsapp', [WebhookController::class, 'whatsapp']);
    });
});
