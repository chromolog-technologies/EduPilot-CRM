<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\LeadSource;
use App\Models\Organization;
use App\Models\Permission;
use App\Models\PipelineStage;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoOrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $organization = Organization::query()->create([
            'name' => 'BlueVenture',
            'code' => 'BLUEVENTURE',
            'email' => 'hello@blueventure.test',
            'phone' => '+10000000000',
            'address' => 'Demo address',
            'status' => 'active',
        ]);

        $branch = Branch::withoutGlobalScopes()->create([
            'organization_id' => $organization->id,
            'name' => 'Head Office',
            'code' => 'HO',
            'status' => 'active',
        ]);

        $adminRole = Role::withoutGlobalScopes()->create([
            'organization_id' => $organization->id,
            'name' => 'Administrator',
            'slug' => 'admin',
        ]);

        $counselorRole = Role::withoutGlobalScopes()->create([
            'organization_id' => $organization->id,
            'name' => 'Counselor',
            'slug' => 'counselor',
        ]);

        $adminRole->permissions()->sync(Permission::query()->pluck('id'));
        $counselorRole->permissions()->sync(
            Permission::query()->whereIn('slug', [
                'leads.view', 'leads.create', 'leads.update', 'leads.assign',
                'students.view', 'students.create', 'students.update',
                'followups.view', 'followups.create', 'followups.update',
                'tasks.view', 'tasks.create', 'tasks.update',
                'documents.view', 'documents.create',
                'communications.view', 'communications.create',
            ])->pluck('id')
        );

        $admin = User::withoutGlobalScopes()->create([
            'organization_id' => $organization->id,
            'branch_id' => $branch->id,
            'role_id' => $adminRole->id,
            'name' => 'Admin User',
            'email' => 'admin@blueventure.test',
            'phone' => '+10000000001',
            'password' => Hash::make('password'),
            'status' => 'active',
        ]);
        $admin->roles()->sync([$adminRole->id]);

        $counselor = User::withoutGlobalScopes()->create([
            'organization_id' => $organization->id,
            'branch_id' => $branch->id,
            'role_id' => $counselorRole->id,
            'name' => 'Priya Counselor',
            'email' => 'counselor@blueventure.test',
            'phone' => '+10000000002',
            'password' => Hash::make('password'),
            'status' => 'active',
        ]);
        $counselor->roles()->sync([$counselorRole->id]);

        $sources = [
            ['name' => 'Website', 'code' => 'website'],
            ['name' => 'Facebook', 'code' => 'facebook'],
            ['name' => 'Instagram', 'code' => 'instagram'],
            ['name' => 'WhatsApp', 'code' => 'whatsapp'],
            ['name' => 'Google', 'code' => 'google'],
            ['name' => 'Referral', 'code' => 'referral'],
            ['name' => 'Walk-in', 'code' => 'walk-in'],
            ['name' => 'Agent', 'code' => 'agent'],
            ['name' => 'Other', 'code' => 'other'],
        ];

        foreach ($sources as $source) {
            LeadSource::withoutGlobalScopes()->create([
                'organization_id' => $organization->id,
                'name' => $source['name'],
                'code' => $source['code'],
                'status' => 'active',
            ]);
        }

        $stages = [
            'New Inquiry',
            'First Outreach',
            'Follow-up',
            'Counseling',
            'Documents',
            'Applied',
            'Payment',
            'Enrolled',
            'Lost',
        ];

        foreach ($stages as $index => $name) {
            PipelineStage::withoutGlobalScopes()->create([
                'organization_id' => $organization->id,
                'name' => $name,
                'slug' => \Illuminate\Support\Str::slug($name),
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }
    }
}
