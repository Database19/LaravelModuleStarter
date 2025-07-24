<?php

namespace Modules\Purchasing\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JournalEntry;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Purchasing\Events\PurchaseOrderReceived;
use Modules\Purchasing\Listeners\ProcessPurchaseOrderReceipt;

class PurchaseOrderController extends Controller
{
   public function index()
    {
        $purchaseOrders = PurchaseOrder::with('supplier')->latest()->paginate(15);
        return view('purchasing::purchase-orders.index', compact('purchaseOrders'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $warehouses = Warehouse::orderBy('name')->get();
        $products = Product::where('product_category_id', '!=', 11)->orderBy('name')->get();
        return view('purchasing::purchase-orders.create', compact('suppliers', 'products', 'warehouses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'order_date' => 'required|date',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        // Kalkulasi
        $subtotal = collect($validated['items'])->sum(fn($item) => $item['quantity'] * $item['unit_cost']);
        $tax = $validated['tax_amount'] ?? 0;
        $discount = $validated['discount_amount'] ?? 0;
        $totalAmount = ($subtotal + $tax) - $discount;

        DB::transaction(function () use ($validated, $subtotal, $tax, $discount, $totalAmount) {
            $po = PurchaseOrder::create([
                'order_number' => 'PO-' . date('Ym') .'-'. str_pad(PurchaseOrder::count() + 1, 4, '0', STR_PAD_LEFT),
                'supplier_id' => $validated['supplier_id'],
                'warehouse_id' => $validated['warehouse_id'],
                'order_date' => $validated['order_date'],
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'discount_amount' => $discount,
                'total_amount' => $totalAmount,
                'notes' => $validated['notes'],
                'status' => 'ordered',
                'user_id' => auth()->id(),
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            $itemsData = collect($validated['items'])->map(fn($item) => [
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_cost' => $item['unit_cost'],
                'total_cost' => $item['quantity'] * $item['unit_cost'],
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            $po->items()->createMany($itemsData->all());
        });

        alert()->success('Berhasil!', 'Purchase Order baru telah dibuat.');
        return redirect()->route('purchasing.purchase-orders.index');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchase_order = $purchaseOrder->load('supplier', 'warehouse', 'items.product');
        return view('purchasing::purchase-orders.show', compact('purchase_order'));
    }

    public function receive(PurchaseOrder $purchaseOrder)
    {
        // dd($purchaseOrder->items);

        // Gunakan status lowercase agar konsisten dengan database ENUM
        if ($purchaseOrder->status !== 'ordered') {
            // Sebaiknya gunakan redirect dengan flash message standar
            return back();
        }

        DB::beginTransaction();
        try {
            $this->updateWarehouseStock($purchaseOrder);

            $inventoryAccountId = DB::table('accounting_settings')->where('key', 'default_inventory')->value('value');
            $apAccountId = DB::table('accounting_settings')->where('key', 'default_accounts_payable')->value('value');

            if (!$inventoryAccountId || !$apAccountId) {
                throw new \Exception("Pengaturan Akun Persediaan atau Utang Usaha belum diatur.");
            }

            // 2. Buat Jurnal
            $totalAmount = $purchaseOrder->items->sum('total_cost'); // Asumsi total dari item

            $journal = JournalEntry::create([
                'journal_number' => 'JRN-PO-' . $purchaseOrder->id,
                'date' => now(),
                'description' => 'Penerimaan barang dari PO #' . $purchaseOrder->order_number,
                'total_debit' => $totalAmount,
                'total_credit' => $totalAmount,
                'referenceable_type' => get_class($purchaseOrder),
                'referenceable_id' => $purchaseOrder->id,
                'user_id' => auth()->id(),
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            // Jurnal Sisi DEBIT: Persediaan Bertambah
            $journal->items()->create([
                'account_id' => $inventoryAccountId,
                'description' => 'Persediaan dari ' . $purchaseOrder->supplier->name, // Asumsi ada relasi supplier
                'debit' => $totalAmount, 'credit' => 0,
                'created_by' => auth()->id(), 'updated_by' => auth()->id(),
            ]);

            // Jurnal Sisi KREDIT: Utang Usaha Bertambah
            $journal->items()->create([
                'account_id' => $apAccountId,
                'description' => 'Utang Usaha ke ' . $purchaseOrder->supplier->name,
                'debit' => 0, 'credit' => $totalAmount,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            $purchaseOrder->update([
                'status' => 'received',
                'received_date' => now(),
            ]);

            DB::commit();
            return redirect()->route('purchasing.purchase-orders.show', $purchaseOrder);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses PO: ' . $e->getMessage());
        }
    }

    private function updateWarehouseStock(PurchaseOrder $purchaseOrder): void
    {
        foreach ($purchaseOrder->items as $item) {
            $stock = WarehouseStock::firstOrNew([
                'warehouse_id' => $purchaseOrder->warehouse_id,
                'product_id' => $item->product_id,
            ]);

            $quantityBefore = $stock->quantity ?? 0;

            // Tambah kuantitasnya
            $stock->quantity += $item->quantity;
            $quantityAfter = $stock->quantity;

            // Isi data user jika ini record baru
            if (!$stock->exists) {
                $stock->created_by = auth()->id();
            }
            $stock->updated_by = auth()->id();
            $stock->save();

            // Buat catatan pergerakan stok (kode Anda sudah bagus)
            StockMovement::create([
                'reference_number' => $purchaseOrder->order_number . '-' . $item->product_id,
                'product_id' => $item->product_id,
                'warehouse_id' => $purchaseOrder->warehouse_id,
                'type' => 'in',
                'quantity' => $item->quantity,
                'quantity_before' => $quantityBefore,
                'quantity_after' => $quantityAfter,
                'reason' => 'Purchase Receipt from PO #' . $purchaseOrder->order_number,
                'user_id' => auth()->id(),
                'reference_type' => get_class($purchaseOrder),
                'reference_id' => $purchaseOrder->id,
                'movement_date' => now(),
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);
        }
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('items');
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::where('product_category_id', '!=', 11)->orderBy('name')->get();
        return view('purchasing::purchase-orders.edit', compact('purchaseOrder', 'suppliers', 'products'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        $totalAmount = collect($validated['items'])->sum(fn($item) => $item['quantity'] * $item['unit_cost']);

        DB::transaction(function () use ($validated, $totalAmount, $purchaseOrder) {
            $purchaseOrder->update([
                'supplier_id' => $validated['supplier_id'],
                'order_date' => $validated['order_date'],
                'total_amount' => $totalAmount,
            ]);

            $itemsData = collect($validated['items'])->map(fn($item) => [
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_cost' => $item['unit_cost'],
                'total_cost' => $item['quantity'] * $item['unit_cost'],
            ]);

            $purchaseOrder->items()->delete(); // Hapus item lama
            $purchaseOrder->items()->createMany($itemsData->all()); // Buat item baru
        });

        alert()->success('Berhasil!', 'Purchase Order telah diperbarui.');
        return redirect()->route('purchasing.purchase-orders.index');
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        // Hanya PO dengan status 'ordered' yang boleh dihapus
        if ($purchaseOrder->status !== 'ordered') {
            alert()->error('Gagal!', 'Hanya Purchase Order dengan status "Ordered" yang bisa dihapus.');
            return back();
        }
        $purchaseOrder->delete(); // Items akan terhapus otomatis karena cascading delete
        alert()->success('Berhasil!', 'Purchase Order telah dihapus.');
        return redirect()->route('purchasing.purchase-orders.index');
    }
}
