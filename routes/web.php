<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/debug', function () {
    return [
        'host' => request()->getHost(),
        'current_tenant' => \Spatie\Multitenancy\Models\Tenant::checkCurrent() ? \Spatie\Multitenancy\Models\Tenant::current()->id : 'none',
        'auth_user' => Auth::check() ? Auth::user()->email : 'guest',
        'is_super_admin' => Auth::check() ? (Auth::user()->is_super_admin ?? false) : false,
        'companies' => \App\Models\Company::count(),
    ];
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/test', function () {
    return "test";
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [UserController::class, 'editModal'])->name('profile.edit');
    Route::post('/profile', [UserController::class, 'updateModal'])->name('profile.update');

    // Super Admin Routes
    Route::prefix('superadmin')->name('superadmin.')->middleware('can:manage-acl')->group(function () {
        Route::get('/companies', [SuperAdminController::class, 'companies'])->name('companies.index');
        Route::get('/switch-company', [SuperAdminController::class, 'switchCompany'])->name('switch-company');
        Route::post('/switch-company', [SuperAdminController::class, 'switchToCompany'])->name('switch-company.post');
        Route::get('/users', [SuperAdminController::class, 'globalUsers'])->name('users.index');
        Route::get('/monitor', [SuperAdminController::class, 'monitor'])->name('monitor.index');
    });

    Route::prefix('admin')->name('admin.')->middleware('permission:manage-acl')->group(function () {

        // Rute untuk Manajemen Menu
        Route::resource('menu', MenuController::class);

        // Rute untuk Manajemen Pengguna
        Route::resource('users', UserController::class);

        // Rute untuk Manajemen Peran
        Route::resource('roles', RoleController::class);

    });

    // Reports Routes
    Route::prefix('reports')->name('reports.')->middleware('can:manage-acl')->group(function () {
        Route::get('/executive', [ReportsController::class, 'executive'])->name('executive.index');
        Route::get('/sales', [ReportsController::class, 'sales'])->name('sales.index');
        Route::get('/financial', [ReportsController::class, 'financial'])->name('financial.index');
        Route::get('/inventory', [ReportsController::class, 'inventory'])->name('inventory.index');
        Route::get('/hr', [ReportsController::class, 'hr'])->name('hr.index');
        Route::get('/custom', [ReportsController::class, 'custom'])->name('custom.index');
    });

    // Settings Routes
    Route::prefix('settings')->name('settings.')->middleware('can:manage-acl')->group(function () {
        Route::get('/users', [SettingsController::class, 'users'])->name('users.index');
        Route::get('/roles', [SettingsController::class, 'roles'])->name('roles.index');
        Route::get('/menus', [SettingsController::class, 'menus'])->name('menus.index');
        Route::get('/company', [SettingsController::class, 'company'])->name('company.index');
        Route::get('/accounting', [SettingsController::class, 'accounting'])->name('accounting.index');
        Route::get('/email', [SettingsController::class, 'email'])->name('email.index');
        Route::get('/backup', [SettingsController::class, 'backup'])->name('backup.index');
        Route::get('/logs', [SettingsController::class, 'logs'])->name('logs.index');
    });
});

// Test routes (not in production)
if (app()->environment(['local', 'testing'])) {
    require __DIR__.'/test.php';
}
