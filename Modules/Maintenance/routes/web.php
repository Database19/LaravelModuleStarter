<?php

use Illuminate\Support\Facades\Route;
use Modules\Maintenance\Http\Controllers\MaintenanceController;
use Modules\Maintenance\Http\Controllers\EquipmentController;
use Modules\Maintenance\Http\Controllers\OrderController;
use Modules\Maintenance\Http\Controllers\ScheduleController;

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

Route::middleware(['auth', 'permission:manage-maintenance|manage-companies|super-admin-access'])->prefix('maintenance')->name('maintenance.')->group(function () {
    Route::resource('maintenance', MaintenanceController::class)->names('maintenance');

    // Equipment Management
    Route::resource('equipment', EquipmentController::class);

    // Maintenance Orders
    Route::resource('orders', OrderController::class);

    // Maintenance Schedule
    Route::resource('schedule', ScheduleController::class);
});
