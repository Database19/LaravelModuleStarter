<?php

namespace Modules\ProjectManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class ReportController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('projects::reports.index');
    }

    /**
     * Show project summary report.
     */
    public function summary()
    {
        return view('projects::reports.summary');
    }

    /**
     * Show time tracking report.
     */
    public function timeTracking()
    {
        return view('projects::reports.time-tracking');
    }

    /**
     * Show project budget report.
     */
    public function budget()
    {
        return view('projects::reports.budget');
    }

    /**
     * Show project progress report.
     */
    public function progress()
    {
        return view('projects::reports.progress');
    }

    /**
     * Show team performance report.
     */
    public function teamPerformance()
    {
        return view('projects::reports.team-performance');
    }

    /**
     * Export project reports.
     */
    public function export(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:summary,time_tracking,budget,progress,team_performance',
            'format' => 'required|in:pdf,excel,csv',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        // Process report export
        $filePath = storage_path('exports/project_report.' . $request->format);
        return response()->download($filePath);
    }
}
