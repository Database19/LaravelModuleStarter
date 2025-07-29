<?php

namespace Modules\PointOfSales\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PosPayment;
use App\Models\PosTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = PosPayment::with(['transaction.customer'])
            ->orderBy('payment_date', 'desc')
            ->paginate(15);

        return view('pointofsales::payments.index', compact('payments'));
    }

    public function show(PosPayment $payment)
    {
        $payment->load(['transaction.customer', 'transaction.items.product']);
        return view('pointofsales::payments.show', compact('payment'));
    }

    public function refund(Request $request, PosPayment $payment)
    {
        $validated = $request->validate([
            'refund_amount' => 'required|numeric|min:0.01|max:' . $payment->amount,
            'refund_reason' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($payment, $validated) {
            // Create refund payment record
            PosPayment::create([
                'pos_transaction_id' => $payment->pos_transaction_id,
                'payment_method' => $payment->payment_method,
                'amount' => -$validated['refund_amount'],
                'received_amount' => 0,
                'change_amount' => 0,
                'status' => 'refunded',
                'payment_date' => now(),
                'notes' => 'Refund: ' . $validated['refund_reason'],
            ]);

            // Update original payment status if fully refunded
            $totalRefunded = PosPayment::where('pos_transaction_id', $payment->pos_transaction_id)
                ->where('amount', '<', 0)
                ->sum('amount');

            if (abs($totalRefunded) >= $payment->amount) {
                $payment->update(['status' => 'refunded']);
                $payment->transaction->update(['payment_status' => 'refunded']);
            } else {
                $payment->transaction->update(['payment_status' => 'partial_refund']);
            }
        });

        alert()->success('Berhasil!', 'Refund berhasil diproses.');
        return redirect()->route('pointofsales.payments.show', $payment);
    }

    public function report(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $payments = PosPayment::with(['transaction'])
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->where('amount', '>', 0) // Exclude refunds
            ->get()
            ->groupBy('payment_method');

        $totals = [];
        foreach ($payments as $method => $methodPayments) {
            $totals[$method] = [
                'count' => $methodPayments->count(),
                'total' => $methodPayments->sum('amount'),
            ];
        }

        return view('pointofsales::payments.report', compact('payments', 'totals', 'startDate', 'endDate'));
    }
}
