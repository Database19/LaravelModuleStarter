<?php

use Illuminate\Support\Facades\Route;
use Modules\CRM\Http\Controllers\LeadController;
use Modules\CRM\Http\Controllers\OpportunityController;

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
Route::middleware(['auth', 'role:Admin|Sales Executive'])->prefix('crm')->name('crm.')->group(function () {
    // Route::resource('crm', CRMController::class)->names('crm');
    Route::resource('leads', LeadController::class);
    Route::post('leads/{lead}/convert', [LeadController::class, 'convert'])->name('leads.convert');

    Route::resource('opportunities', OpportunityController::class);
});
// Route::group([], function () {
//     Route::resource('crm', CRMController::class)->names('crm');
// });
