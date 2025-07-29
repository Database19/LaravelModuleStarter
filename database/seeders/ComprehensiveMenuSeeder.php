<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComprehensiveMenuSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // Clear existing menu items
            MenuItem::query()->delete();
            echo "🗑️  Cleared existing menu items\n";

            // Define SVG icons
            $icons = $this->getSvgIcons();

            // Define comprehensive menu structure
            $menus = $this->getMenuStructure($icons);

            // Create menu items
            $this->createMenuItems($menus);

            echo "✅ ComprehensiveMenuSeeder completed successfully!\n";
        });
    }

    private function getSvgIcons(): array
    {
        return [
            'dashboard' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>',

            'superadmin' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>',

            'masterdata' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7l8 4 8-4M12 15v4" /></svg>',

            'sales' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>',

            'crm' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',

            'purchasing' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',

            'inventory' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>',

            'manufacturing' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>',

            'accounting' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>',

            'hr' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" /></svg>',

            'projects' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>',

            'documents' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>',

            'warehouse' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>',

            'pos' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>',

            'quality' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',

            'helpdesk' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5z" /></svg>',

            'maintenance' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /></svg>',

            'reports' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>',

            'settings' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /></svg>',
        ];
    }

    private function getMenuStructure(array $icons): array
    {
        return [
            // == SUPER ADMIN GROUP ==
            [
                'group' => 'Super Admin', 'order' => 0, 'name' => 'Company Management', 'route' => '#',
                'permission_name' => 'super-admin-access',
                'icon_svg' => $icons['superadmin'],
                'submenu' => [
                    ['name' => 'All Companies', 'route' => 'superadmin.companies.index', 'permission_name' => 'manage-companies', 'order' => 1],
                    ['name' => 'Global Users', 'route' => 'superadmin.users.index', 'permission_name' => 'manage-global-users', 'order' => 2],
                    ['name' => 'System Monitor', 'route' => 'superadmin.monitor.index', 'permission_name' => 'system-monitor', 'order' => 3],
                    ['name' => 'Company Switcher', 'route' => 'superadmin.switch-company', 'permission_name' => 'super-admin-access', 'order' => 4],
                ]
            ],

            // == MAIN GROUP ==
            [
                'group' => 'Main', 'order' => 1, 'name' => 'Dashboard', 'route' => 'home',
                'permission_name' => 'manage-dashboard',
                'icon_svg' => $icons['dashboard'],
            ],

            // == MASTER DATA GROUP ==
            [
                'group' => 'Master Data', 'order' => 2, 'name' => 'Master Data', 'route' => '#',
                'permission_name' => 'manage-master-data',
                'icon_svg' => $icons['masterdata'],
                'submenu' => [
                    ['name' => 'Brands', 'route' => 'master-data.brands.index', 'permission_name' => 'manage-master-data', 'order' => 1],
                    ['name' => 'Units', 'route' => 'master-data.units.index', 'permission_name' => 'manage-master-data', 'order' => 2],
                    ['name' => 'Product Categories', 'route' => 'master-data.product-categories.index', 'permission_name' => 'manage-master-data', 'order' => 3],
                    ['name' => 'Products', 'route' => 'master-data.products.index', 'permission_name' => 'manage-master-data', 'order' => 4],
                    ['name' => 'Customers', 'route' => 'master-data.customers.index', 'permission_name' => 'manage-master-data', 'order' => 5],
                    ['name' => 'Suppliers', 'route' => 'master-data.suppliers.index', 'permission_name' => 'manage-master-data', 'order' => 6],
                    ['name' => 'Warehouses', 'route' => 'master-data.warehouses.index', 'permission_name' => 'manage-master-data', 'order' => 7],
                    ['name' => 'Departments', 'route' => 'master-data.departments.index', 'permission_name' => 'manage-master-data', 'order' => 8],
                    ['name' => 'Positions', 'route' => 'master-data.positions.index', 'permission_name' => 'manage-master-data', 'order' => 9],
                ]
            ],

            // == CRM GROUP ==
            [
                'group' => 'CRM', 'order' => 3, 'name' => 'CRM', 'route' => '#',
                'permission_name' => 'manage-crm',
                'icon_svg' => $icons['crm'],
                'submenu' => [
                    ['name' => 'Leads', 'route' => 'crm.leads.index', 'permission_name' => 'manage-crm', 'order' => 1],
                    ['name' => 'Opportunities', 'route' => 'crm.opportunities.index', 'permission_name' => 'manage-crm', 'order' => 2],
                    ['name' => 'Customer Communications', 'route' => 'crm.communications.index', 'permission_name' => 'manage-crm', 'order' => 3],
                    ['name' => 'Sales Pipeline', 'route' => 'crm.pipeline.index', 'permission_name' => 'manage-crm', 'order' => 4],
                ]
            ],

            // == SALES GROUP ==
            [
                'group' => 'Sales', 'order' => 4, 'name' => 'Sales', 'route' => '#',
                'permission_name' => 'manage-sales',
                'icon_svg' => $icons['sales'],
                'submenu' => [
                    ['name' => 'Sales Orders', 'route' => 'sales.orders.index', 'permission_name' => 'manage-sales', 'order' => 1],
                    ['name' => 'Quotations', 'route' => 'sales.quotations.index', 'permission_name' => 'manage-sales', 'order' => 2],
                    ['name' => 'Sales Returns', 'route' => 'sales.returns.index', 'permission_name' => 'manage-sales', 'order' => 3],
                    ['name' => 'Sales Reports', 'route' => 'sales.reports.index', 'permission_name' => 'manage-sales', 'order' => 4],
                ]
            ],

            // == PURCHASING GROUP ==
            [
                'group' => 'Purchasing', 'order' => 5, 'name' => 'Purchasing', 'route' => '#',
                'permission_name' => 'manage-purchasing',
                'icon_svg' => $icons['purchasing'],
                'submenu' => [
                    ['name' => 'Purchase Orders', 'route' => 'purchasing.orders.index', 'permission_name' => 'manage-purchasing', 'order' => 1],
                    ['name' => 'Purchase Requests', 'route' => 'purchasing.requests.index', 'permission_name' => 'manage-purchasing', 'order' => 2],
                    ['name' => 'Supplier Evaluations', 'route' => 'purchasing.evaluations.index', 'permission_name' => 'manage-purchasing', 'order' => 3],
                    ['name' => 'Purchase Reports', 'route' => 'purchasing.reports.index', 'permission_name' => 'manage-purchasing', 'order' => 4],
                ]
            ],

            // == INVENTORY GROUP ==
            [
                'group' => 'Inventory', 'order' => 6, 'name' => 'Inventory', 'route' => '#',
                'permission_name' => 'manage-inventory',
                'icon_svg' => $icons['inventory'],
                'submenu' => [
                    ['name' => 'Stock Management', 'route' => 'inventory.stock.index', 'permission_name' => 'manage-inventory', 'order' => 1],
                    ['name' => 'Stock Movements', 'route' => 'inventory.movements.index', 'permission_name' => 'manage-inventory', 'order' => 2],
                    ['name' => 'Stock Adjustments', 'route' => 'inventory.adjustments.index', 'permission_name' => 'manage-inventory', 'order' => 3],
                    ['name' => 'Stock Opname', 'route' => 'inventory.opname.index', 'permission_name' => 'manage-inventory', 'order' => 4],
                    ['name' => 'Low Stock Alert', 'route' => 'inventory.alerts.index', 'permission_name' => 'manage-inventory', 'order' => 5],
                ]
            ],

            // == WAREHOUSE GROUP ==
            [
                'group' => 'Warehouse', 'order' => 7, 'name' => 'Warehouse', 'route' => '#',
                'permission_name' => 'manage-warehouse',
                'icon_svg' => $icons['warehouse'],
                'submenu' => [
                    ['name' => 'Warehouse Management', 'route' => 'warehouse.index', 'permission_name' => 'manage-warehouse', 'order' => 1],
                    ['name' => 'Receiving', 'route' => 'warehouse.receiving.index', 'permission_name' => 'manage-warehouse', 'order' => 2],
                    ['name' => 'Picking & Packing', 'route' => 'warehouse.picking.index', 'permission_name' => 'manage-warehouse', 'order' => 3],
                    ['name' => 'Shipping', 'route' => 'warehouse.shipping.index', 'permission_name' => 'manage-warehouse', 'order' => 4],
                ]
            ],

            // == MANUFACTURING GROUP ==
            [
                'group' => 'Manufacturing', 'order' => 8, 'name' => 'Manufacturing', 'route' => '#',
                'permission_name' => 'manage-manufacturing',
                'icon_svg' => $icons['manufacturing'],
                'submenu' => [
                    ['name' => 'Production Orders', 'route' => 'manufacturing.orders.index', 'permission_name' => 'manage-manufacturing', 'order' => 1],
                    ['name' => 'Work Centers', 'route' => 'manufacturing.workcenters.index', 'permission_name' => 'manage-manufacturing', 'order' => 2],
                    ['name' => 'BOM (Bill of Materials)', 'route' => 'manufacturing.bom.index', 'permission_name' => 'manage-manufacturing', 'order' => 3],
                    ['name' => 'Production Planning', 'route' => 'manufacturing.planning.index', 'permission_name' => 'manage-manufacturing', 'order' => 4],
                ]
            ],

            // == QUALITY CONTROL GROUP ==
            [
                'group' => 'Quality', 'order' => 9, 'name' => 'Quality Control', 'route' => '#',
                'permission_name' => 'manage-quality-control',
                'icon_svg' => $icons['quality'],
                'submenu' => [
                    ['name' => 'Quality Inspections', 'route' => 'quality.inspections.index', 'permission_name' => 'manage-quality-control', 'order' => 1],
                    ['name' => 'Quality Control Plans', 'route' => 'quality.plans.index', 'permission_name' => 'manage-quality-control', 'order' => 2],
                    ['name' => 'Non-Conformance Reports', 'route' => 'quality.ncr.index', 'permission_name' => 'manage-quality-control', 'order' => 3],
                    ['name' => 'Quality Certificates', 'route' => 'quality.certificates.index', 'permission_name' => 'manage-quality-control', 'order' => 4],
                ]
            ],

            // == ACCOUNTING GROUP ==
            [
                'group' => 'Accounting', 'order' => 10, 'name' => 'Accounting', 'route' => '#',
                'permission_name' => 'manage-accounting',
                'icon_svg' => $icons['accounting'],
                'submenu' => [
                    ['name' => 'Chart of Accounts', 'route' => 'accounting.coa.index', 'permission_name' => 'manage-accounting', 'order' => 1],
                    ['name' => 'Journal Entries', 'route' => 'accounting.journal.index', 'permission_name' => 'manage-accounting', 'order' => 2],
                    ['name' => 'General Ledger', 'route' => 'accounting.ledger.index', 'permission_name' => 'manage-accounting', 'order' => 3],
                    ['name' => 'Trial Balance', 'route' => 'accounting.trial-balance.index', 'permission_name' => 'manage-accounting', 'order' => 4],
                    ['name' => 'Financial Statements', 'route' => 'accounting.statements.index', 'permission_name' => 'manage-accounting', 'order' => 5],
                    ['name' => 'Tax Management', 'route' => 'accounting.tax.index', 'permission_name' => 'manage-accounting', 'order' => 6],
                ]
            ],

            // == HR GROUP ==
            [
                'group' => 'Human Resources', 'order' => 11, 'name' => 'Human Resources', 'route' => '#',
                'permission_name' => 'manage-hr',
                'icon_svg' => $icons['hr'],
                'submenu' => [
                    ['name' => 'Employees', 'route' => 'hr.employees.index', 'permission_name' => 'manage-hr', 'order' => 1],
                    ['name' => 'Attendance', 'route' => 'hr.attendance.index', 'permission_name' => 'manage-hr', 'order' => 2],
                    ['name' => 'Payroll', 'route' => 'hr.payroll.index', 'permission_name' => 'manage-hr', 'order' => 3],
                    ['name' => 'Leave Management', 'route' => 'hr.leave.index', 'permission_name' => 'manage-hr', 'order' => 4],
                    ['name' => 'Performance Reviews', 'route' => 'hr.performance.index', 'permission_name' => 'manage-hr', 'order' => 5],
                    ['name' => 'Training', 'route' => 'hr.training.index', 'permission_name' => 'manage-hr', 'order' => 6],
                ]
            ],

            // == PROJECTS GROUP ==
            [
                'group' => 'Projects', 'order' => 12, 'name' => 'Project Management', 'route' => '#',
                'permission_name' => 'manage-projects',
                'icon_svg' => $icons['projects'],
                'submenu' => [
                    ['name' => 'Projects', 'route' => 'projects.index', 'permission_name' => 'manage-projects', 'order' => 1],
                    ['name' => 'Tasks', 'route' => 'projects.tasks.index', 'permission_name' => 'manage-projects', 'order' => 2],
                    ['name' => 'Time Tracking', 'route' => 'projects.timetracking.index', 'permission_name' => 'manage-projects', 'order' => 3],
                    ['name' => 'Project Reports', 'route' => 'projects.reports.index', 'permission_name' => 'manage-projects', 'order' => 4],
                ]
            ],

            // == DOCUMENTS GROUP ==
            [
                'group' => 'Documents', 'order' => 13, 'name' => 'Document Management', 'route' => '#',
                'permission_name' => 'manage-documents',
                'icon_svg' => $icons['documents'],
                'submenu' => [
                    ['name' => 'Document Library', 'route' => 'documents.library.index', 'permission_name' => 'manage-documents', 'order' => 1],
                    ['name' => 'Document Categories', 'route' => 'documents.categories.index', 'permission_name' => 'manage-documents', 'order' => 2],
                    ['name' => 'Document Workflow', 'route' => 'documents.workflow.index', 'permission_name' => 'manage-documents', 'order' => 3],
                    ['name' => 'Document Archive', 'route' => 'documents.archive.index', 'permission_name' => 'manage-documents', 'order' => 4],
                ]
            ],

            // == POS GROUP ==
            [
                'group' => 'Point of Sale', 'order' => 14, 'name' => 'Point of Sale', 'route' => '#',
                'permission_name' => 'manage-pos',
                'icon_svg' => $icons['pos'],
                'submenu' => [
                    ['name' => 'POS Terminal', 'route' => 'pos.terminal.index', 'permission_name' => 'manage-pos', 'order' => 1],
                    ['name' => 'Cash Register', 'route' => 'pos.register.index', 'permission_name' => 'manage-pos', 'order' => 2],
                    ['name' => 'POS Reports', 'route' => 'pos.reports.index', 'permission_name' => 'manage-pos', 'order' => 3],
                    ['name' => 'POS Settings', 'route' => 'pos.settings.index', 'permission_name' => 'manage-pos', 'order' => 4],
                ]
            ],

            // == HELPDESK GROUP ==
            [
                'group' => 'Helpdesk', 'order' => 15, 'name' => 'Helpdesk', 'route' => '#',
                'permission_name' => 'manage-helpdesk',
                'icon_svg' => $icons['helpdesk'],
                'submenu' => [
                    ['name' => 'Tickets', 'route' => 'helpdesk.tickets.index', 'permission_name' => 'manage-helpdesk', 'order' => 1],
                    ['name' => 'Knowledge Base', 'route' => 'helpdesk.kb.index', 'permission_name' => 'manage-helpdesk', 'order' => 2],
                    ['name' => 'FAQ Management', 'route' => 'helpdesk.faq.index', 'permission_name' => 'manage-helpdesk', 'order' => 3],
                    ['name' => 'Customer Support', 'route' => 'helpdesk.support.index', 'permission_name' => 'manage-helpdesk', 'order' => 4],
                ]
            ],

            // == MAINTENANCE GROUP ==
            [
                'group' => 'Maintenance', 'order' => 16, 'name' => 'Maintenance', 'route' => '#',
                'permission_name' => 'manage-maintenance',
                'icon_svg' => $icons['maintenance'],
                'submenu' => [
                    ['name' => 'Asset Management', 'route' => 'maintenance.assets.index', 'permission_name' => 'manage-maintenance', 'order' => 1],
                    ['name' => 'Work Orders', 'route' => 'maintenance.workorders.index', 'permission_name' => 'manage-maintenance', 'order' => 2],
                    ['name' => 'Preventive Maintenance', 'route' => 'maintenance.preventive.index', 'permission_name' => 'manage-maintenance', 'order' => 3],
                    ['name' => 'Maintenance Schedule', 'route' => 'maintenance.schedule.index', 'permission_name' => 'manage-maintenance', 'order' => 4],
                ]
            ],

            // == REPORTS GROUP ==
            [
                'group' => 'Reports', 'order' => 17, 'name' => 'Reports & Analytics', 'route' => '#',
                'permission_name' => 'view-reports',
                'icon_svg' => $icons['reports'],
                'submenu' => [
                    ['name' => 'Sales Reports', 'route' => 'reports.sales.index', 'permission_name' => 'view-reports', 'order' => 1],
                    ['name' => 'Financial Reports', 'route' => 'reports.financial.index', 'permission_name' => 'view-reports', 'order' => 2],
                    ['name' => 'Inventory Reports', 'route' => 'reports.inventory.index', 'permission_name' => 'view-reports', 'order' => 3],
                    ['name' => 'HR Reports', 'route' => 'reports.hr.index', 'permission_name' => 'view-reports', 'order' => 4],
                    ['name' => 'Custom Reports', 'route' => 'reports.custom.index', 'permission_name' => 'view-reports', 'order' => 5],
                ]
            ],

            // == SETTINGS GROUP ==
            [
                'group' => 'Settings', 'order' => 18, 'name' => 'Settings', 'route' => '#',
                'permission_name' => 'manage-settings',
                'icon_svg' => $icons['settings'],
                'submenu' => [
                    ['name' => 'Company Settings', 'route' => 'settings.company.index', 'permission_name' => 'manage-settings', 'order' => 1],
                    ['name' => 'User Management', 'route' => 'settings.users.index', 'permission_name' => 'manage-users', 'order' => 2],
                    ['name' => 'Roles & Permissions', 'route' => 'settings.roles.index', 'permission_name' => 'manage-roles', 'order' => 3],
                    ['name' => 'System Settings', 'route' => 'settings.system.index', 'permission_name' => 'manage-settings', 'order' => 4],
                    ['name' => 'Backup & Restore', 'route' => 'settings.backup.index', 'permission_name' => 'manage-settings', 'order' => 5],
                ]
            ],
        ];
    }

    private function createMenuItems(array $menus): void
    {
        foreach ($menus as $menuData) {
            $menu = MenuItem::create([
                'group' => $menuData['group'],
                'name' => $menuData['name'],
                'route' => $menuData['route'],
                'icon_svg' => $menuData['icon_svg'],
                'permission_name' => $menuData['permission_name'],
                'order' => $menuData['order'],
                'parent_id' => null,
                'status' => 1, // Use status instead of is_active
            ]);

            if (isset($menuData['submenu'])) {
                foreach ($menuData['submenu'] as $submenuData) {
                    MenuItem::create([
                        'group' => $menuData['group'],
                        'name' => $submenuData['name'],
                        'route' => $submenuData['route'],
                        'icon_svg' => null,
                        'permission_name' => $submenuData['permission_name'],
                        'order' => $submenuData['order'],
                        'parent_id' => (string)$menu->id, // Convert to string as per migration
                        'status' => 1, // Use status instead of is_active
                    ]);
                }

                echo "✅ Created menu group '{$menuData['name']}' with " . count($menuData['submenu']) . " submenus\n";
            } else {
                echo "✅ Created menu item '{$menuData['name']}'\n";
            }
        }
    }
}
