<?php

use App\Models\Menu;
use App\Models\SubMenu;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/create-module', function() {
    $menus = [
        [
            'name' => 'Master Data',
            'description' => 'Manajemen Data Referensi',
            'icons' => '',
            'route' => 'master.index',
            'permission' => 'manage-user',
            'is_active' => true,
            'sub_menus' => [
                ['name' => 'Material Master', 'description' => 'Data bahan baku dicatat, termasuk kode material, deskripsi, dan satuan.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Product Master', 'description' => 'Data produk jadi dan setengah jadi, termasuk SKU, kategori, dan spesifikasi teknis.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Bill Of Materials (BOM)', 'description' => 'Struktur material dicatat untuk setiap produk, termasuk jumlah bahan baku yang dibutuhkan.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Supplier Master', 'description' => 'Informasi pemasok seperti nama, alamat, kontak, dan kinerja dicatat.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Machine Master', 'description' => 'Data mesin pabrik, termasuk kapasitas, lokasi, dan jadwal pemeliharaan.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Work Center Master', 'description' => 'Lokasi kerja dicatat dengan detail seperti fungsi dan kapasitas.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Warehouse Master', 'description' => 'Lokasi Gudang.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Customer Master', 'description' => 'Data Customer.', 'icons' => '#', 'route' => '#', 'is_active' => true],
            ]
        ],
        [
            'name' => 'Procurement',
            'description' => 'Pengadaan Bahan Baku dan Peralatan Produksi',
            'icons' => '',
            'route' => 'procurement.index',
            'permission' => 'manage-user',
            'is_active' => true,
            'sub_menus' => [
                ['name' => 'Supplier Management', 'description' => 'Data pemasok dikelola, termasuk kategori, harga, dan reputasi.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Purchase Request (PR)', 'description' => 'Permintaan pembelian dibuat oleh departemen terkait berdasarkan kebutuhan.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Purchase Order', 'description' => 'Pesanan resmi dibuat berdasarkan permintaan yang disetujui.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Delivery Tracking', 'description' => 'Barang yang diterima dari pemasok dilacak untuk memastikan jadwal dan kualitas.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Invoice Verification', 'description' => 'Faktur pemasok diverifikasi sebelum pembayaran dilakukan.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Good Receipt', 'description' => 'Penerimaan barang dicatat, dan stok diupdate dalam sistem inventaris.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Approval Workflow', 'description' => 'Proses persetujuan PR dan PO dilakukan oleh manajer atau otoritas terkait.', 'icons' => '#', 'route' => '#', 'is_active' => true],
            ]
        ],
        [
            'name' => 'Production',
            'description' => 'Proses Produksi Barang',
            'icons' => '',
            'route' => 'production.index',
            'permission' => 'manage-production',
            'is_active' => true,
            'sub_menus' => [
                ['name' => 'Bill of Materials (BOM)', 'description' => 'Struktur material yang diperlukan untuk memproduksi barang.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Production Scheduling', 'description' => 'Penjadwalan produksi berdasarkan permintaan atau pesanan yang diterima.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Work Order Management', 'description' => 'Mengelola perintah kerja untuk memulai produksi.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Production Tracking', 'description' => 'Melacak status produksi dan status pekerjaan yang sedang berjalan.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Quality Check', 'description' => 'Melakukan pemeriksaan kualitas barang yang diproduksi.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Cost Calculation', 'description' => 'Menghitung biaya produksi untuk menentukan harga pokok produksi.', 'icons' => '#', 'route' => '#', 'is_active' => true],
            ]
        ],
        [
            'name' => 'Quality Control',
            'description' => 'Kontrol Kualitas',
            'icons' => '',
            'route' => 'qualitycontrol.index',
            'permission' => 'manage-user',
            'is_active' => true,
            'sub_menus' => [
                ['name' => 'Incoming Material Inspection', 'description' => 'Bahan baku diperiksa kualitasnya sebelum diterima.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'In-Process Quality Check', 'description' => 'Proses produksi diawasi untuk memastikan mutu.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Finished Goods Inspection', 'description' => 'Barang jadi diperiksa sebelum dikirim ke pelanggan.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Defect Analysis', 'description' => 'Produk cacat dianalisis untuk mencari penyebab dan solusi.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Quality Reporting', 'description' => 'Laporan mutu dibuat untuk evaluasi.', 'icons' => '#', 'route' => '#', 'is_active' => true],
            ]
        ],
        [
            'name' => 'Inventory',
            'description' => 'Manajemen Stok',
            'icons' => '',
            'route' => 'inventory.index',
            'permission' => 'manage-inventory',
            'is_active' => true,
            'sub_menus' => [
                ['name' => 'Stock Management', 'description' => 'Mengelola stok bahan baku dan barang jadi.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Product Categories', 'description' => 'Menyusun kategori produk untuk memudahkan pengelolaan dan pengelompokan.', 'icons' => '#', 'route' => '#', 'is_active' => true],
            ]
        ],
        [
            'name' => 'Sales',
            'description' => 'Penjualan Produk',
            'icons' => '',
            'route' => 'sales.index',
            'permission' => 'manage-sales',
            'is_active' => true,
            'sub_menus' => [
                ['name' => 'Sales Orders', 'description' => 'Mengelola pesanan dari pelanggan.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Sales Reports', 'description' => 'Menyediakan laporan penjualan.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Invoices', 'description' => 'Mengelola faktur penjualan untuk pelanggan.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Payments', 'description' => 'Mencatat pembayaran yang diterima dari pelanggan.', 'icons' => '#', 'route' => '#', 'is_active' => true],
            ]
        ],
        [
            'name' => 'Accounting',
            'description' => 'Pengelolaan Keuangan',
            'icons' => '',
            'route' => 'accounting.index',
            'permission' => 'manage-accounting',
            'is_active' => true,
            'sub_menus' => [
                ['name' => 'Financial Report', 'description' => 'Laporan keuangan untuk evaluasi bisnis.', 'icons' => '#', 'route' => '#', 'is_active' => true],
            ]
        ],
        [
            'name' => 'HR',
            'description' => 'Sumber Daya Manusia',
            'icons' => '',
            'route' => 'humanresource.index',
            'permission' => 'manage-hr',
            'is_active' => true,
            'sub_menus' => [
                ['name' => 'Employee Management', 'description' => 'Data karyawan dicatat dan dikelola.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Attendance Tracking', 'description' => 'Mengelola absensi karyawan.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Payroll Management', 'description' => 'Mengelola pembayaran gaji karyawan.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Leave Management', 'description' => 'Mencatat dan mengelola pengajuan cuti karyawan.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Performance Review', 'description' => 'Evaluasi kinerja karyawan.', 'icons' => '#', 'route' => '#', 'is_active' => true],
            ]
        ],
        [
            'name' => 'Maintenance',
            'description' => 'Pemeliharaan Mesin dan Peralatan',
            'icons' => '',
            'route' => 'maintenance.index',
            'permission' => 'manage-maintenance',
            'is_active' => true,
            'sub_menus' => [
                ['name' => 'Work Orders', 'description' => 'Memelihara dan memperbaiki mesin yang rusak berdasarkan perintah kerja.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Equipment', 'description' => 'Memelihara dan mengelola peralatan produksi.', 'icons' => '#', 'route' => '#', 'is_active' => true],
            ]
        ],
        [
            'name' => 'Configuration',
            'description' => 'Pengaturan Sistem',
            'icons' => '',
            'route' => 'configuration.index',
            'permission' => 'manage-maintenance',
            'is_active' => true,
            'sub_menus' => [
                ['name' => 'System Settings', 'description' => 'Pengaturan sistem seperti parameter umum dan integrasi.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'User Management', 'description' => 'Mengelola data pengguna dan akses sistem.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Roles', 'description' => 'Menentukan peran pengguna dan hak akses dalam sistem.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Permission', 'description' => 'Mengelola izin akses pengguna di setiap modul.', 'icons' => '#', 'route' => '#', 'is_active' => true],
                ['name' => 'Module Setting', 'description' => 'Mengonfigurasi pengaturan modul yang aktif atau non-aktif.', 'icons' => '#', 'route' => '#', 'is_active' => true],
            ]
        ]
    ];

    // foreach ($menus as $menuData) {
        // $menu = Menu::create([
        //     'name' => $menuData['name'],
        //     'description' => $menuData['description'],
        //     'icons' => $menuData['icons'],
        //     'route' => $menuData['route'],
        //     'permission' => $menuData['permission'],
        //     'is_active' => $menuData['is_active']
        // ]);

    //     foreach ($menuData['sub_menus'] as $subMenuData) {
    //         SubMenu::create([
    //             'parent_id' => $menu->id,
    //             'name' => $subMenuData['name'],
    //             'description' => $subMenuData['description'],
    //             'icons' => $subMenuData['icons'],
    //             'route' => $subMenuData['route'],
    //             'is_active' => $subMenuData['is_active']
    //         ]);
    //     }
    // }

    return "sukses";
});

