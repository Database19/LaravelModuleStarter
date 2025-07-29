<?php

use Illuminate\Support\Facades\Route;
use Modules\QualityControl\Http\Controllers\QualityControlController;
use Modules\QualityControl\Http\Controllers\QualityCheckController;
use Modules\QualityControl\Http\Controllers\QualityStandardController;
use Modules\QualityControl\Http\Controllers\QualityInspectionController;

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

Route::middleware(['auth', 'permission:manage-quality-control|manage-companies|super-admin-access'])->prefix('qualitycontrol')->name('qualitycontrol.')->group(function () {
    Route::get('/', [QualityControlController::class, 'index'])->name('index');

    // Quality Checks
    Route::resource('checks', QualityCheckController::class);

    // Quality Standards
    Route::resource('standards', QualityStandardController::class);

    // Quality Inspections (for detailed inspection management)
    Route::resource('inspections', QualityInspectionController::class);

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [QualityControlController::class, 'reports'])->name('index');
        Route::get('/defect-analysis', [QualityControlController::class, 'defectAnalysis'])->name('defect_analysis');
        Route::get('/supplier-performance', [QualityControlController::class, 'supplierPerformance'])->name('supplier_performance');
    });
});
