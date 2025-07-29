<?php

namespace Modules\QualityControl\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\QualityStandard;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QualityStandardController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = QualityStandard::with('product');

        // Filters
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('product', function($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $standards = $query->orderBy('created_at', 'desc')->paginate(15);
        $products = Product::orderBy('name')->get();

        return view('qualitycontrol::standards.index', compact('standards', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::orderBy('name')->get();

        return view('qualitycontrol::standards.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'product_id' => 'nullable|exists:products,id',
            'standards' => 'required|array',
            'is_active' => 'boolean',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        QualityStandard::create($validated);

        alert()->success('Berhasil!', 'Quality Standard berhasil dibuat.');
        return redirect()->route('qualitycontrol.standards.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(QualityStandard $standard)
    {
        $standard->load(['product', 'creator', 'updater']);

        return view('qualitycontrol::standards.show', compact('standard'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QualityStandard $standard)
    {
        $products = Product::orderBy('name')->get();

        return view('qualitycontrol::standards.edit', compact('standard', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QualityStandard $standard)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'product_id' => 'nullable|exists:products,id',
            'standards' => 'required|array',
            'is_active' => 'boolean',
        ]);

        $validated['updated_by'] = Auth::id();

        $standard->update($validated);

        alert()->success('Berhasil!', 'Quality Standard berhasil diupdate.');
        return redirect()->route('qualitycontrol.standards.show', $standard);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QualityStandard $standard)
    {
        $standard->delete();

        alert()->success('Berhasil!', 'Quality Standard berhasil dihapus.');
        return redirect()->route('qualitycontrol.standards.index');
    }
}
