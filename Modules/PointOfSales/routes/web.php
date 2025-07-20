<?php

use Illuminate\Support\Facades\Route;
use Modules\PointOfSales\Http\Controllers\PointOfSalesController;

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
Route::middleware(['auth', 'role:Admin|POS Cashier'])->group(function () {
    Route::resource('pointofsales', PointOfSalesController::class)->names('pointofsales');
});

// Route::group([], function () {
//     Route::resource('pointofsales', PointOfSalesController::class)->names('pointofsales');
// });
