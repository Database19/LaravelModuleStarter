<?php

namespace App\Providers;

use App\Models\MenuItem;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    public function boot(): void
{
    View::composer('*', function ($view) {
        if (auth()->check()) {
            $user = auth()->user();
            $company = $user->company;

            // Logika dijalankan langsung tanpa Cache

            $allMenuItems = MenuItem::whereNull('parent_id')
                ->with('children')
                ->orderBy('group')
                ->orderBy('order')
                ->get();

            if (!$company) {
                // Logika untuk Super Admin
                $filteredMenuItems = $allMenuItems->filter(function ($menu) use ($user) {
                    return $menu->permission_name === null || $user->can($menu->permission_name);
                });
            } else {
                // Logika untuk user perusahaan
                $subscribedModules = $company->subscriptions()->pluck('module_key')->toArray();

                $filteredMenuItems = $allMenuItems->filter(function ($menu) use ($subscribedModules, $user) {

                    if (empty($menu->permission_name)) {
                        return true;
                    }

                    $moduleKey = explode('-', $menu->permission_name)[1] ?? null;
                    $isSubscribed = in_array($moduleKey, $subscribedModules);
                    $hasPermission = $user->can($menu->permission_name);

                    return $isSubscribed && $hasPermission;
                });
            }

            $groupedMenuItems = $filteredMenuItems->groupBy('group');
            $view->with('dynamicMenuItems', $groupedMenuItems);
        }
    });
}
}
