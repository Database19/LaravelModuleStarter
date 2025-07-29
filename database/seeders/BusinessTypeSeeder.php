<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $businessTypes = [
            [
                'key' => 'all',
                'name' => 'Semua Bidang Usaha',
                'description' => 'Akun umum yang berlaku untuk semua jenis bisnis',
                'icon' => 'fas fa-globe',
                'color' => '#6B7280',
                'sort_order' => 0,
            ],
            [
                'key' => 'trading',
                'name' => 'Perusahaan Dagang',
                'description' => 'Perusahaan yang bergerak di bidang jual beli barang dagangan',
                'icon' => 'fas fa-store',
                'color' => '#10B981',
                'sort_order' => 1,
            ],
            [
                'key' => 'service',
                'name' => 'Perusahaan Jasa',
                'description' => 'Perusahaan yang menyediakan layanan atau jasa',
                'icon' => 'fas fa-handshake',
                'color' => '#3B82F6',
                'sort_order' => 2,
            ],
            [
                'key' => 'manufacturing',
                'name' => 'Perusahaan Manufaktur',
                'description' => 'Perusahaan yang memproduksi barang dari bahan baku',
                'icon' => 'fas fa-industry',
                'color' => '#8B5CF6',
                'sort_order' => 3,
            ],
            [
                'key' => 'retail',
                'name' => 'Retail/Toko',
                'description' => 'Toko atau outlet yang menjual langsung ke konsumen',
                'icon' => 'fas fa-shopping-cart',
                'color' => '#F59E0B',
                'sort_order' => 4,
            ],
            [
                'key' => 'restaurant',
                'name' => 'Restoran/F&B',
                'description' => 'Usaha makanan dan minuman',
                'icon' => 'fas fa-utensils',
                'color' => '#EF4444',
                'sort_order' => 5,
            ],
            [
                'key' => 'construction',
                'name' => 'Konstruksi/Kontraktor',
                'description' => 'Perusahaan konstruksi dan kontraktor bangunan',
                'icon' => 'fas fa-hard-hat',
                'color' => '#F97316',
                'sort_order' => 6,
            ],
            [
                'key' => 'healthcare',
                'name' => 'Kesehatan/Klinik',
                'description' => 'Layanan kesehatan, klinik, rumah sakit',
                'icon' => 'fas fa-stethoscope',
                'color' => '#06B6D4',
                'sort_order' => 7,
            ],
            [
                'key' => 'education',
                'name' => 'Pendidikan/Sekolah',
                'description' => 'Institusi pendidikan, sekolah, universitas',
                'icon' => 'fas fa-graduation-cap',
                'color' => '#8B5CF6',
                'sort_order' => 8,
            ],
            [
                'key' => 'transportation',
                'name' => 'Transportasi/Logistik',
                'description' => 'Jasa transportasi dan logistik',
                'icon' => 'fas fa-truck',
                'color' => '#059669',
                'sort_order' => 9,
            ],
            [
                'key' => 'technology',
                'name' => 'Teknologi/IT',
                'description' => 'Perusahaan teknologi informasi dan software',
                'icon' => 'fas fa-laptop-code',
                'color' => '#6366F1',
                'sort_order' => 10,
            ],
            [
                'key' => 'agriculture',
                'name' => 'Pertanian/Perkebunan',
                'description' => 'Usaha pertanian, perkebunan, dan agribisnis',
                'icon' => 'fas fa-seedling',
                'color' => '#84CC16',
                'sort_order' => 11,
            ],
            [
                'key' => 'real_estate',
                'name' => 'Properti/Real Estate',
                'description' => 'Pengembang dan broker properti',
                'icon' => 'fas fa-building',
                'color' => '#64748B',
                'sort_order' => 12,
            ],
            [
                'key' => 'financial',
                'name' => 'Keuangan/Asuransi',
                'description' => 'Lembaga keuangan, bank, asuransi',
                'icon' => 'fas fa-university',
                'color' => '#DC2626',
                'sort_order' => 13,
            ],
            [
                'key' => 'hospitality',
                'name' => 'Hotel/Pariwisata',
                'description' => 'Hotel, resort, dan industri pariwisata',
                'icon' => 'fas fa-bed',
                'color' => '#DB2777',
                'sort_order' => 14,
            ],
        ];

        foreach ($businessTypes as $businessType) {
            DB::table('business_types')->updateOrInsert(
                ['key' => $businessType['key']],
                array_merge($businessType, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
