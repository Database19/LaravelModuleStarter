<?php

namespace Modules\QualityControl\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\QualityInspection;
use App\Models\QualityCheck;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QualityInspectionController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = QualityInspection::with(['qualityCheck.product', 'inspector']);

        // Filters
        if ($request->filled('quality_check_id')) {
            $query->where('quality_check_id', $request->quality_check_id);
        }

        if ($request->filled('inspector_id')) {
            $query->where('inspector_id', $request->inspector_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('parameter_name', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        $inspections = $query->orderBy('created_at', 'desc')->paginate(15);
        $qualityChecks = QualityCheck::with('product')->orderBy('created_at', 'desc')->get();
        $inspectors = User::where('is_super_admin', true)
                         ->orWhere('name', 'like', '%quality%')
                         ->orWhere('name', 'like', '%inspector%')
                         ->get();

        return view('qualitycontrol::inspections.index', compact('inspections', 'qualityChecks', 'inspectors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $qualityChecks = QualityCheck::with('product')->where('status', '!=', 'completed')->orderBy('created_at', 'desc')->get();
        $inspectors = User::where('is_super_admin', true)
                         ->orWhere('name', 'like', '%quality%')
                         ->orWhere('name', 'like', '%inspector%')
                         ->get();

        return view('qualitycontrol::inspections.create', compact('qualityChecks', 'inspectors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'quality_check_id' => 'required|exists:quality_checks,id',
            'parameter_name' => 'required|string|max:255',
            'specification' => 'required|string',
            'actual_value' => 'required|string',
            'unit' => 'nullable|string|max:50',
            'method' => 'required|string',
            'result' => 'required|in:pass,fail,warning',
            'inspector_id' => 'required|exists:users,id',
            'inspection_date' => 'required|date',
            'notes' => 'nullable|string',
            'corrective_action' => 'nullable|string',
        ]);

        $validated['status'] = $validated['result'] == 'pass' ? 'approved' : 'rejected';
        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        QualityInspection::create($validated);

        alert()->success('Berhasil!', 'Quality Inspection berhasil dibuat.');
        return redirect()->route('qualitycontrol.inspections.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(QualityInspection $inspection)
    {
        $inspection->load(['qualityCheck.product', 'inspector', 'creator', 'updater']);

        return view('qualitycontrol::inspections.show', compact('inspection'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QualityInspection $inspection)
    {
        $qualityChecks = QualityCheck::with('product')->orderBy('created_at', 'desc')->get();
        $inspectors = User::where('is_super_admin', true)
                         ->orWhere('name', 'like', '%quality%')
                         ->orWhere('name', 'like', '%inspector%')
                         ->get();

        return view('qualitycontrol::inspections.edit', compact('inspection', 'qualityChecks', 'inspectors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QualityInspection $inspection)
    {
        $validated = $request->validate([
            'quality_check_id' => 'required|exists:quality_checks,id',
            'parameter_name' => 'required|string|max:255',
            'specification' => 'required|string',
            'actual_value' => 'required|string',
            'unit' => 'nullable|string|max:50',
            'method' => 'required|string',
            'result' => 'required|in:pass,fail,warning',
            'inspector_id' => 'required|exists:users,id',
            'inspection_date' => 'required|date',
            'notes' => 'nullable|string',
            'corrective_action' => 'nullable|string',
        ]);

        $validated['status'] = $validated['result'] == 'pass' ? 'approved' : 'rejected';
        $validated['updated_by'] = Auth::id();

        $inspection->update($validated);

        alert()->success('Berhasil!', 'Quality Inspection berhasil diupdate.');
        return redirect()->route('qualitycontrol.inspections.show', $inspection);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QualityInspection $inspection)
    {
        $inspection->delete();

        alert()->success('Berhasil!', 'Quality Inspection berhasil dihapus.');
        return redirect()->route('qualitycontrol.inspections.index');
    }
}
