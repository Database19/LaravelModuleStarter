<?php

use Illuminate\Support\Facades\Route;
use Modules\Warehouse\Http\Controllers\StockCountController;
use Modules\Warehouse\Http\Controllers\StockTransferController;
use Modules\Warehouse\Http\Controllers\WarehouseController;

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
Route::middleware(['auth', 'role:Admin|Inventory Manager'])->prefix('warehouse')->name('warehouse.')->group(function () {
    Route::resource('warehouses', WarehouseController::class)->except('show');

    Route::resource('transfers', StockTransferController::class);
    Route::post('transfers/{transfer}/ship', [StockTransferController::class, 'ship'])->name('transfers.ship');
    Route::post('transfers/{transfer}/receive', [StockTransferController::class, 'receive'])->name('transfers.receive');

    Route::resource('counts', StockCountController::class);
    Route::post('counts/{count}/process', [StockCountController::class, 'process'])->name('counts.process');
});

// Route::group([], function () {
//     Route::resource('warehouse', WarehouseController::class)->names('warehouse');
// });
