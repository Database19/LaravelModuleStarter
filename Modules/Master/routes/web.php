<?php

use Illuminate\Support\Facades\Route;
use Modules\Master\Http\Controllers\BomController;
use Modules\Master\Http\Controllers\CustomerController;
use Modules\Master\Http\Controllers\MachineController;
use Modules\Master\Http\Controllers\MasterController;
use Modules\Master\Http\Controllers\MaterialController;
use Modules\Master\Http\Controllers\ProductController;
use Modules\Master\Http\Controllers\SupplierController;
use Modules\Master\Http\Controllers\WarehouseController;
use Modules\Master\Http\Controllers\WorkCenterController;

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
    Route::resource('masterdata', MasterController::class)->names('masterdata');

    Route::prefix('masterdata')->group(function () {
        Route::resource('material', MaterialController::class)->names('masterdata.material');
        Route::resource('product', ProductController::class)->names('masterdata.product');
        Route::resource('supplier', SupplierController::class)->names('masterdata.supplier');
        Route::resource('customer', CustomerController::class)->names('masterdata.customer');
        Route::resource('warehouse', WarehouseController::class)->names('masterdata.warehouse');
        Route::resource('workcenter', WorkCenterController::class)->names('masterdata.work-center');
        Route::resource('machine', MachineController::class)->names('masterdata.machine');
        Route::resource('bom', BomController::class)->names('masterdata.bom');
    });
});
