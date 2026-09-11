<?php

namespace Tests;

use App\Models\Organization;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function createUserWithPermissions(array $permissions = [], ?Organization $organization = null): User
    {
        $organization ??= Organization::factory()->create();

        $role = Role::withoutGlobalScopes()->create([
            'organization_id' => $organization->id,
            'name' => 'Tester',
            'slug' => 'tester-'.uniqid(),
        ]);

        $permissionIds = [];
        foreach ($permissions as $slug) {
            $permission = Permission::query()->firstOrCreate(
                ['slug' => $slug],
                ['name' => $slug, 'module' => explode('.', $slug)[0]]
            );
            $permissionIds[] = $permission->id;
        }

        $role->permissions()->sync($permissionIds);

        $user = User::withoutGlobalScopes()->create([
            'organization_id' => $organization->id,
            'role_id' => $role->id,
            'name' => 'Test User',
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password',
            'status' => 'active',
        ]);

        $user->roles()->sync([$role->id]);

        return $user->fresh();
    }

    protected function authHeaders(User $user): array
    {
        $this->app['auth']->forgetGuards();

        $token = $user->createToken('test')->plainTextToken;

        return ['Authorization' => 'Bearer '.$token];
    }
}
