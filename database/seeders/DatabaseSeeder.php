<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // 1. Core comprehensive seeder (roles, permissions, companies, users)
            ComprehensiveSeeder::class,

            // 2. Business types and account recommendations
            BusinessTypeSeeder::class,
            AccountRecommendationSeeder::class,

            // 3. Menu items seeder (Indonesian)
            MenuIndonesiaSeeder::class,

            // 4. Sample data seeder (master data & sample transactions)
            SampleDataSeeder::class,

            // 5. Foundation data (only if needed)
            // PondasiAwalSeeder::class,

            // 6. Master data seeders (only if needed)
            // MasterDataSeeder::class,

            // 6. Business transaction data (only if needed)
            // BusinessDataSeeder::class,

            // 7. Sample business data (only if needed)
            // FakeSeed::class,
            // HRSeeder::class,

            // 8. Transaction data (optional)
            // PaymentsSeeder::class,
            // ManufacturingSeeder::class,
        ]);

        $this->command->info('🎉 Database seeding completed successfully!');
        $this->command->info('📧 Super Admin Email: superadmin@erp.test');
        $this->command->info('🔑 Default Password: password');
        $this->command->info('🏢 Companies created with sample users');
        $this->command->info('👥 Use respective company domain emails to login');
    }
}
