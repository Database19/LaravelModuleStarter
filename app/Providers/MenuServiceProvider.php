<?php

namespace App\Providers;

use App\Models\MenuItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    public function boot(): void
{
    View::composer('*', function ($view) {
        if (Auth::check()) {
            // Get the authenticated user and their company
            $user = Auth::user();
            $company = $user->company;

            // Get all menu items (no tenant restriction since MenuItem doesn't use company_id)
            $allMenuItems = MenuItem::whereNull('parent_id')
                ->with('children')
                ->orderBy('group')
                ->orderBy('order')
                ->get();

            if ($user->is_super_admin) {
                // Super admin can see all menus
                $filteredMenuItems = $allMenuItems->filter(function ($menu) use ($user) {
                    return $menu->permission_name === null || $user->can($menu->permission_name) || $menu->permission_name === 'super-admin';
                });
            } else {
                if (!$company) {
                    $filteredMenuItems = $allMenuItems->filter(function ($menu) use ($user) {
                        return $menu->permission_name === null || $user->can($menu->permission_name);
                    });
                } else {
                    $subscribedModules = $company->subscriptions()->pluck('module_key')->toArray();

                    $filteredMenuItems = $allMenuItems->filter(function ($menu) use ($subscribedModules, $user) {

                        if (empty($menu->permission_name)) {
                            return true;
                        }

                        $moduleKey = explode('-', $menu->permission_name)[1] ?? null;
                        $isSubscribed = in_array($moduleKey, $subscribedModules);
                        $hasPermission = $user->can($menu->permission_name) || $menu->permission_name === 'super-admin';

                        return $isSubscribed && $hasPermission;
                    });
                }
            }

            $groupedMenuItems = $filteredMenuItems->groupBy('group');
            $view->with('dynamicMenuItems', $groupedMenuItems);
        }
    });
}
}
