<?php

use Illuminate\Support\Facades\Route;
use Modules\HumanResource\Http\Controllers\EmployeeController;
use Modules\HumanResource\Http\Controllers\PayrollController;

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

Route::middleware(['auth', 'role:Admin|HR Manager'])->prefix('humanresource')->name('humanresource.')->group(function () {
    // Route::resource('humanresource', HumanResourceController::class)->names('humanresource');
    Route::resource('employees', EmployeeController::class);

    Route::resource('payrolls', PayrollController::class)->only(['index', 'create', 'store']);

    // Rute khusus untuk memproses gaji satu periode
    Route::post('payrolls/process', [PayrollController::class, 'process'])->name('payrolls.process');
});

// Route::group([], function () {
//     Route::resource('humanresource', HumanResourceController::class)->names('humanresource');
// });
