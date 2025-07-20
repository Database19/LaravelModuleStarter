<?php
namespace Modules\Inventory\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Inventory\Http\Requests\StoreProductRequest;
use Modules\Inventory\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('inventory::products.index', compact('products'));
    }

    public function create()
    {
        $categories = ProductCategory::orderBy('name')->get();
        return view('inventory::products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        Product::create($request->validated());
        alert()->success('Berhasil!', 'Produk baru telah ditambahkan.');
        return redirect()->route('products.index');
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::orderBy('name')->get();
        return view('inventory::products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        alert()->success('Berhasil!', 'Produk telah diperbarui.');
        return redirect()->route('products.index');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        alert()->success('Berhasil!', 'Produk telah dihapus.');
        return redirect()->route('products.index');
    }
}
