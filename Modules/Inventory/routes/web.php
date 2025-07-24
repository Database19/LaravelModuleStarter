<?php

use Illuminate\Support\Facades\Route;
use Modules\Inventory\Http\Controllers\BrandController;
use Modules\Inventory\Http\Controllers\ProductCategoryController;
use Modules\Inventory\Http\Controllers\ProductController;
use Modules\Inventory\Http\Controllers\StockMovementController;
use Modules\Inventory\Http\Controllers\UnitController;

Route::middleware(['auth', 'role:Admin|Inventory Manager'])->prefix('inventory')->name('inventory.')->group(function () {
    // Route::resource('inventory', InventoryController::class)->names('inventory');
    Route::resource('product-categories', ProductCategoryController::class);
    Route::resource('products', ProductController::class);

    Route::get('stock-movements', [StockMovementController::class, 'index'])->name('stock-movements.index');

    Route::resource('units', UnitController::class);
    Route::resource('brands', BrandController::class);
});
