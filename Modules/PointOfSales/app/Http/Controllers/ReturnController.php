<?php

namespace Modules\PointOfSales\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PosTransaction;
use App\Models\PosTransactionItem;
use App\Models\PosPayment;
use App\Models\WarehouseStock;
use App\Models\JournalEntry;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReturnController extends Controller
{
    public function index()
    {
        $returns = PosTransaction::where('type', 'return')
            ->with(['customer', 'cashier'])
            ->orderBy('transaction_date', 'desc')
            ->paginate(15);

        return view('pointofsales::returns.index', compact('returns'));
    }

    public function create()
    {
        $transactions = PosTransaction::where('type', 'sale')
            ->where('status', 'completed')
            ->with(['customer', 'items.product'])
            ->orderBy('transaction_date', 'desc')
            ->limit(100)
            ->get();

        return view('pointofsales::returns.create', compact('transactions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'original_transaction_id' => 'required|exists:pos_transactions,id',
            'return_items' => 'required|array|min:1',
            'return_items.*.transaction_item_id' => 'required|exists:pos_transaction_items,id',
            'return_items.*.quantity' => 'required|integer|min:1',
            'return_reason' => 'required|string|max:255',
            'refund_method' => 'required|in:cash,card,transfer,ewallet,qris,store_credit',
        ]);

        $originalTransaction = PosTransaction::with(['items', 'payments'])->find($validated['original_transaction_id']);

        DB::transaction(function () use ($validated, $originalTransaction) {
            // Create return transaction
            $returnTransaction = PosTransaction::create([
                'transaction_no' => PosTransaction::generateTransactionNo(),
                'type' => 'return',
                'customer_id' => $originalTransaction->customer_id,
                'cashier_id' => Auth::id(),
                'subtotal' => 0,
                'tax_amount' => 0,
                'discount_amount' => 0,
                'total_amount' => 0,
                'status' => 'completed',
                'payment_status' => 'refunded',
                'transaction_date' => now(),
                'notes' => 'Return for transaction: ' . $originalTransaction->transaction_no . ' - ' . $validated['return_reason'],
                'reference_transaction_id' => $originalTransaction->id,
            ]);

            $returnTotal = 0;

            // Process return items
            foreach ($validated['return_items'] as $returnItem) {
                $originalItem = PosTransactionItem::find($returnItem['transaction_item_id']);
                $returnQuantity = $returnItem['quantity'];
                $returnSubtotal = ($originalItem->unit_price * $returnQuantity) -
                    (($originalItem->discount_amount / $originalItem->quantity) * $returnQuantity);

                // Create return item
                PosTransactionItem::create([
                    'pos_transaction_id' => $returnTransaction->id,
                    'product_id' => $originalItem->product_id,
                    'quantity' => -$returnQuantity, // Negative for return
                    'unit_price' => $originalItem->unit_price,
                    'discount_amount' => ($originalItem->discount_amount / $originalItem->quantity) * $returnQuantity,
                    'subtotal' => -$returnSubtotal, // Negative for return
                    'product_snapshot' => $originalItem->product_snapshot,
                    'reference_item_id' => $originalItem->id,
                ]);

                $returnTotal += $returnSubtotal;

                // Restore stock
                $this->restoreStock($originalItem->product_id, $returnQuantity);

                // Update original item returned quantity
                $originalItem->increment('returned_quantity', $returnQuantity);
            }

            // Update return transaction totals
            $returnTransaction->update([
                'subtotal' => -$returnTotal,
                'total_amount' => -$returnTotal,
            ]);

            // Create refund payment
            PosPayment::create([
                'pos_transaction_id' => $returnTransaction->id,
                'payment_method' => $validated['refund_method'],
                'amount' => -$returnTotal, // Negative for refund
                'received_amount' => 0,
                'change_amount' => 0,
                'status' => 'completed',
                'payment_date' => now(),
                'notes' => 'Refund for return',
            ]);

            // Create journal entries for return
            $this->createReturnJournalEntries($returnTransaction);
        });

        alert()->success('Berhasil!', 'Return berhasil diproses.');
        return redirect()->route('pointofsales.returns.index');
    }

    public function show(PosTransaction $return)
    {
        $return->load(['items.product', 'payments', 'customer', 'cashier', 'referenceTransaction']);
        return view('pointofsales::returns.show', compact('return'));
    }

    private function restoreStock($productId, $quantity)
    {
        $stock = WarehouseStock::where('product_id', $productId)->first();
        if ($stock) {
            $stock->increment('quantity', $quantity);
        }
    }

    private function createReturnJournalEntries(PosTransaction $returnTransaction)
    {
        $journal = JournalEntry::create([
            'date' => $returnTransaction->transaction_date,
            'description' => 'POS Return - ' . $returnTransaction->transaction_no,
            'user_id' => Auth::id(),
        ]);

        // Credit: Cash/Bank Account (money going out)
        $journal->items()->create([
            'account_id' => Account::where('account_code', '1010')->first()->id,
            'debit' => 0,
            'credit' => abs($returnTransaction->total_amount),
        ]);

        // Debit: Sales Returns Account
        $journal->items()->create([
            'account_id' => Account::where('account_code', '4020')->first()->id, // Sales returns
            'debit' => abs($returnTransaction->total_amount),
            'credit' => 0,
        ]);
    }
}
