<?php

use Illuminate\Support\Facades\Route;
use Modules\Configuration\Http\Controllers\ConfigurationController;
use Modules\Configuration\Http\Controllers\ModuleSettingController;
use Modules\Configuration\Http\Controllers\PermissionController;
use Modules\Configuration\Http\Controllers\RolesController;
use Modules\Configuration\Http\Controllers\SystemSettingController;
use Modules\Configuration\Http\Controllers\UserManagementController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth', 'role:Admin|Maintenance'])->group(function () {
    Route::resource('configuration', ConfigurationController::class)->names('configuration');

    Route::prefix('configuration')->group(function () {
        Route::resource('module-setting', ModuleSettingController::class)->names('configuration.module-setting');
        Route::resource('system-setting', SystemSettingController::class)->names('configuration.system-setting');
        Route::resource('roles', RolesController::class)->names('configuration.roles');
        Route::resource('permission', PermissionController::class)->names('configuration.permission');
        Route::resource('user-management', UserManagementController::class)->names('configuration.user-management');
    });
});
