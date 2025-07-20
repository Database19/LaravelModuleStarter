<?php

use Illuminate\Support\Facades\Route;
use Modules\Inventory\Http\Controllers\ProductCategoryController;
use Modules\Inventory\Http\Controllers\ProductController;
use Modules\Inventory\Http\Controllers\StockMovementController;

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

Route::middleware(['auth', 'role:Admin|Inventory Manager'])->prefix('inventory')->name('inventory.')->group(function () {
    // Route::resource('inventory', InventoryController::class)->names('inventory');
    Route::resource('product-categories', ProductCategoryController::class);
    Route::resource('products', ProductController::class);

    Route::get('stock-movements', [StockMovementController::class, 'index'])->name('stock-movements.index');
});
