<?php

use Illuminate\Support\Facades\Route;
use Modules\DocumentManagement\Http\Controllers\DocumentManagementController;

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

Route::middleware(['auth', 'role:Admin|Project Manager'])->group(function () {
    Route::resource('documentmanagement', DocumentManagementController::class)->names('documentmanagement');
});
// Route::group([], function () {
//     Route::resource('documentmanagement', DocumentManagementController::class)->names('documentmanagement');
// });
