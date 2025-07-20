<?php

use Illuminate\Support\Facades\Route;
use Modules\MasterData\Http\Controllers\CustomerController;
use Modules\MasterData\Http\Controllers\EmployeesController;
use Modules\MasterData\Http\Controllers\SupplierController;

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

Route::middleware(['auth', 'role:Admin'])->prefix('master-data')->name('master-data.')->group(function () {
    Route::resource('customer', CustomerController::class);
    Route::resource('supplier', SupplierController::class);
    Route::resource('employees', EmployeesController::class);
});
