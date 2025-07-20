<?php

namespace App\Providers;

use App\Models\MenuItem;
use Illuminate\Support\Facades\Cache; // Gunakan Cache untuk performa
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Gunakan View Composer untuk berbagi data menu ke semua view
        View::composer('*', function ($view) {
            // Gunakan cache agar query ini tidak dijalankan di setiap request
            $dynamicMenuItems = Cache::remember('dynamic_menu_items', 60, function () {
                return MenuItem::whereNull('parent_id') // 1. Ambil hanya parent menu
                    ->with('children') // 2. Eager load submenu-nya
                    ->orderBy('group')
                    ->orderBy('order')
                    ->get()
                    ->groupBy('group'); // 3. Kelompokkan berdasarkan grup
            });

            $view->with('dynamicMenuItems', $dynamicMenuItems);
        });
    }
}
