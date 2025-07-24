<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/test', function () {
    return "test";
});

Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->name('admin.')->middleware('can:manage-acl')->group(function () {

        // Rute untuk Manajemen Menu
        Route::resource('menu', MenuController::class);

        // Rute untuk Manajemen Pengguna
        Route::resource('users', UserController::class);

        // Rute untuk Manajemen Peran
        Route::resource('roles', RoleController::class);

    });
});
