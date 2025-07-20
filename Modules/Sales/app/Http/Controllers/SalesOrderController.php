<?php

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// ===== PERUBAHAN UTAMA DI BAGIAN INI =====
use App\Models\User;
use App\Models\SalesOrder;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Account;
use App\Models\JournalEntry;
// ==========================================

class SalesOrderController extends Controller
{
    /**
     * Menampilkan daftar semua sales order.
     */
    public function index()
    {
        $salesOrders = SalesOrder::with(['customer', 'user'])->latest()->paginate(15);
        // dd($salesOrders);
        return view('sales::sales_orders.index', compact('salesOrders'));
    }

    /**
     * Menampilkan form untuk membuat sales order baru.
     */
    public function create()
    {
        // PERUBAHAN DI SINI: Mengambil data dari model Customer
        $customers = Customer::all();
        $products = Product::where('is_active', true)->get();
        $users = User::all();
        $warehouses = Warehouse::all();

        return view('sales::sales_orders.create', compact('customers', 'products', 'users', 'warehouses'));
    }

    /**
     * Menyimpan sales order baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            // PERUBAHAN DI SINI: Validasi ke tabel customers
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'required|exists:users,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'order_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // ... (Logika di dalam try-catch tetap sama) ...
        // (Tidak perlu ada perubahan di sini karena semua sudah menggunakan variabel)
        DB::beginTransaction();
        try {
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += ($item['quantity'] * $item['unit_price']) - ($item['discount_amount'] ?? 0);
            }
            $grandTotal = $subtotal - ($request->discount_amount ?? 0) + ($request->tax_amount ?? 0);

            $salesOrder = SalesOrder::create([
                'order_number' => 'SO-' . date('Ymd') . '-' . time(),
                'customer_id' => $request->customer_id,
                'user_id' => $request->user_id,
                'warehouse_id' => $request->warehouse_id,
                'order_date' => $request->order_date,
                'subtotal' => $subtotal,
                'tax_amount' => $request->tax_amount ?? 0,
                'discount_amount' => $request->discount_amount ?? 0,
                'total_amount' => $grandTotal,
                'status' => 'draft',
                'notes' => $request->notes,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            foreach ($request->items as $item) {
                $salesOrder->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount_amount' => $item['discount_amount'] ?? 0,
                    'total_price' => ($item['quantity'] * $item['unit_price']) - ($item['discount_amount'] ?? 0),
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);
            }

            DB::commit();
            return redirect()->route('sales.orders.show', $salesOrder)->with('success', 'Sales Order berhasil disimpan sebagai Draft.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail satu sales order.
     */
    public function show(SalesOrder $salesOrder)
    {
        $salesOrder->load(['customer', 'user', 'warehouse', 'items.product']);

        // dd($salesOrder);
        return view('sales::sales_orders.show', compact('salesOrder'));
    }

    /**
     * Menampilkan form untuk mengedit sales order.
     */
    public function edit(SalesOrder $salesOrder)
    {
        if ($salesOrder->status != 'draft') {
            return redirect()->route('sales.orders.show', $salesOrder)->with('error', 'Hanya order dengan status Draft yang bisa diedit.');
        }

        // PERUBAHAN DI SINI: Mengambil data dari model Customer
        $customers = Customer::all();
        $products = Product::where('is_active', true)->get();
        $users = User::all();
        $warehouses = Warehouse::all();

        return view('sales::sales_orders.edit', compact('salesOrder', 'customers', 'products', 'users', 'warehouses'));
    }