Route::get('/assign-role', function() {
    // $userSales = User::create([
    //     'name' => 'Manager Sales',
    //     'email' => 'sales@crm.com',
    //     'password' => Hash::make('kodok123')
    // ]);

    // $userAkuntan = User::create([
    //     'name' => 'Manager Accounting',
    //     'email' => 'accounting@crm.com',
    //     'password' => Hash::make('kodok123')
    // ]);

    // $userInventory = User::create([
    //     'name' => 'Manager Inventory',
    //     'email' => 'inventory@crm.com',
    //     'password' => Hash::make('kodok123')
    // ]);

    // $userMaintenance = User::create([
    //     'name' => 'Manager Maintenance',
    //     'email' => 'maintenance@crm.com',
    //     'password' => Hash::make('kodok123')
    // ]);

    // $err = 0;
    // if(!$userSales->assignRole('Sales Manager')){
    //     $err = 1;
    // }
    // if(!$userAkuntan->assignRole('Accountant')){
    //     $err = 1;
    // }
    // if(!$userInventory->assignRole('Inventory Clerk')){
    //     $err = 1;
    // }
    // if(!$userMaintenance->assignRole('Maintenance')){
    //     $err = 1;
    // }

    // if($err === 1){
    //     return "fail";
    // }

    // return "sukses";
});

Route::get('/get-module', function () {
    $data = Menu::with('subMenus:name,parent_id')
                ->select(['id', 'name'])
                ->get();

    return $data;
});

// Route::get('/module-delete', function() {
//     $menu = Menu::with('SubMenus')->find(33);

//     if ($menu) {
//         $menu->SubMenus()->delete();
//         $menu->delete();

//         Alert::success('Sukses', 'Module berhasil terhapus');
//         return redirect()->route('home');
//     }

//     Alert::info('Not Found', 'Module Tidak Ditemukan !');
//     return redirect()->route('home');
// });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
