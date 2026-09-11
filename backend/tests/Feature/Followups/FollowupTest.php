<?php

namespace Tests\Feature\Followups;

use App\Models\PipelineStage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FollowupTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_and_complete_followup(): void
    {
        $user = $this->createUserWithPermissions([
            'students.view',
            'students.create',
            'followups.view',
            'followups.create',
            'followups.update',
        ]);

        PipelineStage::withoutGlobalScopes()->create([
            'organization_id' => $user->organization_id,
            'name' => 'New Inquiry',
            'slug' => 'new-inquiry',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $studentId = $this->postJson('/api/v1/students', [
            'first_name' => 'Noah',
            'phone' => '+15550006666',
        ], $this->authHeaders($user))->json('data.id');

        $followup = $this->postJson('/api/v1/followups', [
            'student_id' => $studentId,
            'scheduled_at' => now()->addDay()->toIso8601String(),
            'followup_type' => 'call',
            'notes' => 'First outreach',
        ], $this->authHeaders($user));

        $followup->assertCreated();
        $id = $followup->json('data.id');

        $this->patchJson('/api/v1/followups/'.$id.'/complete', [], $this->authHeaders($user))
            ->assertOk()
            ->assertJsonPath('data.status', 'completed');
    }
}
