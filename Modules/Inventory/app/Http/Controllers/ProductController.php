<?php
namespace Modules\Inventory\Http\Controllers;

use App\Models\Brand;
use Illuminate\Routing\Controller;
use Modules\Inventory\Http\Requests\StoreProductRequest;
use Modules\Inventory\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Unit;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('inventory::products.index', compact('products'));
    }

    public function create()
    {
        $categories = ProductCategory::all(); // Asumsi
        $units = Unit::all(); // Asumsi
        $brands = Brand::all();

        $categories = ProductCategory::orderBy('name')->get();
        return view('inventory::products.create', compact('categories', 'units', 'brands'));
    }

    public function store(Request $request)
    {
        $gudang = Warehouse::find(1);

        if(!$gudang){
            a('Gudang tidak ditemukan', 'Tolong buat data gudang terlebih dahulu untuk penyesuaian stock secara otomatis', 'error');
            return redirect()->route('warehouse.warehouses.index');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku',
            'product_category_id' => 'required|exists:product_categories,id',
            'unit_id' => 'required|exists:units,id',
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'track_stock' => 'required|boolean',
            'is_active' => 'required|boolean',
            'brand_id' => 'nullable|exists:brands,id',
            'barcode' => 'nullable|string|max:255|unique:products,barcode',
            'description' => 'nullable|string',
            'type' => 'required|in:product,service',
        ]);

        // 3. Buat produk baru menggunakan array yang sudah dimodifikasi.
        Product::create($validatedData);
        alert()->success('Berhasil!', 'Produk baru telah ditambahkan.');
        return redirect()->route('inventory.products.index');
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::all();
        $units = Unit::all();
        $brands = Brand::all();

        $categories = ProductCategory::orderBy('name')->get();
        return view('inventory::products.edit', compact('product', 'categories', 'units', 'brands'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        alert()->success('Berhasil!', 'Produk telah diperbarui.');
        return redirect()->route('inventory.products.index');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        alert()->success('Berhasil!', 'Produk telah dihapus.');
        return redirect()->route('inventory.products.index');
    }
}
