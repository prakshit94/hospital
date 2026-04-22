<?php

return [
    'app_name' => env('APP_NAME', 'AccessHub'),
    'workspace_name' => env('ENTERPRISE_UI_WORKSPACE', 'Divit Hospital'),
    'sidebar' => [
        [
            'label' => 'Main',
            'items' => [
                [
                    'title' => 'Dashboard',
                    'icon' => 'layout-dashboard',
                    'route' => 'dashboard',
                    'pattern' => 'dashboard*',
                    'permission' => 'dashboard.view'
                ],
            ],
        ],
        [
            'label' => 'Occupational Health',
            'items' => [
                [
                    'title' => 'Health Records',
                    'icon' => 'file-medical',
                    'route' => 'health-records.index',
                    'pattern' => 'health-records*',
                    'permission' => 'health_records.view'
                ],
                [
                    'title' => 'Companies',
                    'icon' => 'building',
                    'route' => 'companies.index',
                    'pattern' => 'companies*',
                    'permission' => 'health_records.view'
                ],
            ],
        ],
        [
            'label' => 'Administration',
            'items' => [
                [
                    'title' => 'Access Control',
                    'icon' => 'shield',
                    'children' => [
                        ['title' => 'Staff/Users', 'route' => 'users.index', 'pattern' => 'users*', 'icon' => 'users', 'permission' => 'users.view'],
                        ['title' => 'Roles', 'route' => 'roles.index', 'pattern' => 'roles*', 'icon' => 'shield', 'permission' => 'roles.view'],
                        ['title' => 'Permissions', 'route' => 'permissions.index', 'pattern' => 'permissions*', 'icon' => 'tool', 'permission' => 'permissions.view'],
                    ],
                ],
                [
                    'title' => 'System',
                    'icon' => 'settings',
                    'children' => [
                        ['title' => 'Reports', 'route' => 'reports.index', 'pattern' => 'reports*', 'icon' => 'file-text', 'permission' => 'reports.view'],
                    ],
                ],
            ],
        ],
        [
            'label' => 'Security',
            'items' => [
                [
                    'title' => 'Activity Logs',
                    'icon' => 'activity',
                    'route' => 'activity-logs.index',
                    'pattern' => 'activity-logs*',
                    'permission' => 'activities.view'
                ],
                [
                    'title' => 'My Devices',
                    'icon' => 'smartphone',
                    'route' => 'devices.index',
                    'pattern' => 'devices*',
                ],
            ],
        ],
    ],
];
