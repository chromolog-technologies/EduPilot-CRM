<?php

namespace Tests\Feature\Leads;

use App\Models\Organization;
use App\Models\PipelineStage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_lead(): void
    {
        $user = $this->createUserWithPermissions(['leads.view', 'leads.create']);
        PipelineStage::withoutGlobalScopes()->create([
            'organization_id' => $user->organization_id,
            'name' => 'New Inquiry',
            'slug' => 'new-inquiry',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/v1/leads', [
            'first_name' => 'Aisha',
            'last_name' => 'Khan',
            'phone' => '+15550001111',
            'email' => 'aisha@example.com',
            'priority' => 'high',
        ], $this->authHeaders($user));

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.student.first_name', 'Aisha');
    }

    public function test_user_can_update_lead(): void
    {
        $user = $this->createUserWithPermissions(['leads.view', 'leads.create', 'leads.update']);
        PipelineStage::withoutGlobalScopes()->create([
            'organization_id' => $user->organization_id,
            'name' => 'New Inquiry',
            'slug' => 'new-inquiry',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $create = $this->postJson('/api/v1/leads', [
            'first_name' => 'Omar',
            'phone' => '+15550002222',
            'priority' => 'medium',
        ], $this->authHeaders($user))->json('data');

        $this->putJson('/api/v1/leads/'.$create['id'], [
            'notes' => 'Called family',
            'priority' => 'high',
        ], $this->authHeaders($user))
            ->assertOk()
            ->assertJsonPath('data.notes', 'Called family');
    }

    public function test_organization_isolation_hides_other_tenant_leads(): void
    {
        $orgA = Organization::factory()->create();
        $orgB = Organization::factory()->create();
        $userA = $this->createUserWithPermissions(['leads.view', 'leads.create'], $orgA);
        $userB = $this->createUserWithPermissions(['leads.view'], $orgB);

        PipelineStage::withoutGlobalScopes()->create([
            'organization_id' => $orgA->id,
            'name' => 'New Inquiry',
            'slug' => 'new-inquiry',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->postJson('/api/v1/leads', [
            'first_name' => 'Hidden',
            'phone' => '+15550003333',
        ], $this->authHeaders($userA))->assertCreated();

        $response = $this->getJson('/api/v1/leads', $this->authHeaders($userB));
        $response->assertOk()->assertJsonPath('meta.total', 0);
    }

    public function test_permission_is_required_to_create_lead(): void
    {
        $user = $this->createUserWithPermissions(['leads.view']);

        $this->postJson('/api/v1/leads', [
            'first_name' => 'No',
            'phone' => '+15550004444',
        ], $this->authHeaders($user))->assertForbidden();
    }
}
