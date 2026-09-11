<?php

namespace Tests\Feature\Auth;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login(): void
    {
        $organization = Organization::factory()->create(['code' => 'DEMO']);
        $user = User::factory()->create([
            'organization_id' => $organization->id,
            'email' => 'admin@blueventure.test',
            'password' => 'password',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@blueventure.test',
            'password' => 'password',
            'organization_code' => 'DEMO',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.email', $user->email);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        $headers = $this->authHeaders($user);

        $this->postJson('/api/v1/auth/logout', [], $headers)
            ->assertOk()
            ->assertJsonPath('success', true);
    }

    public function test_login_does_not_fail_with_csrf_token_mismatch_from_frontend_origin(): void
    {
        $organization = Organization::factory()->create(['code' => 'BLUEVENTURE']);
        User::factory()->create([
            'organization_id' => $organization->id,
            'email' => 'admin@blueventure.test',
            'password' => 'password',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@blueventure.test',
            'password' => 'password',
            'organization_code' => 'BLUEVENTURE',
        ], [
            'Origin' => 'http://localhost:3000',
            'Referer' => 'http://localhost:3000/',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true);
    }
}
