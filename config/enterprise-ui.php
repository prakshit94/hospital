<?php

return [
    'app_name' => env('APP_NAME', 'AccessHub'),
    'workspace_name' => env('ENTERPRISE_UI_WORKSPACE', 'LifeCare Hospital'),
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
                [
                    'title' => 'Appointments',
                    'icon' => 'calendar',
                    'children' => [
                        ['title' => 'Book Appointment', 'url' => '#', 'pattern' => 'appointments/create*', 'icon' => 'calendar'],
                        ['title' => 'All Appointments', 'url' => '#', 'pattern' => 'appointments', 'icon' => 'clipboard-list'],
                        ['title' => 'Doctor Schedule', 'url' => '#', 'pattern' => 'schedule*', 'icon' => 'activity'],
                    ],
                ],
                [
                    'title' => 'Patients',
                    'icon' => 'users',
                    'children' => [
                        ['title' => 'Medical Records', 'url' => '#', 'pattern' => 'records*', 'icon' => 'file-medical'],
                    ],
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
            'label' => 'Medical Services',
            'items' => [
                [
                    'title' => 'Specialists',
                    'icon' => 'user-md',
                    'children' => [
                        ['title' => 'Doctors List', 'url' => '#', 'pattern' => 'doctors*', 'icon' => 'user-md'],
                        ['title' => 'Departments', 'url' => '#', 'pattern' => 'departments*', 'icon' => 'layers'],
                    ],
                ],
                [
                    'title' => 'OPD/IPD',
                    'icon' => 'stethoscope',
                    'children' => [
                        ['title' => 'Outpatient (OPD)', 'url' => '#', 'pattern' => 'opd*', 'icon' => 'stethoscope'],
                        ['title' => 'Inpatient (IPD)', 'url' => '#', 'pattern' => 'ipd*', 'icon' => 'bed'],
                        ['title' => 'Operation Theater', 'url' => '#', 'pattern' => 'ot*', 'icon' => 'activity'],
                    ],
                ],
                [
                    'title' => 'Pharmacy',
                    'icon' => 'pills',
                    'children' => [
                        ['title' => 'Medicines', 'url' => '#', 'pattern' => 'medicines*', 'icon' => 'pills'],
                        ['title' => 'Pharmacy Billing', 'url' => '#', 'pattern' => 'pharmacy/billing*', 'icon' => 'credit-card'],
                    ],
                ],
                [
                    'title' => 'Diagnostics',
                    'icon' => 'microscope',
                    'children' => [
                        ['title' => 'Laboratory', 'url' => '#', 'pattern' => 'lab*', 'icon' => 'microscope'],
                        ['title' => 'Radiology', 'url' => '#', 'pattern' => 'radiology*', 'icon' => 'activity'],
                    ],
                ],
            ],
        ],
        [
            'label' => 'Administration',
            'items' => [
                [
                    'title' => 'Accounts',
                    'icon' => 'credit-card',
                    'children' => [
                        ['title' => 'Patient Billing', 'url' => '#', 'pattern' => 'billing*', 'icon' => 'credit-card'],
                        ['title' => 'Expenses', 'url' => '#', 'pattern' => 'expenses*', 'icon' => 'file-text'],
                    ],
                ],
                [
                    'title' => 'Inventory',
                    'icon' => 'box',
                    'children' => [
                        ['title' => 'Stock Levels', 'url' => '#', 'pattern' => 'inventory*', 'icon' => 'box'],
                        ['title' => 'Suppliers', 'url' => '#', 'pattern' => 'suppliers*', 'icon' => 'users'],
                    ],
                ],
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
                        ['title' => 'Hospital Settings', 'url' => '#', 'pattern' => 'settings*', 'icon' => 'settings'],
                        ['title' => 'Activity Logs', 'route' => 'activity-logs.index', 'pattern' => 'activity-logs*', 'icon' => 'file-text', 'permission' => 'activities.view'],
                        ['title' => 'Reports', 'route' => 'reports.index', 'pattern' => 'reports*', 'icon' => 'file-text', 'permission' => 'reports.view'],
                    ],
                ],
            ],
        ],
    ],
];
