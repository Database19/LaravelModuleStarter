<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockTransfer;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockTransferController extends Controller
{
    public function index()
    {
        $transfers = StockTransfer::with(['sourceWarehouse', 'destinationWarehouse'])->latest()->paginate(15);
        return view('warehouse::transfers.index', compact('transfers'));
    }

    public function create()
    {
        $warehouses = Warehouse::where('is_active', true)->get();
        $products = Product::with('warehouses')->where('is_active', true)->get();
        // dd($products);
        return view('warehouse::transfers.create', compact('warehouses', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'source_warehouse_id' => 'required|exists:warehouses,id',
            'destination_warehouse_id' => 'required|exists:warehouses,id|different:source_warehouse_id',
            'transfer_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $transfer = StockTransfer::create([
                'transfer_number' => 'TRF-' . time(), // Ganti dengan generator nomor yang lebih baik
                'source_warehouse_id' => $request->source_warehouse_id,
                'destination_warehouse_id' => $request->destination_warehouse_id,
                'transfer_date' => $request->transfer_date,
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);

            foreach ($request->items as $item) {
                $transfer->items()->create($item);
            }

            DB::commit();
            return redirect()->route('warehouse.transfers.show', $transfer);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(StockTransfer $transfer)
    {
        $transfer->load(['sourceWarehouse', 'destinationWarehouse', 'items.product']);
        return view('warehouse::transfers.show', compact('transfer'));
    }

    // --- LOGIKA ALUR KERJA (WORKFLOW) ---

    public function ship(StockTransfer $transfer)
    {
        if ($transfer->status != 'draft') {
            return back()->with('error', 'Hanya transfer dengan status Draft yang bisa dikirim.');
        }

        DB::beginTransaction();
        try {
            // Kurangi stok dari gudang asal
            foreach ($transfer->items as $item) {
                $stock = WarehouseStock::where('warehouse_id', $transfer->source_warehouse_id)
                                       ->where('product_id', $item->product_id)
                                       ->first();

                if (!$stock || $stock->quantity < $item->quantity) {
                    throw new \Exception("Stok untuk produk {$item->product->name} tidak mencukupi di gudang asal.");
                }
                $stock->decrement('quantity', $item->quantity);
            }

            // Update status transfer
            $transfer->update(['status' => 'shipped']);

            DB::commit();
            return back()->with('success', 'Barang telah dikirim dan stok gudang asal telah diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengirim barang: ' . $e->getMessage());
        }
    }

    public function receive(StockTransfer $transfer)
    {
        if ($transfer->status != 'shipped') {
            return back()->with('error', 'Hanya transfer yang sudah dikirim yang bisa diterima.');
        }

        DB::beginTransaction();
        try {
            // Tambah stok ke gudang tujuan
            foreach ($transfer->items as $item) {
                $stock = WarehouseStock::firstOrCreate(
                    ['warehouse_id' => $transfer->destination_warehouse_id, 'product_id' => $item->product_id],
                    ['quantity' => 0, 'created_by' => auth()->id()]
                );
                $stock->updated_by = auth()->id();
                $stock->increment('quantity', $item->quantity);
            }

            // Update status transfer
            $transfer->update(['status' => 'received']);

            DB::commit();
            return back()->with('success', 'Barang telah diterima dan stok gudang tujuan telah diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menerima barang: ' . $e->getMessage());
        }
    }
}
