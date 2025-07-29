<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuIndonesiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing menu items
        MenuItem::truncate();
        echo "🗑️  Cleared existing menu items\n";

        $this->createMenuItems();
        echo "✅ MenuIndonesiaSeeder completed successfully!\n";
    }

    private function createMenuItems(): void
    {
        $menuStructure = [
            // Super Admin Menu Group
            [
                'group' => 'Super Admin',
                'name' => 'Manajemen Perusahaan',
                'route' => null,
                'icon' => 'fas fa-building',
                'parent_id' => null,
                'order' => 1,
                'status' => 'active',
                'permission' => 'manage-companies',
                'submenus' => [
                    ['name' => 'Daftar Perusahaan', 'url' => '/superadmin/companies', 'icon' => 'fas fa-list', 'permission' => 'manage-companies'],
                    ['name' => 'Pindah Perusahaan', 'url' => '/superadmin/switch-company', 'icon' => 'fas fa-exchange-alt', 'permission' => 'manage-companies'],
                    ['name' => 'Pengguna Global', 'url' => '/superadmin/users', 'icon' => 'fas fa-users', 'permission' => 'manage-global-users'],
                    ['name' => 'Monitor Sistem', 'url' => '/superadmin/monitor', 'icon' => 'fas fa-monitor-heart-rate', 'permission' => 'system-monitor'],
                ]
            ],

            // Main Dashboard
            [
                'group' => 'Utama',
                'name' => 'Dasbor',
                'route' => '/home',
                'icon' => 'fas fa-tachometer-alt',
                'parent_id' => null,
                'order' => 2,
                'status' => 'active',
                'permission' => 'manage-dashboard'
            ],

            // Master Data Module
            [
                'group' => 'Data Master',
                'name' => 'Data Master',
                'route' => null,
                'icon' => 'fas fa-database',
                'parent_id' => null,
                'order' => 3,
                'status' => 'active',
                'permission' => 'manage-master-data',
                'submenus' => [
                    ['name' => 'Produk', 'url' => '/master-data/products', 'icon' => 'fas fa-box', 'permission' => 'manage-master-data'],
                    ['name' => 'Kategori Produk', 'url' => '/master-data/product-categories', 'icon' => 'fas fa-tags', 'permission' => 'manage-master-data'],
                    ['name' => 'Merek', 'url' => '/master-data/brands', 'icon' => 'fas fa-trademark', 'permission' => 'manage-master-data'],
                    ['name' => 'Satuan', 'url' => '/master-data/units', 'icon' => 'fas fa-weight', 'permission' => 'manage-master-data'],
                    ['name' => 'Pelanggan', 'url' => '/master-data/customers', 'icon' => 'fas fa-user-friends', 'permission' => 'manage-master-data'],
                    ['name' => 'Pemasok', 'url' => '/master-data/suppliers', 'icon' => 'fas fa-truck', 'permission' => 'manage-master-data'],
                    ['name' => 'Gudang', 'url' => '/master-data/warehouses', 'icon' => 'fas fa-warehouse', 'permission' => 'manage-master-data'],
                    ['name' => 'Departemen', 'url' => '/master-data/departments', 'icon' => 'fas fa-sitemap', 'permission' => 'manage-master-data'],
                    ['name' => 'Posisi Jabatan', 'url' => '/master-data/positions', 'icon' => 'fas fa-user-tie', 'permission' => 'manage-master-data'],
                ]
            ],

            // CRM Module
            [
                'group' => 'CRM',
                'name' => 'Manajemen Pelanggan',
                'route' => null,
                'icon' => 'fas fa-handshake',
                'parent_id' => null,
                'order' => 4,
                'status' => 'active',
                'permission' => 'manage-crm',
                'submenus' => [
                    ['name' => 'Prospek', 'url' => '/crm/leads', 'icon' => 'fas fa-user-plus', 'permission' => 'manage-crm'],
                    ['name' => 'Peluang', 'url' => '/crm/opportunities', 'icon' => 'fas fa-bullseye', 'permission' => 'manage-crm'],
                    ['name' => 'Kesepakatan', 'url' => '/crm/deals', 'icon' => 'fas fa-handshake', 'permission' => 'manage-crm'],
                    ['name' => 'Aktivitas', 'url' => '/crm/activities', 'icon' => 'fas fa-calendar-check', 'permission' => 'manage-crm'],
                ]
            ],

            // Sales Module
            [
                'group' => 'Penjualan',
                'name' => 'Penjualan',
                'route' => null,
                'icon' => 'fas fa-shopping-cart',
                'parent_id' => null,
                'order' => 5,
                'status' => 'active',
                'permission' => 'manage-sales',
                'submenus' => [
                    ['name' => 'Pesanan Penjualan', 'url' => '/sales/orders', 'icon' => 'fas fa-file-invoice', 'permission' => 'manage-sales'],
                    ['name' => 'Buat Pesanan Baru', 'url' => '/sales/orders/create', 'icon' => 'fas fa-plus', 'permission' => 'manage-sales'],
                ]
            ],

            // Purchasing Module
            [
                'group' => 'Pembelian',
                'name' => 'Pembelian',
                'route' => null,
                'icon' => 'fas fa-shopping-bag',
                'parent_id' => null,
                'order' => 6,
                'status' => 'active',
                'permission' => 'manage-purchasing',
                'submenus' => [
                    ['name' => 'Pesanan Pembelian', 'url' => '/purchasing/purchase-orders', 'icon' => 'fas fa-file-contract', 'permission' => 'manage-purchasing'],
                    ['name' => 'Buat Pesanan Baru', 'url' => '/purchasing/purchase-orders/create', 'icon' => 'fas fa-plus', 'permission' => 'manage-purchasing'],
                ]
            ],

            // Inventory Module
            [
                'group' => 'Inventaris',
                'name' => 'Inventaris',
                'route' => null,
                'icon' => 'fas fa-boxes',
                'parent_id' => null,
                'order' => 7,
                'status' => 'active',
                'permission' => 'manage-inventory',
                'submenus' => [
                    ['name' => 'Stok Barang', 'url' => '/inventory/stock', 'icon' => 'fas fa-cubes', 'permission' => 'manage-inventory'],
                    ['name' => 'Pergerakan Stok', 'url' => '/inventory/movements', 'icon' => 'fas fa-exchange-alt', 'permission' => 'manage-inventory'],
                    ['name' => 'Penyesuaian Stok', 'url' => '/inventory/adjustments', 'icon' => 'fas fa-balance-scale', 'permission' => 'manage-inventory'],
                    ['name' => 'Kategori Produk', 'url' => '/inventory/product-categories', 'icon' => 'fas fa-tags', 'permission' => 'manage-inventory'],
                    ['name' => 'Produk', 'url' => '/inventory/products', 'icon' => 'fas fa-box', 'permission' => 'manage-inventory'],
                ]
            ],

            // Warehouse Module
            [
                'group' => 'Gudang',
                'name' => 'Manajemen Gudang',
                'route' => null,
                'icon' => 'fas fa-warehouse',
                'parent_id' => null,
                'order' => 8,
                'status' => 'active',
                'permission' => 'manage-warehouse',
                'submenus' => [
                    ['name' => 'Daftar Gudang', 'url' => '/warehouse/warehouses', 'icon' => 'fas fa-building', 'permission' => 'manage-warehouse'],
                    ['name' => 'Transfer Stok', 'url' => '/warehouse/transfers', 'icon' => 'fas fa-truck-moving', 'permission' => 'manage-warehouse'],
                    ['name' => 'Penghitungan Stok', 'url' => '/warehouse/counts', 'icon' => 'fas fa-calculator', 'permission' => 'manage-warehouse'],
                ]
            ],

            // Manufacturing Module
            [
                'group' => 'Manufaktur',
                'name' => 'Manufaktur',
                'route' => null,
                'icon' => 'fas fa-industry',
                'parent_id' => null,
                'order' => 9,
                'status' => 'active',
                'permission' => 'manage-manufacturing',
                'submenus' => [
                    ['name' => 'Pesanan Produksi', 'url' => '/manufacturing/orders', 'icon' => 'fas fa-clipboard-list', 'permission' => 'manage-manufacturing'],
                    ['name' => 'Bill of Materials', 'url' => '/manufacturing/boms', 'icon' => 'fas fa-list-alt', 'permission' => 'manage-manufacturing'],
                    ['name' => 'Pusat Kerja', 'url' => '/manufacturing/workcenters', 'icon' => 'fas fa-cogs', 'permission' => 'manage-manufacturing'],
                ]
            ],

            // Quality Control Module
            [
                'group' => 'Kontrol Kualitas',
                'name' => 'Kontrol Kualitas',
                'route' => null,
                'icon' => 'fas fa-award',
                'parent_id' => null,
                'order' => 10,
                'status' => 'active',
                'permission' => 'manage-quality-control',
                'submenus' => [
                    ['name' => 'Standar Kualitas', 'url' => '/quality/standards', 'icon' => 'fas fa-medal', 'permission' => 'manage-quality-control'],
                    ['name' => 'Inspeksi Kualitas', 'url' => '/quality/inspections', 'icon' => 'fas fa-search', 'permission' => 'manage-quality-control'],
                    ['name' => 'Pemeriksaan Kualitas', 'url' => '/quality/checks', 'icon' => 'fas fa-check-circle', 'permission' => 'manage-quality-control'],
                ]
            ],

            // Accounting Module
            [
                'group' => 'Akuntansi',
                'name' => 'Akuntansi',
                'route' => null,
                'icon' => 'fas fa-calculator',
                'parent_id' => null,
                'order' => 11,
                'status' => 'active',
                'permission' => 'manage-accounting',
                'submenus' => [
                    ['name' => 'Bagan Akun', 'url' => '/accounting/coa', 'icon' => 'fas fa-list', 'permission' => 'manage-accounting'],
                    ['name' => 'Jurnal Umum', 'url' => '/accounting/journals', 'icon' => 'fas fa-book', 'permission' => 'manage-accounting'],
                    ['name' => 'Aset Tetap', 'url' => '/accounting/fixed-assets', 'icon' => 'fas fa-building', 'permission' => 'manage-accounting'],
                    ['name' => 'Perencanaan Anggaran', 'url' => '/accounting/budget', 'icon' => 'fas fa-chart-pie', 'permission' => 'manage-accounting'],
                    ['name' => 'Pengaturan Akuntansi', 'url' => '/accounting/settings', 'icon' => 'fas fa-cog', 'permission' => 'manage-accounting'],
                    ['name' => 'Laporan Keuangan', 'url' => '/accounting/reports', 'icon' => 'fas fa-chart-line', 'permission' => 'manage-accounting'],
                ]
            ],

            // Human Resources Module
            [
                'group' => 'Sumber Daya Manusia',
                'name' => 'SDM',
                'route' => null,
                'icon' => 'fas fa-users',
                'parent_id' => null,
                'order' => 12,
                'status' => 'active',
                'permission' => 'manage-hr',
                'submenus' => [
                    ['name' => 'Karyawan', 'url' => '/humanresource/employees', 'icon' => 'fas fa-user', 'permission' => 'manage-hr'],
                    ['name' => 'Penggajian', 'url' => '/humanresource/payrolls', 'icon' => 'fas fa-money-bill', 'permission' => 'manage-hr'],
                    ['name' => 'Absensi', 'url' => '/humanresource/attendance', 'icon' => 'fas fa-clock', 'permission' => 'manage-hr'],
                    ['name' => 'Cuti', 'url' => '/humanresource/leave', 'icon' => 'fas fa-calendar-times', 'permission' => 'manage-hr'],
                    ['name' => 'Penilaian Kinerja', 'url' => '/humanresource/performance', 'icon' => 'fas fa-star', 'permission' => 'manage-hr'],
                    ['name' => 'Rekrutmen', 'url' => '/humanresource/recruitment', 'icon' => 'fas fa-user-plus', 'permission' => 'manage-hr'],
                ]
            ],

            // Project Management Module
            [
                'group' => 'Manajemen Proyek',
                'name' => 'Manajemen Proyek',
                'route' => null,
                'icon' => 'fas fa-project-diagram',
                'parent_id' => null,
                'order' => 13,
                'status' => 'active',
                'permission' => 'manage-projects',
                'submenus' => [
                    ['name' => 'Proyek', 'url' => '/projects', 'icon' => 'fas fa-folder-open', 'permission' => 'manage-projects'],
                    ['name' => 'Tugas', 'url' => '/tasks', 'icon' => 'fas fa-tasks', 'permission' => 'manage-projects'],
                    ['name' => 'Anggota Tim', 'url' => '/project-members', 'icon' => 'fas fa-users-cog', 'permission' => 'manage-projects'],
                    ['name' => 'Pelacakan Waktu', 'url' => '/timetrack', 'icon' => 'fas fa-stopwatch', 'permission' => 'manage-projects'],
                ]
            ],

            // Document Management Module
            [
                'group' => 'Manajemen Dokumen',
                'name' => 'Manajemen Dokumen',
                'route' => null,
                'icon' => 'fas fa-file-alt',
                'parent_id' => null,
                'order' => 14,
                'status' => 'active',
                'permission' => 'manage-documents',
                'submenus' => [
                    ['name' => 'Perpustakaan Dokumen', 'url' => '/documents/library', 'icon' => 'fas fa-book-open', 'permission' => 'manage-documents'],
                    ['name' => 'Kategori Dokumen', 'url' => '/documents/categories', 'icon' => 'fas fa-folder', 'permission' => 'manage-documents'],
                    ['name' => 'Template Dokumen', 'url' => '/documents/templates', 'icon' => 'fas fa-file-contract', 'permission' => 'manage-documents'],
                ]
            ],

            // Point of Sale Module
            [
                'group' => 'Point of Sale',
                'name' => 'Kasir',
                'route' => null,
                'icon' => 'fas fa-cash-register',
                'parent_id' => null,
                'order' => 15,
                'status' => 'active',
                'permission' => 'manage-pos',
                'submenus' => [
                    ['name' => 'Transaksi Kasir', 'url' => '/pos/transactions', 'icon' => 'fas fa-receipt', 'permission' => 'manage-pos'],
                    ['name' => 'Kasir Baru', 'url' => '/pos/new', 'icon' => 'fas fa-plus-circle', 'permission' => 'manage-pos'],
                    ['name' => 'Pembayaran', 'url' => '/pos/payments', 'icon' => 'fas fa-credit-card', 'permission' => 'manage-pos'],
                ]
            ],

            // Helpdesk Module
            [
                'group' => 'Helpdesk',
                'name' => 'Helpdesk',
                'route' => null,
                'icon' => 'fas fa-life-ring',
                'parent_id' => null,
                'order' => 16,
                'status' => 'active',
                'permission' => 'manage-helpdesk',
                'submenus' => [
                    ['name' => 'Tiket Dukungan', 'url' => '/helpdesk/tickets', 'icon' => 'fas fa-ticket-alt', 'permission' => 'manage-helpdesk'],
                    ['name' => 'Basis Pengetahuan', 'url' => '/helpdesk/knowledge', 'icon' => 'fas fa-lightbulb', 'permission' => 'manage-helpdesk'],
                    ['name' => 'Kategori Tiket', 'url' => '/helpdesk/categories', 'icon' => 'fas fa-tags', 'permission' => 'manage-helpdesk'],
                ]
            ],

            // Maintenance Module
            [
                'group' => 'Pemeliharaan',
                'name' => 'Pemeliharaan',
                'route' => null,
                'icon' => 'fas fa-tools',
                'parent_id' => null,
                'order' => 17,
                'status' => 'active',
                'permission' => 'manage-maintenance',
                'submenus' => [
                    ['name' => 'Peralatan', 'url' => '/maintenance/equipment', 'icon' => 'fas fa-wrench', 'permission' => 'manage-maintenance'],
                    ['name' => 'Pesanan Pemeliharaan', 'url' => '/maintenance/orders', 'icon' => 'fas fa-clipboard-list', 'permission' => 'manage-maintenance'],
                    ['name' => 'Jadwal Pemeliharaan', 'url' => '/maintenance/schedules', 'icon' => 'fas fa-calendar-alt', 'permission' => 'manage-maintenance'],
                ]
            ],

            // Reports & Analytics
            [
                'group' => 'Laporan',
                'name' => 'Laporan & Analitik',
                'route' => null,
                'icon' => 'fas fa-chart-bar',
                'parent_id' => null,
                'order' => 18,
                'status' => 'active',
                'permission' => 'view-reports',
                'submenus' => [
                    ['name' => 'Laporan Penjualan', 'url' => '/reports/sales', 'icon' => 'fas fa-chart-line', 'permission' => 'view-reports'],
                    ['name' => 'Laporan Inventaris', 'url' => '/reports/inventory', 'icon' => 'fas fa-boxes', 'permission' => 'view-reports'],
                    ['name' => 'Laporan Keuangan', 'url' => '/reports/financial', 'icon' => 'fas fa-money-bill-wave', 'permission' => 'view-reports'],
                    ['name' => 'Laporan SDM', 'url' => '/reports/hr', 'icon' => 'fas fa-users', 'permission' => 'view-reports'],
                    ['name' => 'Dashboard Analitik', 'url' => '/reports/analytics', 'icon' => 'fas fa-chart-pie', 'permission' => 'view-reports'],
                ]
            ],

            // Settings
            [
                'group' => 'Pengaturan',
                'name' => 'Pengaturan',
                'route' => null,
                'icon' => 'fas fa-cogs',
                'parent_id' => null,
                'order' => 19,
                'status' => 'active',
                'permission' => 'manage-settings',
                'submenus' => [
                    ['name' => 'Manajemen Pengguna', 'url' => '/admin/users', 'icon' => 'fas fa-users-cog', 'permission' => 'manage-users'],
                    ['name' => 'Peran & Hak Akses', 'url' => '/admin/roles', 'icon' => 'fas fa-user-shield', 'permission' => 'manage-roles'],
                    ['name' => 'Manajemen Menu', 'url' => '/admin/menu', 'icon' => 'fas fa-bars', 'permission' => 'manage-permissions'],
                    ['name' => 'Pengaturan Sistem', 'url' => '/settings/system', 'icon' => 'fas fa-server', 'permission' => 'manage-settings'],
                    ['name' => 'Pengaturan Perusahaan', 'url' => '/settings/company', 'icon' => 'fas fa-building', 'permission' => 'manage-settings'],
                ]
            ],
        ];

        foreach ($menuStructure as $menuData) {
            // Create parent menu
            $parentMenu = MenuItem::create([
                'group' => $menuData['group'],
                'name' => $menuData['name'],
                'route' => $menuData['route'] ?? '#',
                'icon_svg' => $this->getIconSvg($menuData['icon']),
                'parent_id' => null,
                'order' => $menuData['order'],
                'status' => $menuData['status'] === 'active' ? 1 : 0,
                'permission_name' => $menuData['permission'] ?? null,
            ]);

            // Create submenus if they exist
            if (isset($menuData['submenus'])) {
                foreach ($menuData['submenus'] as $index => $submenu) {
                    MenuItem::create([
                        'group' => $menuData['group'],
                        'name' => $submenu['name'],
                        'route' => $this->convertUrlToRoute($submenu['url']),
                        'icon_svg' => $this->getIconSvg($submenu['icon']),
                        'parent_id' => (string)$parentMenu->id,
                        'order' => $index + 1,
                        'status' => 1,
                        'permission_name' => $submenu['permission'] ?? null,
                    ]);
                }
                echo "✅ Created menu group '{$menuData['name']}' with " . count($menuData['submenus']) . " submenus\n";
            } else {
                echo "✅ Created menu item '{$menuData['name']}'\n";
            }
        }
    }

    private function getIconSvg(string $iconClass): string
    {
        // Convert FontAwesome class to simple icon name for SVG
        $iconMap = [
            'fas fa-building' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4V5h12v10z"></path></svg>',
            'fas fa-tachometer-alt' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path></svg>',
            'fas fa-database' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z"></path><path d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z"></path><path d="M17 5c0 1.657-3.134 3-7 3S3 6.657 3 5s3.134-3 7-3 7 1.343 7 3z"></path></svg>',
            'fas fa-handshake' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path></svg>',
            'fas fa-shopping-cart' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path></svg>',
            'fas fa-shopping-bag' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path></svg>',
            'fas fa-boxes' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>',
            'fas fa-warehouse' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.84L7.25 9.036l.394.17a1 1 0 00.788 0l7-3a1 1 0 000-1.84l-7-3zM3.5 9.704l6.894 2.955a1 1 0 00.788 0L17.5 9.704V17a1 1 0 01-1 1H4a1 1 0 01-1-1V9.704z"></path></svg>',
            'fas fa-industry' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>',
            'fas fa-award' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
            'fas fa-calculator' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V4a2 2 0 00-2-2H6zm1 2a1 1 0 000 2h6a1 1 0 100-2H7zm6 7a1 1 0 011 1v3a1 1 0 11-2 0v-3a1 1 0 011-1zm-3 3a1 1 0 100 2h.01a1 1 0 100-2H10zm-4 1a1 1 0 011-1h.01a1 1 0 110 2H7a1 1 0 01-1-1zm1-4a1 1 0 100 2h.01a1 1 0 100-2H7zm2 1a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1zm4-4a1 1 0 100 2h.01a1 1 0 100-2H13zM9 9a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1zM7 8a1 1 0 000 2h.01a1 1 0 000-2H7z" clip-rule="evenodd"></path></svg>',
            'fas fa-users' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>',
            'fas fa-project-diagram' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path></svg>',
            'fas fa-file-alt' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>',
            'fas fa-cash-register' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>',
            'fas fa-life-ring' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path></svg>',
            'fas fa-tools' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path></svg>',
            'fas fa-chart-bar' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path></svg>',
            'fas fa-cogs' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path></svg>',
        ];

        return $iconMap[$iconClass] ?? '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path></svg>';
    }

    private function convertUrlToRoute(string $url): string
    {
        // Convert URL to route name
        $routeMap = [
            // Super Admin routes
            '/superadmin/companies' => 'superadmin.companies.index',
            '/superadmin/switch-company' => 'superadmin.switch-company',
            '/superadmin/users' => 'superadmin.users.index',
            '/superadmin/monitor' => 'superadmin.monitor.index',

            // Main routes
            '/home' => 'home',

            // Master Data routes
            '/master-data/products' => 'master-data.products.index',
            '/master-data/product-categories' => 'master-data.product-categories.index',
            '/master-data/brands' => 'master-data.brands.index',
            '/master-data/units' => 'master-data.units.index',
            '/master-data/customers' => 'master-data.customers.index',
            '/master-data/suppliers' => 'master-data.suppliers.index',
            '/master-data/warehouses' => 'master-data.warehouses.index',
            '/master-data/departments' => 'master-data.departments.index',
            '/master-data/positions' => 'master-data.positions.index',

            // CRM routes
            '/crm/leads' => 'crm.leads.index',
            '/crm/opportunities' => 'crm.opportunities.index',
            '/crm/deals' => 'crm.deals.index',
            '/crm/activities' => 'crm.activities.index',

            // Sales routes
            '/sales/orders' => 'sales.orders.index',
            '/sales/orders/create' => 'sales.orders.create',

            // Purchasing routes
            '/purchasing/purchase-orders' => 'purchasing.purchase-orders.index',
            '/purchasing/purchase-orders/create' => 'purchasing.purchase-orders.create',

            // Inventory routes
            '/inventory/stock' => 'inventory.stock.index',
            '/inventory/movements' => 'inventory.movements.index',
            '/inventory/adjustments' => 'inventory.adjustments.index',
            '/inventory/product-categories' => 'inventory.product-categories.index',
            '/inventory/products' => 'inventory.products.index',

            // Warehouse routes
            '/warehouse/warehouses' => 'warehouse.warehouses.index',
            '/warehouse/transfers' => 'warehouse.transfers.index',
            '/warehouse/counts' => 'warehouse.counts.index',

            // Manufacturing routes
            '/manufacturing/orders' => 'manufacturing.orders.index',
            '/manufacturing/boms' => 'manufacturing.boms.index',
            '/manufacturing/workcenters' => 'manufacturing.workcenters.index',

            // Quality Control routes (updated to correct prefix)
            '/quality/standards' => 'qualitycontrol.standards.index',
            '/quality/inspections' => 'qualitycontrol.inspections.index',
            '/quality/checks' => 'qualitycontrol.checks.index',

            // Accounting routes
            '/accounting/coa' => 'accounting.coa.index',
            '/accounting/journals' => 'accounting.journals.index',
            '/accounting/fixed-assets' => 'accounting.fixed-assets.index',
            '/accounting/budget' => 'accounting.budget.index',
            '/accounting/settings' => 'accounting.settings.index',
            '/accounting/reports' => 'accounting.reports.index',

            // Human Resources routes
            '/humanresource/employees' => 'humanresource.employees.index',
            '/humanresource/payrolls' => 'humanresource.payrolls.index',
            '/humanresource/attendance' => 'humanresource.attendance.index',
            '/humanresource/leave' => 'humanresource.leave.index',
            '/humanresource/performance' => 'humanresource.performance.index',
            '/humanresource/recruitment' => 'humanresource.recruitment.index',

            // Project Management routes (updated to correct prefix)
            '/projects' => 'projectmanagement.projects.index',
            '/tasks' => 'projectmanagement.tasks.index',
            '/project-members' => '#', // Not available
            '/timetrack' => 'projectmanagement.timetrack.index',

            // Document Management routes
            '/documents/library' => 'documents.library.index',
            '/documents/categories' => 'documents.categories.index',
            '/documents/templates' => 'documents.templates.index',

            // Point of Sale routes (updated to correct prefix)
            '/pos/transactions' => 'pointofsales.transactions.index',
            '/pos/new' => '#', // Not available - use create instead
            '/pos/payments' => 'pointofsales.payments.index',

            // Helpdesk routes
            '/helpdesk/tickets' => 'helpdesk.tickets.index',
            '/helpdesk/knowledge' => 'helpdesk.knowledge.index',
            '/helpdesk/categories' => 'helpdesk.categories.index',

            // Maintenance routes
            '/maintenance/equipment' => 'maintenance.equipment.index',
            '/maintenance/orders' => 'maintenance.orders.index',
            '/maintenance/schedules' => 'maintenance.schedule.index',

            // Reports routes
            '/reports/sales' => 'reports.sales.index',
            '/reports/inventory' => 'reports.inventory.index',
            '/reports/financial' => 'reports.financial.index',
            '/reports/hr' => 'reports.hr.index',
            '/reports/analytics' => '#', // Not available

            // Admin/Settings routes
            '/admin/users' => 'admin.users.index',
            '/admin/roles' => 'admin.roles.index',
            '/admin/menu' => 'admin.menu.index',
            '/settings/system' => '#', // Not available
            '/settings/company' => 'settings.company.index',
        ];

        return $routeMap[$url] ?? '#';
    }
}
