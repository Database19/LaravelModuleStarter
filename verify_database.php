<?php

require_once 'vendor/autoload.php';

use App\Models\Company;
use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use App\Models\MenuItem;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Boot Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "========================================\n";
echo "  📊 DATABASE VERIFICATION REPORT\n";
echo "========================================\n\n";

// Count basic data
echo "📈 SUMMARY:\n";
echo "Companies: " . Company::count() . "\n";
echo "Users: " . User::count() . "\n";
echo "Departments: " . Department::count() . "\n";
echo "Positions: " . Position::count() . "\n";
echo "Roles: " . Role::count() . "\n";
echo "Permissions: " . Permission::count() . "\n";
echo "Menu Items: " . MenuItem::count() . "\n\n";

// Show companies
echo "🏢 COMPANIES:\n";
Company::with('users')->get(['id', 'name', 'domain'])->each(function($company) {
    echo "  • {$company->name} - {$company->domain}\n";
    echo "    Users: {$company->users->count()}\n";
    if ($company->users->count() > 0) {
        echo "    Company Users:\n";
        $company->users->each(function($user) {
            echo "      - {$user->name} ({$user->email}) - Roles: " . $user->roles->pluck('name')->join(', ') . "\n";
        });
    }
});
echo "\n";

// Show super admin
echo "👑 SUPER ADMIN:\n";
$superAdmin = User::where('is_super_admin', true)->first();
if ($superAdmin) {
    echo "  • Name: {$superAdmin->name}\n";
    echo "  • Email: {$superAdmin->email}\n";
    echo "  • Company: " . $superAdmin->company->name . "\n";
    echo "  • Roles: " . $superAdmin->roles->pluck('name')->join(', ') . "\n";
} else {
    echo "  ❌ No super admin found!\n";
}
echo "\n";

// Show roles
echo "🎭 ROLES:\n";
Role::with(['permissions', 'users'])->get(['id', 'name'])->each(function($role) {
    echo "  • {$role->name} (Permissions: {$role->permissions->count()}, Users: {$role->users->count()})\n";
    if ($role->permissions->count() > 0) {
        echo "    Permissions: " . $role->permissions->pluck('name')->join(', ') . "\n";
    }
});
echo "\n";

// Show menu groups
echo "📋 MENU GROUPS:\n";
$menuGroups = MenuItem::whereNull('parent_id')->get(['group', 'name']);
$menuGroups->groupBy('group')->each(function($menus, $group) {
    echo "  • {$group}:\n";
    $menus->each(function($menu) {
        $submenuCount = MenuItem::where('parent_id', $menu->id)->count();
        echo "    - {$menu->name}" . ($submenuCount > 0 ? " ({$submenuCount} submenus)" : "") . "\n";
    });
});
echo "\n";

echo "========================================\n";
echo "  ✅ VERIFICATION COMPLETE\n";
echo "========================================\n";
echo "📧 Super Admin Login: superadmin@erp.test\n";
echo "🔑 Password: password\n";
echo "🌐 URL: http://localhost:8000\n";
echo "========================================\n";
