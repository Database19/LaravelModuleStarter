<?php

use Illuminate\Support\Facades\Route;
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
Route::middleware(['auth', 'role:Admin|Inventory Manager'])->group(function () {
    Route::resource('warehouse', WarehouseController::class)->names('warehouse');
});

// Route::group([], function () {
//     Route::resource('warehouse', WarehouseController::class)->names('warehouse');
// });
