<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $organization = Organization::first();
        if (!$organization) return;

        // 1. System Superadmin
        $systemSuperadminRole = Role::withoutGlobalScopes()->firstOrCreate([
            'organization_id' => $organization->id,
            'slug' => 'system_superadmin',
        ], [
            'name' => 'System Superadmin (Global SaaS Owner)',
        ]);

        // 2. Company Superadmin / Tenant Owner
        $companySuperadminRole = Role::withoutGlobalScopes()->firstOrCreate([
            'organization_id' => $organization->id,
            'slug' => 'company_superadmin',
        ], [
            'name' => 'Company Superadmin / Tenant Owner',
        ]);

        // 3. Company Admin / Operations Manager
        $companyAdminRole = Role::withoutGlobalScopes()->firstOrCreate([
            'organization_id' => $organization->id,
            'slug' => 'company_admin',
        ], [
            'name' => 'Company Admin (Operations Manager)',
        ]);

        // 4. Senior Counselor / Team Lead
        $seniorCounselorRole = Role::withoutGlobalScopes()->firstOrCreate([
            'organization_id' => $organization->id,
            'slug' => 'senior_counselor',
        ], [
            'name' => 'Senior Counselor / Team Lead',
        ]);

        // 5. Junior Counselor / Support Executive
        $juniorCounselorRole = Role::withoutGlobalScopes()->firstOrCreate([
            'organization_id' => $organization->id,
            'slug' => 'junior_counselor',
        ], [
            'name' => 'Junior Counselor / Support Executive',
        ]);

        // Assign all permissions to System Superadmin & Company Superadmin
        $allPermissionIds = Permission::pluck('id');
        $systemSuperadminRole->permissions()->sync($allPermissionIds);
        $companySuperadminRole->permissions()->sync($allPermissionIds);
        $companyAdminRole->permissions()->sync($allPermissionIds);

        // Senior Counselor Permissions
        $seniorPermissions = Permission::whereIn('slug', [
            'leads.view', 'leads.create', 'leads.update', 'leads.assign',
            'students.view', 'students.create', 'students.update',
            'followups.view', 'followups.create', 'followups.update',
            'tasks.view', 'tasks.create', 'tasks.update',
            'documents.view', 'documents.create',
            'communications.view', 'communications.create',
            'reports.view',
        ])->pluck('id');
        $seniorCounselorRole->permissions()->sync($seniorPermissions);

        // Junior Counselor Permissions
        $juniorPermissions = Permission::whereIn('slug', [
            'leads.view', 'leads.update',
            'students.view',
            'followups.view', 'followups.update',
            'tasks.view', 'tasks.update',
            'documents.view',
            'communications.view', 'communications.create',
        ])->pluck('id');
        $juniorCounselorRole->permissions()->sync($juniorPermissions);

        // Create Demo Users for each role
        $usersData = [
            ['name' => 'SaaS Global Owner', 'email' => 'saas.owner@blueventure.test', 'role' => $systemSuperadminRole],
            ['name' => 'Tenant Billing Owner', 'email' => 'owner@blueventure.test', 'role' => $companySuperadminRole],
            ['name' => 'Operations Manager', 'email' => 'ops.admin@blueventure.test', 'role' => $companyAdminRole],
            ['name' => 'Senior Lead Counselor', 'email' => 'senior.counselor@blueventure.test', 'role' => $seniorCounselorRole],
            ['name' => 'Junior Support Exec', 'email' => 'junior.counselor@blueventure.test', 'role' => $juniorCounselorRole],
        ];

        foreach ($usersData as $u) {
            $user = User::withoutGlobalScopes()->firstOrCreate([
                'email' => $u['email'],
            ], [
                'organization_id' => $organization->id,
                'role_id' => $u['role']->id,
                'name' => $u['name'],
                'phone' => '+1999' . rand(100000, 999999),
                'password' => Hash::make('password'),
                'status' => 'active',
            ]);

            $user->roles()->sync([$u['role']->id]);
        }
    }
}
