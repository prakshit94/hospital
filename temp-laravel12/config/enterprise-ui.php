<?php

return [
    'app_name' => env('APP_NAME', 'AccessHub'),
    'workspace_name' => env('ENTERPRISE_UI_WORKSPACE', 'Security Console'),
    'sidebar' => [
        [
            'label' => 'Overview',
            'items' => [
                ['title' => 'Dashboard', 'route' => 'dashboard', 'pattern' => 'dashboard*', 'icon' => 'layout-dashboard', 'permission' => 'dashboard.view'],
                ['title' => 'Activity Logs', 'route' => 'activity-logs.index', 'pattern' => 'activity-logs*', 'icon' => 'file-text', 'permission' => 'activities.view'],
            ],
        ],
        [
            'label' => 'Access Control',
            'items' => [
                ['title' => 'Users', 'route' => 'users.index', 'pattern' => 'users*', 'icon' => 'users', 'permission' => 'users.view'],
                ['title' => 'Roles', 'route' => 'roles.index', 'pattern' => 'roles*', 'icon' => 'shield', 'permission' => 'roles.view'],
                ['title' => 'Permissions', 'route' => 'permissions.index', 'pattern' => 'permissions*', 'icon' => 'settings', 'permission' => 'permissions.view'],
            ],
        ],
    ],
];
