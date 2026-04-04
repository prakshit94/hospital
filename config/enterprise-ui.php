<?php

return [
    'app_name' => env('APP_NAME', 'AccessHub'),
    'workspace_name' => env('ENTERPRISE_UI_WORKSPACE', 'Security Console'),
    'sidebar' => [
        [
            'label' => 'General',
            'items' => [
                [
                    'title' => 'Overview',
                    'icon' => 'layout-dashboard',
                    'children' => [
                        ['title' => 'Dashboard', 'route' => 'dashboard', 'pattern' => 'dashboard*', 'icon' => 'layout-dashboard', 'permission' => 'dashboard.view'],
                        ['title' => 'Reports', 'route' => 'reports.index', 'pattern' => 'reports*', 'icon' => 'file-text', 'permission' => 'reports.view'],
                        ['title' => 'Activity Logs', 'route' => 'activity-logs.index', 'pattern' => 'activity-logs*', 'icon' => 'file-text', 'permission' => 'activities.view'],
                    ],
                ],
            ],
        ],
        [
            'label' => 'E-Commerce',
            'items' => [
                [
                    'title' => 'Sales',
                    'icon' => 'shopping-cart',
                    'children' => [
                        ['title' => 'Orders', 'url' => '#', 'pattern' => 'orders*', 'icon' => 'shopping-cart', 'permission' => 'orders.view'],
                        ['title' => 'Invoices', 'url' => '#', 'pattern' => 'invoices*', 'icon' => 'file-text', 'permission' => 'invoices.view'],
                        ['title' => 'Shipments', 'url' => '#', 'pattern' => 'shipments*', 'icon' => 'truck', 'permission' => 'shipments.view'],
                        ['title' => 'Returns', 'url' => '#', 'pattern' => 'returns*', 'icon' => 'shopping-cart', 'permission' => 'returns.view'],
                    ],
                ],
                [
                    'title' => 'Catalog',
                    'icon' => 'package',
                    'children' => [
                        ['title' => 'Products', 'url' => '#', 'pattern' => 'products*', 'icon' => 'package', 'permission' => 'products.view'],
                        ['title' => 'Categories', 'url' => '#', 'pattern' => 'categories*', 'icon' => 'layers', 'permission' => 'categories.view'],
                        ['title' => 'Attributes', 'url' => '#', 'pattern' => 'attributes*', 'icon' => 'tool', 'permission' => 'attributes.view'],
                    ],
                ],
                [
                    'title' => 'Customers',
                    'icon' => 'users',
                    'children' => [
                        ['title' => 'All Customers', 'url' => '#', 'pattern' => 'customers*', 'icon' => 'users', 'permission' => 'customers.view'],
                        ['title' => 'Customer Groups', 'url' => '#', 'pattern' => 'customer-groups*', 'icon' => 'users', 'permission' => 'customer_groups.view'],
                    ],
                ],
                [
                    'title' => 'Marketing',
                    'icon' => 'tag',
                    'children' => [
                        ['title' => 'Promotions', 'url' => '#', 'pattern' => 'promotions*', 'icon' => 'tag', 'permission' => 'promotions.view'],
                        ['title' => 'Coupons', 'url' => '#', 'pattern' => 'coupons*', 'icon' => 'tag', 'permission' => 'coupons.view'],
                    ],
                ],
                [
                    'title' => 'Inventory',
                    'icon' => 'box',
                    'children' => [
                        ['title' => 'Stock Levels', 'url' => '#', 'pattern' => 'inventory*', 'icon' => 'box', 'permission' => 'inventory.view'],
                        ['title' => 'Warehouses', 'url' => '#', 'pattern' => 'warehouses*', 'icon' => 'layers', 'permission' => 'warehouses.view'],
                        ['title' => 'Suppliers', 'url' => '#', 'pattern' => 'suppliers*', 'icon' => 'users', 'permission' => 'suppliers.view'],
                    ],
                ],
            ],
        ],
        [
            'label' => 'Administration',
            'items' => [
                [
                    'title' => 'Content',
                    'icon' => 'layers',
                    'children' => [
                        ['title' => 'Pages', 'url' => '#', 'pattern' => 'pages*', 'icon' => 'file-text', 'permission' => 'pages.view'],
                        ['title' => 'Media Library', 'url' => '#', 'pattern' => 'media*', 'icon' => 'monitor', 'permission' => 'media.view'],
                    ],
                ],
                [
                    'title' => 'System',
                    'icon' => 'tool',
                    'children' => [
                        ['title' => 'Store Settings', 'url' => '#', 'pattern' => 'settings*', 'icon' => 'tool', 'permission' => 'settings.view'],
                        ['title' => 'Payments', 'url' => '#', 'pattern' => 'payments*', 'icon' => 'credit-card', 'permission' => 'payments.view'],
                        ['title' => 'Shipping', 'url' => '#', 'pattern' => 'shipping*', 'icon' => 'truck', 'permission' => 'shipping.view'],
                        ['title' => 'Taxes', 'url' => '#', 'pattern' => 'taxes*', 'icon' => 'percent', 'permission' => 'taxes.view'],
                    ],
                ],
                [
                    'title' => 'Access Control',
                    'icon' => 'shield',
                    'children' => [
                        ['title' => 'Users', 'route' => 'users.index', 'pattern' => 'users*', 'icon' => 'users', 'permission' => 'users.view'],
                        ['title' => 'Roles', 'route' => 'roles.index', 'pattern' => 'roles*', 'icon' => 'shield', 'permission' => 'roles.view'],
                        ['title' => 'Permissions', 'route' => 'permissions.index', 'pattern' => 'permissions*', 'icon' => 'tool', 'permission' => 'permissions.view'],
                    ],
                ],
            ],
        ],
    ],
];
