<?php

use Illuminate\Support\Facades\Route;
use Modules\Manufacturing\Http\Controllers\ManufacturingController;

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
Route::middleware(['auth', 'role:Admin|Production Manager'])->group(function () {
    Route::resource('manufacturing', ManufacturingController::class)->names('manufacturing');
});
// Route::group([], function () {
//     Route::resource('manufacturing', ManufacturingController::class)->names('manufacturing');
// });
