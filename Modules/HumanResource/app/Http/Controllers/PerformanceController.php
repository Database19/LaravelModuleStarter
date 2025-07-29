<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class PerformanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $performances = collect([
            (object)[
                'id' => 1,
                'employee_name' => 'John Doe',
                'review_period' => 'Q2 2025',
                'overall_score' => 4.2,
                'status' => 'Completed',
                'reviewer' => 'Jane Manager'
            ],
            (object)[
                'id' => 2,
                'employee_name' => 'Jane Smith',
                'review_period' => 'Q2 2025',
                'overall_score' => 4.5,
                'status' => 'In Progress',
                'reviewer' => 'John Manager'
            ]
        ]);

        return view('humanresource::performance.index', compact('performances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('humanresource::performance.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'review_period' => 'required|string',
            'goals' => 'required|string',
            'achievements' => 'nullable|string',
            'areas_for_improvement' => 'nullable|string',
            'overall_score' => 'required|numeric|min:1|max:5'
        ]);

        return redirect()->route('humanresource.performance.index')
            ->with('success', 'Performance review created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $performance = (object)[
            'id' => $id,
            'employee_name' => 'John Doe',
            'review_period' => 'Q2 2025',
            'overall_score' => 4.2,
            'status' => 'Completed',
            'reviewer' => 'Jane Manager',
            'goals' => 'Increase sales by 15%, improve customer satisfaction',
            'achievements' => 'Exceeded sales target by 20%, received positive customer feedback',
            'areas_for_improvement' => 'Time management, technical skills',
            'feedback' => 'Great performance overall, keep up the good work',
            'review_date' => '2025-07-15'
        ];

        return view('humanresource::performance.show', compact('performance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $performance = (object)[
            'id' => $id,
            'employee_id' => 1,
            'review_period' => 'Q2 2025',
            'goals' => 'Increase sales by 15%, improve customer satisfaction',
            'achievements' => 'Exceeded sales target by 20%, received positive customer feedback',
            'areas_for_improvement' => 'Time management, technical skills',
            'overall_score' => 4.2,
            'feedback' => 'Great performance overall, keep up the good work'
        ];

        return view('humanresource::performance.edit', compact('performance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'review_period' => 'required|string',
            'goals' => 'required|string',
            'achievements' => 'nullable|string',
            'areas_for_improvement' => 'nullable|string',
            'overall_score' => 'required|numeric|min:1|max:5'
        ]);

        return redirect()->route('humanresource.performance.index')
            ->with('success', 'Performance review updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        return redirect()->route('humanresource.performance.index')
            ->with('success', 'Performance review deleted successfully.');
    }
}
