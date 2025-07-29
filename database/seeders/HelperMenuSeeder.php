<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuItem;

class HelperMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Helper main menu
        $helperMenu = MenuItem::firstOrCreate([
            'name' => 'Helper',
            'group' => 'Helper',
            'route' => 'helper.index',
            'icon_svg' => '<i class="fas fa-magic"></i>',
            'order' => 999,
            'parent_id' => null,
            'permission_name' => 'manage-helper',
            'status' => 1
        ]);

        // Create CRUD Generator submenu
        MenuItem::firstOrCreate([
            'name' => 'CRUD Generator',
            'group' => 'Helper',
            'route' => 'helper.crud-helper.index',
            'icon_svg' => '<i class="fas fa-code"></i>',
            'order' => 1,
            'parent_id' => $helperMenu->id,
            'permission_name' => 'manage-helper',
            'status' => 1
        ]);

        $this->command->info('Helper menu items created successfully!');
    }
}
