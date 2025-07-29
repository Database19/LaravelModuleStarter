<?php

use Illuminate\Support\Facades\Route;
use Modules\Warehouse\Http\Controllers\StockCountController;
use Modules\Warehouse\Http\Controllers\StockTransferController;
use Modules\Warehouse\Http\Controllers\WarehouseController;
use Modules\Warehouse\Http\Controllers\TransferController;
use Modules\Warehouse\Http\Controllers\CountController;

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
Route::middleware(['auth', 'permission:manage-warehouse|manage-companies|super-admin-access'])->prefix('warehouse')->name('warehouse.')->group(function () {
    // General warehouse route
    Route::get('/', [WarehouseController::class, 'index'])->name('index');

    // Warehouse management
    Route::resource('warehouses', WarehouseController::class)->except('show');

    // Stock transfers
    Route::resource('transfers', TransferController::class);
    Route::post('transfers/{transfer}/ship', [StockTransferController::class, 'ship'])->name('transfers.ship');
    Route::post('transfers/{transfer}/receive', [StockTransferController::class, 'receive'])->name('transfers.receive');

    // Stock counts
    Route::resource('counts', CountController::class);
    Route::post('counts/{count}/process', [StockCountController::class, 'process'])->name('counts.process');
});
