<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'View leads', 'slug' => 'leads.view', 'module' => 'leads'],
            ['name' => 'Create leads', 'slug' => 'leads.create', 'module' => 'leads'],
            ['name' => 'Update leads', 'slug' => 'leads.update', 'module' => 'leads'],
            ['name' => 'Delete leads', 'slug' => 'leads.delete', 'module' => 'leads'],
            ['name' => 'Assign leads', 'slug' => 'leads.assign', 'module' => 'leads'],
            ['name' => 'View students', 'slug' => 'students.view', 'module' => 'students'],
            ['name' => 'Create students', 'slug' => 'students.create', 'module' => 'students'],
            ['name' => 'Update students', 'slug' => 'students.update', 'module' => 'students'],
            ['name' => 'Delete students', 'slug' => 'students.delete', 'module' => 'students'],
            ['name' => 'View follow-ups', 'slug' => 'followups.view', 'module' => 'followups'],
            ['name' => 'Create follow-ups', 'slug' => 'followups.create', 'module' => 'followups'],
            ['name' => 'Update follow-ups', 'slug' => 'followups.update', 'module' => 'followups'],
            ['name' => 'View tasks', 'slug' => 'tasks.view', 'module' => 'tasks'],
            ['name' => 'Create tasks', 'slug' => 'tasks.create', 'module' => 'tasks'],
            ['name' => 'Update tasks', 'slug' => 'tasks.update', 'module' => 'tasks'],
            ['name' => 'View reports', 'slug' => 'reports.view', 'module' => 'reports'],
            ['name' => 'Manage users', 'slug' => 'users.manage', 'module' => 'users'],
            ['name' => 'Manage settings', 'slug' => 'settings.manage', 'module' => 'settings'],
            ['name' => 'View documents', 'slug' => 'documents.view', 'module' => 'documents'],
            ['name' => 'Create documents', 'slug' => 'documents.create', 'module' => 'documents'],
            ['name' => 'Delete documents', 'slug' => 'documents.delete', 'module' => 'documents'],
            ['name' => 'View communications', 'slug' => 'communications.view', 'module' => 'communications'],
            ['name' => 'Create communications', 'slug' => 'communications.create', 'module' => 'communications'],
        ];

        foreach ($permissions as $permission) {
            Permission::query()->updateOrCreate(['slug' => $permission['slug']], $permission);
        }
    }
}
