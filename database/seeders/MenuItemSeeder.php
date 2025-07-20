<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            MenuItem::query()->delete();

            $icons = [
                'dashboard' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" /></svg>',

                'sales' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>',

                'crm' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',

                'pos' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>',

                'purchasing' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',

                'inventory' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>',

                'manufacturing' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>',

                'accounting' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>',

                'hr' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h4a2 2 0 012 2v1m-4 0h4m-4 0a2 2 0 100 4 2 2 0 000-4z" /></svg>',

                'projects' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>',

                'helpdesk' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" /></svg>',

                'settings' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>',

                'masterdata' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7l8 4 8-4M12 15v4" /></svg>',
            ];

            $menus = [
                // == MENU UTAMA ==
                [
                    'group' => 'Main', 'order' => 1, 'name' => 'Dashboard', 'route' => 'home',
                    'permission_name' => 'manage-acl', // Dashboard biasanya bisa diakses semua orang
                    'icon_svg' => $icons['dashboard'],
                ],

                // == GROUP: SALES & MARKETING ==
                [
                    'group' => 'Sales & Marketing', 'order' => 1, 'name' => 'Sales', 'route' => 'sales.orders.index',
                    'permission_name' => 'manage-sales', // Menggunakan izin utama modul
                    'icon_svg' => $icons['sales'],
                ],
                [
                    'group' => 'Sales & Marketing', 'order' => 2, 'name' => 'CRM', 'route' => 'crm.index',
                    'permission_name' => 'manage-crm', 'icon_svg' => $icons['crm'],
                ],
                [
                    'group' => 'Sales & Marketing', 'order' => 3, 'name' => 'Point of Sales', 'route' => 'pointofsales.index',
                    'permission_name' => 'manage-pos', 'icon_svg' => $icons['pos'],
                ],

                // == GROUP: OPERATIONS ==
                [
                    'group' => 'Operations', 'order' => 1, 'name' => 'Purchasing', 'route' => 'purchasing.purchase-orders.index',
                    'permission_name' => 'manage-purchasing',
                    'icon_svg' => $icons['purchasing'],
                ],
                [
                    'group' => 'Operations', 'order' => 2, 'name' => 'Inventory', 'route' => '#',
                    'permission_name' => 'manage-inventory',
                    'icon_svg' => $icons['inventory'],
                    'submenu' => [
                        ['name' => 'Products', 'route' => 'inventory.products.index', 'permission_name' => 'manage-inventory', 'order' => 1],
                        ['name' => 'Product Categories', 'route' => 'inventory.product-categories.index', 'permission_name' => 'manage-inventory', 'order' => 2],
                        ['name' => 'Warehouses', 'route' => 'warehouse.index', 'permission_name' => 'manage-warehouse', 'order' => 3],
                    ]
                ],
                [
                    'group' => 'Operations', 'order' => 3, 'name' => 'Manufacturing', 'route' => 'manufacturing.index',
                    'permission_name' => 'manage-manufacturing', 'icon_svg' => $icons['manufacturing'],
                ],

                // == GROUP: FINANCE & HR ==
                [
                    'group' => 'Finance & HR', 'order' => 1, 'name' => 'Accounting', 'route' => '#',
                    'permission_name' => 'manage-accounting',
                    'icon_svg' => $icons['accounting'],
                    'submenu' => [
                        ['name' => 'Financial Reports', 'route' => 'reports.index', 'permission_name' => 'manage-accounting', 'order' => 1],
                        ['name' => 'Chart of Accounts', 'route' => 'coas.index', 'permission_name' => 'manage-accounting', 'order' => 2],
                        ['name' => 'Journal Entries', 'route' => 'journals.index', 'permission_name' => 'manage-accounting', 'order' => 3],
                    ]
                ],
                [
                    'group' => 'Finance & HR', 'order' => 2, 'name' => 'Human Resource', 'route' => '#',
                    'permission_name' => 'manage-hr',
                    'icon_svg' => $icons['hr'],
                    'submenu' => [
                        ['name' => 'Employees', 'route' => 'humanresource.employees.index', 'permission_name' => 'manage-hr', 'order' => 1],
                        ['name' => 'Payrolls', 'route' => 'humanresource.payrolls.index', 'permission_name' => 'manage-hr', 'order' => 2],
                    ]
                ],

                // == GROUP: ADMINISTRATION ==
                [
                    'group' => 'Administration', 'order' => 1, 'name' => 'Master Data', 'route' => '#',
                    'permission_name' => 'manage-acl', // Hanya admin yang boleh kelola master data
                    'icon_svg' => $icons['masterdata'],
                    'submenu' => [
                        ['name' => 'Customers', 'route' => 'master-data.customer.index', 'permission_name' => 'manage-acl', 'order' => 1],
                        ['name' => 'Suppliers', 'route' => 'master-data.supplier.index', 'permission_name' => 'manage-acl', 'order' => 2],
                    ]
                ],
                [
                    'group' => 'Administration', 'order' => 2, 'name' => 'Projects', 'route' => 'projectmanagement.index',
                    'permission_name' => 'manage-projects', 'icon_svg' => $icons['projects'],
                ],
                [
                    'group' => 'Administration', 'order' => 3, 'name' => 'Settings', 'route' => '#',
                    'permission_name' => 'manage-acl',
                    'icon_svg' => $icons['settings'],
                    'submenu' => [
                        ['name' => 'Users', 'route' => 'admin.users.index', 'permission_name' => 'manage-acl', 'order' => 1],
                        ['name' => 'Roles', 'route' => 'admin.roles.index', 'permission_name' => 'manage-acl', 'order' => 2],
                        ['name' => 'Menu Management', 'route' => 'admin.menu.index', 'permission_name' => 'manage-acl', 'order' => 3],
                        ['name' => 'Accounting Settings', 'route' => 'accounting.settings.index', 'permission_name' => 'manage-accounting', 'order' => 4],
                    ]
                ],
            ];

            foreach ($menus as $menuData) {
                $submenuData = $menuData['submenu'] ?? [];
                unset($menuData['submenu']);
                $parent = MenuItem::create($menuData);

                if (!empty($submenuData)) {
                    foreach ($submenuData as $subItem) {
                        $subItem['parent_id'] = $parent->id;
                        $subItem['group'] = $parent->group;
                        $subItem['icon_svg'] = $subItem['icon_svg'] ?? null;
                        MenuItem::create($subItem);
                    }
                }
            }
        });
    }
}
