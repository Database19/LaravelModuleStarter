<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JournalEntry;
use App\Models\StockCount;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockCountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $counts = StockCount::with(['warehouse', 'createdBy'])->latest()->paginate(15);
        return view('warehouse::counts.index', compact('counts'));
    }

    /**
     * Menampilkan form untuk memulai sesi stock opname baru.
     */
    public function create()
    {
        $warehouses = Warehouse::where('is_active', true)->get();
        return view('warehouse::counts.create', compact('warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['warehouse_id' => 'required|exists:warehouses,id', 'count_date' => 'required|date']);

        DB::beginTransaction();
        try {
            $stockCount = StockCount::create([
                'count_number' => 'SC-' . time(),
                'warehouse_id' => $request->warehouse_id,
                'count_date' => $request->count_date,
                'status' => 'counting'
            ]);

            $stocksInWarehouse = WarehouseStock::where('warehouse_id', $request->warehouse_id)->get();
            foreach ($stocksInWarehouse as $stock) {
                $stockCount->items()->create([
                    'product_id' => $stock->product_id,
                    'system_quantity' => $stock->quantity,
                ]);
            }

            DB::commit();
            return redirect()->route('warehouse.counts.show', $stockCount);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memulai Stock Opname: ' . $e->getMessage());
        }
    }

    public function show(StockCount $count)
    {
        $count->load('items.product');
        return view('warehouse::counts.show', compact('count'));
    }

    public function update(Request $request, StockCount $count)
    {
        $request->validate(['items' => 'required|array']);

        foreach ($request->items as $itemId => $data) {
            $count->items()->where('id', $itemId)->update(['counted_quantity' => $data['counted_quantity']]);
        }
        return back()->with('success', 'Hasil hitungan berhasil disimpan.');
    }

    public function process(StockCount $count)
    {
        if ($count->status != 'counting') {
            return back()->with('error', 'Hanya sesi dengan status "counting" yang bisa diproses.');
        }

        DB::beginTransaction();
        try {
            $totalVarianceValue = 0;

            foreach ($count->items as $item) {
                $countedQty = $item->counted_quantity ?? 0;
                $variance = $countedQty - $item->system_quantity;

                if ($variance != 0) {
                    // 1. Sesuaikan stok di tabel warehouse_stock
                    $stock = WarehouseStock::where('warehouse_id', $count->warehouse_id)
                                           ->where('product_id', $item->product_id)
                                           ->first();
                    if ($stock) {
                        $stock->update(['quantity' => $countedQty]);
                    }

                    // 2. Hitung nilai selisih untuk jurnal
                    $productCost = $item->product->cost; // Asumsi ada harga modal di model Product
                    $totalVarianceValue += $variance * $productCost;
                }
            }

            // 3. Buat Jurnal Penyesuaian jika ada selisih
            if ($totalVarianceValue != 0) {
                $inventoryAccountId = DB::table('accounting_settings')->where('key', 'default_inventory_account')->value('value');
                $adjustmentAccountId = DB::table('accounting_settings')->where('key', 'default_inventory_adjustment_account')->value('value');

                if (!$inventoryAccountId || !$adjustmentAccountId) {
                    throw new \Exception("Akun Persediaan atau Penyesuaian Persediaan belum diatur.");
                }

                $journal = JournalEntry::create([
                    'journal_number' => 'JRN-ADJ-' . $count->id,
                    'date' => $count->count_date,
                    'description' => 'Penyesuaian Stok dari Opname #' . $count->count_number,
                    'total_debit' => abs($totalVarianceValue),
                    'total_credit' => abs($totalVarianceValue),
                    'referenceable_type' => StockCount::class,
                    'referenceable_id' => $count->id,
                    'user_id' => auth()->id(),
                ]);

                if ($totalVarianceValue > 0) {
                    $journal->items()->create(['account_id' => $inventoryAccountId, 'debit' => $totalVarianceValue, 'credit' => 0]);
                    $journal->items()->create(['account_id' => $adjustmentAccountId, 'debit' => 0, 'credit' => $totalVarianceValue]);
                } else {
                    $journal->items()->create(['account_id' => $adjustmentAccountId, 'debit' => abs($totalVarianceValue), 'credit' => 0]);
                    $journal->items()->create(['account_id' => $inventoryAccountId, 'debit' => 0, 'credit' => abs($totalVarianceValue)]);
                }
            }

            // 4. Selesaikan sesi opname
            $count->update(['status' => 'completed']);

            DB::commit();
            return back()->with('success', 'Stock Opname berhasil diproses. Stok dan Jurnal telah disesuaikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }
}
