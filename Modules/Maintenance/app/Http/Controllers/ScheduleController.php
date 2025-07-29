<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class ScheduleController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('maintenance::schedule.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('maintenance::schedule.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'equipment_id' => 'required|exists:equipment,id',
            'frequency' => 'required|in:daily,weekly,monthly,quarterly,annually',
            'next_due_date' => 'required|date',
            'description' => 'required|string',
            'estimated_hours' => 'required|numeric|min:0',
        ]);

        // Process maintenance schedule creation
        return redirect()->route('maintenance.schedule.index')
            ->with('success', 'Maintenance schedule created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('maintenance::schedule.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('maintenance::schedule.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'equipment_id' => 'required|exists:equipment,id',
            'frequency' => 'required|in:daily,weekly,monthly,quarterly,annually',
            'next_due_date' => 'required|date',
            'description' => 'required|string',
            'estimated_hours' => 'required|numeric|min:0',
        ]);

        // Process maintenance schedule update
        return redirect()->route('maintenance.schedule.index')
            ->with('success', 'Maintenance schedule updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Process maintenance schedule deletion
        return redirect()->route('maintenance.schedule.index')
            ->with('success', 'Maintenance schedule deleted successfully.');
    }
}
