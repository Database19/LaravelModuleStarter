<?php

namespace Modules\PointOfSales\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PosTransaction;
use App\Models\PosTransactionItem;
use App\Models\PosPayment;
use App\Models\Product;
use App\Models\Customer;
use App\Models\WarehouseStock;
use App\Models\JournalEntry;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = PosTransaction::with(['customer', 'cashier'])
            ->orderBy('transaction_date', 'desc')
            ->paginate(15);

        return view('pointofsales::transactions.index', compact('transactions'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->with('category')->get();
        $customers = Customer::orderBy('name')->get();

        return view('pointofsales::transactions.create', compact('products', 'customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_amount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,card,transfer,ewallet,qris',
            'received_amount' => 'nullable|numeric',
        ]);

        DB::transaction(function () use ($validated) {
            // Create transaction
            $transaction = PosTransaction::create([
                'transaction_no' => PosTransaction::generateTransactionNo(),
                'customer_id' => $validated['customer_id'] ?? null,
                'cashier_id' => Auth::id(),
                'subtotal' => 0, // Will be calculated
                'tax_amount' => $validated['tax_amount'] ?? 0,
                'discount_amount' => $validated['discount_amount'] ?? 0,
                'total_amount' => 0, // Will be calculated
                'status' => 'completed',
                'payment_status' => 'paid',
                'transaction_date' => now(),
            ]);

            $subtotal = 0;

            // Create transaction items and update stock
            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                $itemSubtotal = ($item['unit_price'] * $item['quantity']) - ($item['discount_amount'] ?? 0);

                PosTransactionItem::create([
                    'pos_transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount_amount' => $item['discount_amount'] ?? 0,
                    'subtotal' => $itemSubtotal,
                    'product_snapshot' => [
                        'name' => $product->name,
                        'code' => $product->code,
                        'category' => $product->category->name ?? null,
                    ],
                ]);

                $subtotal += $itemSubtotal;

                // Update stock
                $this->updateStock($item['product_id'], $item['quantity']);
            }

            // Update transaction totals
            $totalAmount = $subtotal + $transaction->tax_amount - $transaction->discount_amount;
            $transaction->update([
                'subtotal' => $subtotal,
                'total_amount' => $totalAmount,
            ]);

            // Create payment record
            PosPayment::create([
                'pos_transaction_id' => $transaction->id,
                'payment_method' => $validated['payment_method'],
                'amount' => $totalAmount,
                'received_amount' => $validated['received_amount'] ?? $totalAmount,
                'change_amount' => max(0, ($validated['received_amount'] ?? $totalAmount) - $totalAmount),
                'status' => 'completed',
                'payment_date' => now(),
            ]);

            // Create accounting journal entries
            $this->createJournalEntries($transaction);
        });

        alert()->success('Berhasil!', 'Transaksi POS berhasil diproses.');
        return redirect()->route('pointofsales.transactions.index');
    }

    public function show(PosTransaction $transaction)
    {
        $transaction->load(['items.product', 'payments', 'customer', 'cashier']);
        return view('pointofsales::transactions.show', compact('transaction'));
    }

    public function destroy(PosTransaction $transaction)
    {
        if ($transaction->status === 'completed') {
            // Restore stock
            foreach ($transaction->items as $item) {
                $this->restoreStock($item->product_id, $item->quantity);
            }
        }

        $transaction->delete();
        alert()->success('Berhasil!', 'Transaksi berhasil dihapus.');
        return redirect()->route('pointofsales.transactions.index');
    }

    private function updateStock($productId, $quantity)
    {
        $stock = WarehouseStock::where('product_id', $productId)->first();
        if ($stock) {
            $stock->decrement('quantity', $quantity);
        }
    }

    private function restoreStock($productId, $quantity)
    {
        $stock = WarehouseStock::where('product_id', $productId)->first();
        if ($stock) {
            $stock->increment('quantity', $quantity);
        }
    }

    private function createJournalEntries(PosTransaction $transaction)
    {
        // Create sales journal entry
        $journal = JournalEntry::create([
            'date' => $transaction->transaction_date,
            'description' => 'POS Sale - ' . $transaction->transaction_no,
            'user_id' => Auth::id(),
        ]);

        // Debit: Cash/Bank Account
        $journal->items()->create([
            'account_id' => Account::where('account_code', '1010')->first()->id, // Cash account
            'debit' => $transaction->total_amount,
            'credit' => 0,
        ]);

        // Credit: Sales Revenue Account
        $journal->items()->create([
            'account_id' => Account::where('account_code', '4010')->first()->id, // Sales account
            'debit' => 0,
            'credit' => $transaction->subtotal,
        ]);

        // Credit: Tax Payable (if any)
        if ($transaction->tax_amount > 0) {
            $journal->items()->create([
                'account_id' => Account::where('account_code', '2020')->first()->id, // Tax payable
                'debit' => 0,
                'credit' => $transaction->tax_amount,
            ]);
        }
    }
}
