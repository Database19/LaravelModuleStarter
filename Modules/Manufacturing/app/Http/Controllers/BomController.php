<?php

namespace Modules\Manufacturing\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bom;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BomController extends Controller
{
    public function index()
    {
        $boms = Bom::with('product')->latest()->paginate(15);
        return view('manufacturing::boms.index', compact('boms'));
    }

    /**
     * Menampilkan form untuk membuat BOM baru.
     */
    public function create()
    {
        // Ambil produk yang bisa diproduksi (bukan bahan baku) dan belum punya BOM
        $finishedProducts = Product::whereDoesntHave('bom')->where('type', 'finished_good')->get();
        // Ambil produk yang merupakan bahan baku
        $rawMaterials = Product::where('type', 'raw_material')->get();

        return view('manufacturing::boms.create', compact('finishedProducts', 'rawMaterials'));
    }

    /**
     * Menyimpan BOM baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id|unique:boms,product_id',
            'name' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.component_product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.0001',
        ]);

        DB::beginTransaction();
        try {
            $bom = Bom::create([
                'product_id' => $request->product_id,
                'name' => $request->bom_name,
                'description' => $request->description,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            foreach ($request->items as $item) {
                $bom->items()->create($item);
            }

            DB::commit();
            return redirect()->route('manufacturing.boms.index')->with('success', 'Bill of Materials berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail satu BOM.
     */
    public function show(Bom $bom)
    {
        // Eager load relasi untuk efisiensi
        $bom->load(['product', 'items.component']);
        return view('manufacturing::boms.show', compact('bom'));
    }

    /**
     * Menampilkan form untuk mengedit BOM.
     */
    public function edit(Bom $bom)
    {
        $bom->load('items'); // Load item yang sudah ada
        $rawMaterials = Product::where('type', 'raw_material')->get();

        // Produk jadi tidak bisa diubah, jadi kita hanya pass namanya
        $finishedProduct = $bom->product;

        return view('manufacturing::boms.edit', compact('bom', 'rawMaterials', 'finishedProduct'));
    }

    /**
     * Memperbarui data BOM di database.
     */
    public function update(Request $request, Bom $bom)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.component_product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.0001',
        ]);

        DB::beginTransaction();
        try {
            // 1. Update data utama BOM
            $bom->update([
                'name' => $request->bom_name,
                'description' => $request->description,
                'updated_by' => auth()->id(),
            ]);

            // 2. Hapus semua item lama
            $bom->items()->delete();

            // 3. Buat kembali item dari data request yang baru
            foreach ($request->items as $item) {
                $bom->items()->create($item);
            }

            DB::commit();
            return redirect()->route('manufacturing.boms.show', $bom)->with('success', 'Bill of Materials berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus BOM dari database.
     */
    public function destroy(Bom $bom)
    {
        // Karena ada onDelete('cascade') di migrasi,
        // semua bom_items akan terhapus secara otomatis.
        $bom->delete();

        return redirect()->route('manufacturing.boms.index')->with('success', 'Bill of Materials berhasil dihapus.');
    }
}
