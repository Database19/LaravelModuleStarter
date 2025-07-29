 <?php

use Illuminate\Support\Facades\Route;
use Modules\MasterData\Http\Controllers\CustomerController;
use Modules\MasterData\Http\Controllers\EmployeesController;
use Modules\MasterData\Http\Controllers\SupplierController;
use Modules\MasterData\Http\Controllers\BrandController;
use Modules\MasterData\Http\Controllers\UnitController;
use Modules\MasterData\Http\Controllers\ProductCategoryController;
use Modules\MasterData\Http\Controllers\ProductController;
use Modules\MasterData\Http\Controllers\WarehouseController;
use Modules\MasterData\Http\Controllers\DepartmentController;
use Modules\MasterData\Http\Controllers\PositionController;

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

Route::middleware(['auth', 'permission:manage-master-data|manage-companies|super-admin-access'])->prefix('master-data')->name('master-data.')->group(function () {

    // Basic Master Data (accessible to all authenticated users)
    Route::resource('brands', BrandController::class);
    Route::resource('units', UnitController::class);
    Route::resource('product-categories', ProductCategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('warehouses', WarehouseController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('positions', PositionController::class);

    // Legacy routes (for backward compatibility)
    Route::resource('customer', CustomerController::class);
    Route::resource('supplier', SupplierController::class);
    Route::resource('employees', EmployeesController::class);
});
