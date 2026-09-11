<?php

namespace Tests\Feature\Tasks;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_and_complete_task(): void
    {
        $user = $this->createUserWithPermissions(['tasks.view', 'tasks.create', 'tasks.update']);

        $task = $this->postJson('/api/v1/tasks', [
            'title' => 'Send brochure',
            'priority' => 'high',
        ], $this->authHeaders($user));

        $task->assertCreated();

        $this->patchJson('/api/v1/tasks/'.$task->json('data.id').'/complete', [], $this->authHeaders($user))
            ->assertOk()
            ->assertJsonPath('data.status', 'completed');
    }
}
