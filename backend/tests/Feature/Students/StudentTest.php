<?php

namespace Tests\Feature\Students;

use App\Models\PipelineStage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_and_update_student(): void
    {
        $user = $this->createUserWithPermissions([
            'students.view',
            'students.create',
            'students.update',
        ]);

        PipelineStage::withoutGlobalScopes()->create([
            'organization_id' => $user->organization_id,
            'name' => 'New Inquiry',
            'slug' => 'new-inquiry',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $create = $this->postJson('/api/v1/students', [
            'first_name' => 'Sara',
            'last_name' => 'Ali',
            'phone' => '+15550005555',
            'email' => 'sara@example.com',
        ], $this->authHeaders($user));

        $create->assertCreated()->assertJsonPath('data.first_name', 'Sara');
        $id = $create->json('data.id');

        $this->putJson('/api/v1/students/'.$id, [
            'city' => 'Dubai',
        ], $this->authHeaders($user))
            ->assertOk()
            ->assertJsonPath('data.city', 'Dubai');
    }
}
