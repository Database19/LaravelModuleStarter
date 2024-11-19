<?php

use Illuminate\Support\Facades\Route;
use Modules\Inventory\Http\Controllers\InventoryController;

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

Route::middleware(['auth', 'role:Admin|Inventory Clerk'])->group(function () {
    Route::resource('inventory', InventoryController::class)->names('inventory');
    // Route::get('/', [InventoryController::class, 'index'])->name('inventory.index');
});
