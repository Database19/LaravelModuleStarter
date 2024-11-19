<?php

use Illuminate\Support\Facades\Route;
use Modules\Maintenance\Http\Controllers\MaintenanceController;

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

Route::middleware(['auth', 'role:Admin|Maintenance'])->group(function () {
    Route::resource('maintenance', MaintenanceController::class)->names('maintenance');
});
