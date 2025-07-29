<?php

use Illuminate\Support\Facades\Route;
use Modules\ProjectManagement\Http\Controllers\ProjectManagementController;
use Modules\ProjectManagement\Http\Controllers\ProjectController;
use Modules\ProjectManagement\Http\Controllers\TaskController;
use Modules\ProjectManagement\Http\Controllers\TimetrackController;
use Modules\ProjectManagement\Http\Controllers\ReportController;

Route::middleware(['auth', 'permission:manage-project|manage-companies|super-admin-access'])->prefix('projectmanagement')->name('projectmanagement.')->group(function () {
    // Main projects route
    Route::resource('projects', ProjectController::class);

    // Tasks
    Route::resource('tasks', TaskController::class);

    // Time tracking
    Route::resource('timetrack', TimetrackController::class);

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/summary', [ReportController::class, 'summary'])->name('summary');
        Route::get('/time-tracking', [ReportController::class, 'timeTracking'])->name('time_tracking');
        Route::get('/budget', [ReportController::class, 'budget'])->name('budget');
        Route::get('/progress', [ReportController::class, 'progress'])->name('progress');
        Route::get('/team-performance', [ReportController::class, 'teamPerformance'])->name('team_performance');
        Route::post('/export', [ReportController::class, 'export'])->name('export');
    });
});
