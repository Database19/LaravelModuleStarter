<?php

use Illuminate\Support\Facades\Route;
use Modules\Manufacturing\Http\Controllers\BomController;
use Modules\Manufacturing\Http\Controllers\ManufacturingController;
use Modules\Manufacturing\Http\Controllers\ManufacturingOrderController;
use Modules\Manufacturing\Http\Controllers\OrderController;
use Modules\Manufacturing\Http\Controllers\WorkcenterController;

Route::middleware(['auth', 'permission:manage-manufacturing|manage-companies|super-admin-access'])->prefix('manufacturing')->name('manufacturing.')->group(function () {
    // Manufacturing Orders
    Route::resource('orders', OrderController::class);
    Route::resource('manufacturing-orders', ManufacturingOrderController::class);

    // Bill of Materials
    Route::get('boms/{bom}/details', [BomController::class, 'getBomDetails'])->name('boms.details');
    Route::resource('boms', BomController::class);

    // Workcenters
    Route::resource('workcenters', WorkcenterController::class);
});
