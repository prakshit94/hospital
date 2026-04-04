<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AccessControlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = collect([
            ['name' => 'View dashboard', 'slug' => 'dashboard.view', 'group_name' => 'dashboard', 'description' => 'Open the dashboard and summary cards.'],
            ['name' => 'View users', 'slug' => 'users.view', 'group_name' => 'users', 'description' => 'Browse user records.'],
            ['name' => 'Create users', 'slug' => 'users.create', 'group_name' => 'users', 'description' => 'Create new user accounts.'],
            ['name' => 'Update users', 'slug' => 'users.update', 'group_name' => 'users', 'description' => 'Edit existing user accounts.'],
            ['name' => 'Delete users', 'slug' => 'users.delete', 'group_name' => 'users', 'description' => 'Delete user accounts.'],
            ['name' => 'View roles', 'slug' => 'roles.view', 'group_name' => 'roles', 'description' => 'Browse roles.'],
            ['name' => 'Create roles', 'slug' => 'roles.create', 'group_name' => 'roles', 'description' => 'Create new roles.'],
            ['name' => 'Update roles', 'slug' => 'roles.update', 'group_name' => 'roles', 'description' => 'Edit roles.'],
            ['name' => 'Delete roles', 'slug' => 'roles.delete', 'group_name' => 'roles', 'description' => 'Delete roles.'],
            ['name' => 'View permissions', 'slug' => 'permissions.view', 'group_name' => 'permissions', 'description' => 'Browse permissions.'],
            ['name' => 'Create permissions', 'slug' => 'permissions.create', 'group_name' => 'permissions', 'description' => 'Create new permissions.'],
            ['name' => 'Update permissions', 'slug' => 'permissions.update', 'group_name' => 'permissions', 'description' => 'Edit permissions.'],
            ['name' => 'Delete permissions', 'slug' => 'permissions.delete', 'group_name' => 'permissions', 'description' => 'Delete permissions.'],
            ['name' => 'View activities', 'slug' => 'activities.view', 'group_name' => 'activities', 'description' => 'Access the audit log.'],
            ['name' => 'View reports', 'slug' => 'reports.view', 'group_name' => 'reports', 'description' => 'Access reporting dashboards.'],
            ['name' => 'Export reports', 'slug' => 'reports.export', 'group_name' => 'reports', 'description' => 'Export CSV reports.'],
        ])->map(fn (array $permission) => Permission::query()->updateOrCreate(
            ['slug' => $permission['slug']],
            $permission,
        ));

        $roles = [
            'super-admin' => [
                'name' => 'Super Admin',
                'description' => 'Full system access.',
                'permissions' => $permissions->pluck('id')->all(),
                'is_system' => true,
            ],
            'admin' => [
                'name' => 'Admin',
                'description' => 'Operational administrator with user and activity access.',
                'permissions' => $permissions->whereIn('slug', [
                    'dashboard.view',
                    'users.view',
                    'users.create',
                    'users.update',
                    'roles.view',
                    'roles.create',
                    'roles.update',
                    'permissions.view',
                    'activities.view',
                    'reports.view',
                    'reports.export',
                ])->pluck('id')->all(),
                'is_system' => true,
            ],
            'viewer' => [
                'name' => 'Viewer',
                'description' => 'Read-only dashboard and audit access.',
                'permissions' => $permissions->whereIn('slug', [
                    'dashboard.view',
                    'users.view',
                    'roles.view',
                    'permissions.view',
                    'activities.view',
                    'reports.view',
                ])->pluck('id')->all(),
                'is_system' => true,
            ],
        ];

        foreach ($roles as $slug => $roleData) {
            $role = Role::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $roleData['name'],
                    'description' => $roleData['description'],
                    'is_system' => $roleData['is_system'],
                ],
            );

            $role->permissions()->sync($roleData['permissions']);
        }

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Administrator',
                'password' => 'password',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
        );

        $admin->roles()->sync([
            Role::where('slug', 'super-admin')->value('id'),
        ]);
    }
}
