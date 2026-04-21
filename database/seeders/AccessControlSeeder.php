<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AccessControlSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ ALL PERMISSIONS (FIXED + COMPLETE)
        $permissions = collect([

            // Dashboard
            ['name' => 'View dashboard', 'slug' => 'dashboard.view', 'group_name' => 'dashboard'],

            // Users
            ['name' => 'View users', 'slug' => 'users.view', 'group_name' => 'users'],
            ['name' => 'Create users', 'slug' => 'users.create', 'group_name' => 'users'],
            ['name' => 'Update users', 'slug' => 'users.update', 'group_name' => 'users'],
            ['name' => 'Delete users', 'slug' => 'users.delete', 'group_name' => 'users'],

            // Companies
            ['name' => 'View companies', 'slug' => 'companies.view', 'group_name' => 'companies'],
            ['name' => 'Create companies', 'slug' => 'companies.create', 'group_name' => 'companies'],
            ['name' => 'Update companies', 'slug' => 'companies.update', 'group_name' => 'companies'],
            ['name' => 'Delete companies', 'slug' => 'companies.delete', 'group_name' => 'companies'],

            // Roles
            ['name' => 'View roles', 'slug' => 'roles.view', 'group_name' => 'roles'],
            ['name' => 'Create roles', 'slug' => 'roles.create', 'group_name' => 'roles'],
            ['name' => 'Update roles', 'slug' => 'roles.update', 'group_name' => 'roles'],
            ['name' => 'Delete roles', 'slug' => 'roles.delete', 'group_name' => 'roles'],

            // Permissions
            ['name' => 'View permissions', 'slug' => 'permissions.view', 'group_name' => 'permissions'],
            ['name' => 'Create permissions', 'slug' => 'permissions.create', 'group_name' => 'permissions'],
            ['name' => 'Update permissions', 'slug' => 'permissions.update', 'group_name' => 'permissions'],
            ['name' => 'Delete permissions', 'slug' => 'permissions.delete', 'group_name' => 'permissions'],

            // Activities
            ['name' => 'View activities', 'slug' => 'activities.view', 'group_name' => 'activities'],

            // Reports
            ['name' => 'View reports', 'slug' => 'reports.view', 'group_name' => 'reports'],
            ['name' => 'Export reports', 'slug' => 'reports.export', 'group_name' => 'reports'],

            // Health Records
            ['name' => 'View health records', 'slug' => 'health_records.view', 'group_name' => 'health_records'],
            ['name' => 'Create health records', 'slug' => 'health_records.create', 'group_name' => 'health_records'],
            ['name' => 'Update health records', 'slug' => 'health_records.update', 'group_name' => 'health_records'],
            ['name' => 'Delete health records', 'slug' => 'health_records.delete', 'group_name' => 'health_records'],

        ])->map(fn ($permission) =>
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                $permission + ['description' => $permission['name']]
            )
        );

        // ✅ ROLES
        $roles = [

            'super-admin' => [
                'name' => 'Super Admin',
                'description' => 'Full system access.',
                'permissions' => $permissions->pluck('id')->all(),
                'is_system' => true,
            ],

            'admin' => [
                'name' => 'Admin',
                'description' => 'Operational administrator.',
                'permissions' => $permissions->whereIn('slug', [
                    'dashboard.view',

                    'users.view',
                    'users.create',
                    'users.update',

                    'companies.view',
                    'companies.create',
                    'companies.update',

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
                'description' => 'Read-only access.',
                'permissions' => $permissions->whereIn('slug', [
                    'dashboard.view',
                    'users.view',
                    'companies.view',
                    'roles.view',
                    'permissions.view',
                    'activities.view',
                    'reports.view',
                ])->pluck('id')->all(),
                'is_system' => true,
            ],
        ];

        foreach ($roles as $slug => $roleData) {
            $role = Role::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $roleData['name'],
                    'description' => $roleData['description'],
                    'is_system' => $roleData['is_system'],
                ]
            );

            $role->permissions()->sync($roleData['permissions']);
        }

        // ✅ ADMIN USER (FIXED PASSWORD)
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'), // ✅ FIXED
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        $admin->roles()->sync([
            Role::where('slug', 'super-admin')->value('id'),
        ]);
    }
}