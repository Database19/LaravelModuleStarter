<?php

namespace Modules\Helpdesk\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class CategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('helpdesk::categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('helpdesk::categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ]);

        // Process category creation
        return redirect()->route('helpdesk.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('helpdesk::categories.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('helpdesk::categories.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ]);

        // Process category update
        return redirect()->route('helpdesk.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Process category deletion
        return redirect()->route('helpdesk.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
