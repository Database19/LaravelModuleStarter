<?php
namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\ProductCategory;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::with('parent')->latest()->get();
        // Ambil daftar kategori untuk dropdown parent
        $parentCategories = ProductCategory::whereNull('parent_id')->orderBy('name')->get();
        return view('inventory::product-categories.index', compact('categories', 'parentCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:product_categories,name',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:product_categories,id',
            'is_active' => 'boolean',
        ]);

        ProductCategory::create($validated + [
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return response()->json(['message' => 'Kategori produk baru telah berhasil ditambahkan.']);
    }

    public function edit(ProductCategory $productCategory)
    {
        return response()->json($productCategory);
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:product_categories,name,' . $productCategory->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:product_categories,id',
            'is_active' => 'boolean',
        ]);

        $productCategory->update($validated + ['updated_by' => auth()->id()]);
        return response()->json(['message' => 'Data kategori produk telah berhasil diperbarui.']);
    }

    public function destroy(ProductCategory $productCategory)
    {
        if ($productCategory->children()->exists()) {
            alert()->error('Gagal!', 'Hapus submenu terlebih dahulu sebelum menghapus kategori utama.');
            return back();
        }
        if ($productCategory->products()->exists()) {
            alert()->error('Gagal!', 'Kategori tidak bisa dihapus karena memiliki produk terkait.');
            return back();
        }

        $productCategory->delete();
        alert()->success('Berhasil!', 'Kategori produk telah dihapus.');
        return redirect()->route('product-categories.index');
    }
}
