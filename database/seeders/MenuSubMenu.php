<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\SubMenu;

class MenuSubMenu extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Menambahkan Menu Utama
        $menus = [
            [
                'name' => 'Sales',
                'description' => 'Manage Sales',
                'icons' => 'bi bi-shop',
                'route' => 'sales.index',
                'permission' => 'manage-sales',
                'is_active' => true,
                'sub_menus' => [
                    ['name' => 'Sales Orders', 'description' => 'View Sales Orders', 'icons' => 'bi bi-file-earmark', 'route' => '#', 'is_active' => true],
                    ['name' => 'Sales Reports', 'description' => 'Generate Sales Reports', 'icons' => 'bi bi-bar-chart-line', 'route' => '#', 'is_active' => true]
                ]
            ],
            [
                'name' => 'Accounting',
                'description' => 'Manage Accounting',
                'icons' => 'bi bi-credit-card',
                'route' => 'accounting.index',
                'permission' => 'manage-accounting',
                'is_active' => true,
                'sub_menus' => [
                    ['name' => 'Invoices', 'description' => 'Manage Invoices', 'icons' => 'bi bi-file-earmark-text', 'route' => '#', 'is_active' => true],
                    ['name' => 'Payments', 'description' => 'Track Payments', 'icons' => 'bi bi-wallet', 'route' => '#', 'is_active' => true]
                ]
            ],
            [
                'name' => 'Inventory',
                'description' => 'Manage Inventory',
                'icons' => 'bi bi-box',
                'route' => 'inventory.index',
                'permission' => 'manage-inventory',
                'is_active' => true,
                'sub_menus' => [
                    ['name' => 'Stock Management', 'description' => 'View Stock Levels', 'icons' => 'bi bi-clipboard', 'route' => '#', 'is_active' => true],
                    ['name' => 'Product Categories', 'description' => 'Manage Categories', 'icons' => 'bi bi-tags', 'route' => '#', 'is_active' => true]
                ]
            ],
            [
                'name' => 'Maintenance',
                'description' => 'Manage Maintenance',
                'icons' => 'bi bi-tools',
                'route' => 'maintenance.index',
                'permission' => 'manage-maintenance',
                'is_active' => true,
                'sub_menus' => [
                    ['name' => 'Work Orders', 'description' => 'Create Work Orders', 'icons' => 'bi bi-journal', 'route' => '#', 'is_active' => true],
                    ['name' => 'Equipment', 'description' => 'Manage Equipment', 'icons' => 'bi bi-gear', 'route' => '#', 'is_active' => true]
                ]
            ],
            [
                'name' => 'Configuration',
                'description' => 'Configure Application',
                'icons' => 'bi bi-gear',
                'route' => 'configuration.index',
                'permission' => 'manage-maintenance',
                'is_active' => true,
                'sub_menus' => [
                    ['name' => 'System Settings', 'description' => 'Manage System Settings', 'icons' => 'bi bi-sliders', 'route' => 'configuration.settings.index', 'is_active' => true],
                    ['name' => 'User Management', 'description' => 'Manage Users', 'icons' => 'bi bi-person-circle', 'route' => '#', 'is_active' => true],
                    ['name' => 'Roles', 'description' => 'Manage Roles', 'icons' => 'bi bi-person-circle', 'route' => '#', 'is_active' => true],
                    ['name' => 'Permission', 'description' => 'Manage Permission', 'icons' => 'bi bi-person-circle', 'route' => '#', 'is_active' => true],
                ]
            ]
        ];

        foreach ($menus as $menuData) {
            $menu = Menu::create([
                'name' => $menuData['name'],
                'description' => $menuData['description'],
                'icons' => $menuData['icons'],
                'route' => $menuData['route'],
                'permission' => $menuData['permission'],
                'is_active' => $menuData['is_active']
            ]);

            foreach ($menuData['sub_menus'] as $subMenuData) {
                SubMenu::create([
                    'parent_id' => $menu->id,
                    'name' => $subMenuData['name'],
                    'description' => $subMenuData['description'],
                    'icons' => $subMenuData['icons'],
                    'route' => $subMenuData['route'],
                    'is_active' => $subMenuData['is_active']
                ]);
            }
        }
    }
}
