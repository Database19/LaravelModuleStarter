<?php

use Illuminate\Support\Facades\Route;
use Modules\Purchasing\Http\Controllers\PurchaseOrderController;
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

Route::middleware(['auth', 'permission:manage-purchasing|manage-companies|super-admin-access'])->prefix('purchasing')->name('purchasing.')->group(function () {
    // Route::resource('/', PurchasingController::class);
    Route::post('purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');
    Route::resource('purchase-orders', PurchaseOrderController::class);
});
