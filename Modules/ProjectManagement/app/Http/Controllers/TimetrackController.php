<?php

namespace Modules\ProjectManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class TimetrackController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('projects::timetrack.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('projects::timetrack.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'task_id' => 'nullable|exists:tasks,id',
            'user_id' => 'required|exists:users,id',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after:start_time',
            'description' => 'required|string|max:500',
            'billable' => 'boolean',
        ]);

        // Process time tracking creation
        return redirect()->route('projects.timetrack.index')
            ->with('success', 'Time entry created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('projects::timetrack.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('projects::timetrack.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'task_id' => 'nullable|exists:tasks,id',
            'user_id' => 'required|exists:users,id',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after:start_time',
            'description' => 'required|string|max:500',
            'billable' => 'boolean',
        ]);

        // Process time tracking update
        return redirect()->route('projects.timetrack.index')
            ->with('success', 'Time entry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Process time tracking deletion
        return redirect()->route('projects.timetrack.index')
            ->with('success', 'Time entry deleted successfully.');
    }
}
