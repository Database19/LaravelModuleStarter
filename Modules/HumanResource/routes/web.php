<?php

use Illuminate\Support\Facades\Route;
use Modules\HumanResource\Http\Controllers\EmployeeController;
use Modules\HumanResource\Http\Controllers\PayrollController;
use Modules\HumanResource\Http\Controllers\AttendanceController;
use Modules\HumanResource\Http\Controllers\LeaveController;
use Modules\HumanResource\Http\Controllers\PerformanceController;
use Modules\HumanResource\Http\Controllers\RecruitmentController;

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

Route::middleware(['auth', 'permission:manage-hr|manage-companies|super-admin-access'])->prefix('humanresource')->name('humanresource.')->group(function () {
    // Employee Management
    Route::resource('employees', EmployeeController::class);

    // Payroll Management
    Route::resource('payrolls', PayrollController::class)->only(['index', 'create', 'store']);
    Route::post('payrolls/process', [PayrollController::class, 'process'])->name('payrolls.process');

    // Attendance Management
    Route::resource('attendance', AttendanceController::class);

    // Leave Management
    Route::resource('leave', LeaveController::class);
    Route::patch('leave/{id}/approve', [LeaveController::class, 'approve'])->name('leave.approve');
    Route::patch('leave/{id}/reject', [LeaveController::class, 'reject'])->name('leave.reject');

    // Performance Management
    Route::resource('performance', PerformanceController::class);

    // Recruitment Management
    Route::resource('recruitment', RecruitmentController::class);
    Route::patch('recruitment/{id}/close', [RecruitmentController::class, 'close'])->name('recruitment.close');
    Route::get('recruitment/{id}/applications', [RecruitmentController::class, 'applications'])->name('recruitment.applications');
});
