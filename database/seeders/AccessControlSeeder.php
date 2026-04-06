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

            // ✅ Customers (FIXED)
            ['name' => 'View customers', 'slug' => 'customers.view', 'group_name' => 'customers'],
            ['name' => 'Create customers', 'slug' => 'customers.create', 'group_name' => 'customers'],
            ['name' => 'Update customers', 'slug' => 'customers.update', 'group_name' => 'customers'],
            ['name' => 'Delete customers', 'slug' => 'customers.delete', 'group_name' => 'customers'],

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

                    'customers.view',
                    'customers.create',
                    'customers.update',

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
                    'customers.view',
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