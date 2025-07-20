<?php
namespace Modules\Inventory\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Routing\Controller;
use Modules\Inventory\Http\Requests\StoreProductCategoryRequest;
use Modules\Inventory\Http\Requests\UpdateProductCategoryRequest;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::withCount('products')->latest()->paginate(10);
        return view('inventory::product-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('inventory::product-categories.create');
    }

    public function store(StoreProductCategoryRequest $request)
    {
        ProductCategory::create($request->validated());
        alert()->success('Berhasil!', 'Kategori produk baru telah ditambahkan.');
        return redirect()->route('inventory.product-categories.index');
    }

    public function edit(ProductCategory $productCategory)
    {
        return view('inventory::product-categories.edit', compact('productCategory'));
    }

    public function update(UpdateProductCategoryRequest $request, ProductCategory $productCategory)
    {
        $productCategory->update($request->validated());
        alert()->success('Berhasil!', 'Kategori produk telah diperbarui.');
        return redirect()->route('inventory.product-categories.index');
    }

    public function destroy(ProductCategory $productCategory)
    {
        if ($productCategory->products()->exists()) {
            alert()->error('Gagal!', 'Kategori tidak bisa dihapus karena memiliki produk terkait.');
            return back();
        }
        $productCategory->delete();
        alert()->success('Berhasil!', 'Kategori produk telah dihapus.');
        return redirect()->route('inventory.product-categories.index');
    }
}
