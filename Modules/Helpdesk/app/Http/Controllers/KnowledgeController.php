<?php

namespace Modules\Helpdesk\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class KnowledgeController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('helpdesk::knowledge.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('helpdesk::knowledge.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:knowledge_categories,id',
            'is_public' => 'boolean',
            'tags' => 'nullable|string',
        ]);

        // Process knowledge article creation
        return redirect()->route('helpdesk.knowledge.index')
            ->with('success', 'Knowledge article created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('helpdesk::knowledge.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('helpdesk::knowledge.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:knowledge_categories,id',
            'is_public' => 'boolean',
            'tags' => 'nullable|string',
        ]);

        // Process knowledge article update
        return redirect()->route('helpdesk.knowledge.index')
            ->with('success', 'Knowledge article updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Process knowledge article deletion
        return redirect()->route('helpdesk.knowledge.index')
            ->with('success', 'Knowledge article deleted successfully.');
    }
}
