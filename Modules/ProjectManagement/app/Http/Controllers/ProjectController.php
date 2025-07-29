<?php

namespace Modules\ProjectManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class ProjectController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('projects::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('projects::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:customers,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'budget' => 'required|numeric|min:0',
            'status' => 'required|in:planning,active,on_hold,completed,cancelled',
        ]);

        // Process project creation
        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('projects::show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('projects::edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:customers,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'budget' => 'required|numeric|min:0',
            'status' => 'required|in:planning,active,on_hold,completed,cancelled',
        ]);

        // Process project update
        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Process project deletion
        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}
