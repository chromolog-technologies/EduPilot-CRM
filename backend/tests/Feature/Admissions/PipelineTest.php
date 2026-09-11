<?php

namespace Tests\Feature\Admissions;

use App\Models\PipelineStage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PipelineTest extends TestCase
{
    use RefreshDatabase;

    public function test_stage_change_creates_history(): void
    {
        $user = $this->createUserWithPermissions(['students.view', 'students.create', 'students.update']);

        $first = PipelineStage::withoutGlobalScopes()->create([
            'organization_id' => $user->organization_id,
            'name' => 'New Inquiry',
            'slug' => 'new-inquiry',
            'sort_order' => 1,
            'is_active' => true,
        ]);
        $second = PipelineStage::withoutGlobalScopes()->create([
            'organization_id' => $user->organization_id,
            'name' => 'Counseling',
            'slug' => 'counseling',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $studentId = $this->postJson('/api/v1/students', [
            'first_name' => 'Lina',
            'phone' => '+15550007777',
        ], $this->authHeaders($user))->json('data.id');

        $this->patchJson('/api/v1/students/'.$studentId.'/stage', [
            'pipeline_stage_id' => $second->id,
            'notes' => 'Ready for counseling',
        ], $this->authHeaders($user))->assertOk();

        $this->getJson('/api/v1/students/'.$studentId.'/stage-history', $this->authHeaders($user))
            ->assertOk()
            ->assertJsonPath('data.0.to_stage_id', $second->id)
            ->assertJsonPath('data.0.from_stage_id', $first->id);
    }
}
