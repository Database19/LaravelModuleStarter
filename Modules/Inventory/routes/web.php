<?php

use Illuminate\Support\Facades\Route;
use Modules\Inventory\Http\Controllers\BrandController;
use Modules\Inventory\Http\Controllers\ProductCategoryController;
use Modules\Inventory\Http\Controllers\ProductController;
use Modules\Inventory\Http\Controllers\StockMovementController;
use Modules\Inventory\Http\Controllers\UnitController;
use Modules\Inventory\Http\Controllers\StockController;
use Modules\Inventory\Http\Controllers\MovementController;
use Modules\Inventory\Http\Controllers\AdjustmentController;
use Modules\Inventory\Http\Controllers\InventoryController;

Route::middleware(['auth', 'permission:manage-inventory|manage-companies|super-admin-access'])->prefix('inventory')->name('inventory.')->group(function () {
    Route::resource('inventory', InventoryController::class)->names('inventory');

    // Stock Management Routes
    Route::resource('stock', StockController::class);
    Route::resource('movements', MovementController::class);
    Route::resource('adjustments', AdjustmentController::class);

    // Product Management Routes
    Route::resource('product-categories', ProductCategoryController::class);
    Route::resource('products', ProductController::class);

    // Stock movements alias
    Route::get('stock-movements', [MovementController::class, 'index'])->name('stock-movements.index');

    Route::resource('units', UnitController::class);
    Route::resource('brands', BrandController::class);
});
