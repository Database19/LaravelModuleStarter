<?php

namespace Modules\DocumentManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class TemplateController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('documents::templates.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('documents::templates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'template_file' => 'required|file|mimes:doc,docx,pdf,xls,xlsx',
            'category_id' => 'required|exists:document_categories,id',
            'is_active' => 'boolean',
        ]);

        // Process template creation
        return redirect()->route('documents.templates.index')
            ->with('success', 'Template created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('documents::templates.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('documents::templates.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'template_file' => 'nullable|file|mimes:doc,docx,pdf,xls,xlsx',
            'category_id' => 'required|exists:document_categories,id',
            'is_active' => 'boolean',
        ]);

        // Process template update
        return redirect()->route('documents.templates.index')
            ->with('success', 'Template updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Process template deletion
        return redirect()->route('documents.templates.index')
            ->with('success', 'Template deleted successfully.');
    }
}