    /**
     * Memperbarui sales order di database.
     */
    public function update(Request $request, SalesOrder $salesOrder)
    {
        $request->validate([
            // PERUBAHAN DI SINI: Validasi ke tabel customers
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'required|exists:users,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'order_date' => 'required|date',
            'items' => 'required|array|min:1',
        ]);

        // ... (Logika di dalam try-catch tetap sama) ...
        DB::beginTransaction();
        try {
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += ($item['quantity'] * $item['unit_price']) - ($item['discount_amount'] ?? 0);
            }
            $grandTotal = $subtotal - ($request->discount_amount ?? 0) + ($request->tax_amount ?? 0);

            $salesOrder->update([
                'customer_id' => $request->customer_id,
                'user_id' => $request->user_id,
                'warehouse_id' => $request->warehouse_id,
                'order_date' => $request->order_date,
                'subtotal' => $subtotal,
                'tax_amount' => $request->tax_amount ?? 0,
                'discount_amount' => $request->discount_amount ?? 0,
                'total_amount' => $grandTotal,
                'notes' => $request->notes,
                'updated_by' => auth()->id(),
            ]);

            $salesOrder->items()->delete();
            foreach ($request->items as $item) {
                 $salesOrder->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount_amount' => $item['discount_amount'] ?? 0,
                    'total_price' => ($item['quantity'] * $item['unit_price']) - ($item['discount_amount'] ?? 0),
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);
            }

            DB::commit();
            return redirect()->route('sales.orders.show', $salesOrder)->with('success', 'Sales Order berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function confirm(SalesOrder $salesOrder)
    {
        if ($salesOrder->status != 'draft') {
            return back()->with('error', 'Hanya order dengan status Draft yang bisa dikonfirmasi.');
        }
        $salesOrder->update(['status' => 'confirmed']);
        return back()->with('success', 'Sales Order telah dikonfirmasi.');
    }

    public function createShipment(SalesOrder $salesOrder)
    {
        if ($salesOrder->status != 'confirmed') {
             return back()->with('error', 'Hanya order yang sudah dikonfirmasi yang bisa dikirim.');
        }

        DB::beginTransaction();
        try {
            // 1. Ubah status order
            $salesOrder->update(['status' => 'shipped']);

            // 2. Logika pengurangan stok langsung
            foreach ($salesOrder->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    // Cek stok jika perlu
                    if($product->quantity < $item->quantity) {
                         throw new \Exception("Stok untuk produk " . $product->name . " tidak mencukupi.");
                    }
                    $product->decrement('quantity', $item->quantity);
                }
            }

            DB::commit();
            return back()->with('success', 'Barang telah dikirim dan stok telah diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat pengiriman: ' . $e->getMessage());
        }
    }

    public function createInvoice(SalesOrder $salesOrder)
    {
        if ($salesOrder->status != 'shipped') {
             return back()->with('error', 'Hanya order yang sudah dikirim yang bisa dibuatkan faktur.');
        }

        DB::beginTransaction();
        try {
            $salesOrder->update(['status' => 'completed']);

            $piutangAccountId = DB::table('accounting_settings')->where('key', 'default_accounts_receivable')->value('value');
            $pendapatanAccountId = DB::table('accounting_settings')->where('key', 'default_sales_revenue')->value('value');

            if (!$piutangAccountId || !$pendapatanAccountId) {
                throw new \Exception("Pengaturan Akun Piutang/Pendapatan belum diatur.");
            }

            $journal = JournalEntry::create([
                'journal_number' => 'JRN-SO-' . $salesOrder->id,
                'date' => $salesOrder->order_date,
                'description' => 'Penjualan berdasarkan ' . $salesOrder->order_number,
                'total_debit' => $salesOrder->total_amount,
                'total_credit' => $salesOrder->total_amount,
                'referenceable_type' => get_class($salesOrder),
                'referenceable_id' => $salesOrder->id,
                'user_id' => auth()->id(),
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            $journal->items()->create([
                'account_id' => $piutangAccountId,
                'description' => 'Piutang atas ' . $salesOrder->customer->name,
                'debit' => $salesOrder->total_amount, 'credit' => 0,
                'created_by' => auth()->id(), 'updated_by' => auth()->id(),
            ]);

            $journal->items()->create([
                'account_id' => $pendapatanAccountId,
                'description' => 'Pendapatan dari ' . $salesOrder->order_number,
                'debit' => 0, 'credit' => $salesOrder->total_amount,
                'created_by' => auth()->id(), 'updated_by' => auth()->id(),
            ]);

            DB::commit();
            return back()->with('success', 'Faktur telah dibuat dan jurnal berhasil dicatat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat faktur: ' . $e->getMessage());
        }
    }
}
