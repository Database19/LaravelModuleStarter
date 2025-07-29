<?php

namespace Modules\DocumentManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class LibraryController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('documents::library.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('documents::library.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|max:10240',
            'category_id' => 'required|exists:document_categories,id',
            'is_public' => 'boolean',
        ]);

        // Process document upload
        return redirect()->route('documents.library.index')
            ->with('success', 'Document uploaded successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('documents::library.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('documents::library.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
            'category_id' => 'required|exists:document_categories,id',
            'is_public' => 'boolean',
        ]);

        // Process document update
        return redirect()->route('documents.library.index')
            ->with('success', 'Document updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Process document deletion
        return redirect()->route('documents.library.index')
            ->with('success', 'Document deleted successfully.');
    }
}
