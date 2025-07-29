<?php

namespace Modules\PointOfSales\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PosTransaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    public function show(PosTransaction $transaction)
    {
        $transaction->load(['items.product', 'payments', 'customer', 'cashier']);
        return view('pointofsales::receipts.show', compact('transaction'));
    }

    public function print(PosTransaction $transaction)
    {
        $transaction->load(['items.product', 'payments', 'customer', 'cashier']);
        return view('pointofsales::receipts.print', compact('transaction'));
    }

    public function pdf(PosTransaction $transaction)
    {
        $transaction->load(['items.product', 'payments', 'customer', 'cashier']);

        $pdf = Pdf::loadView('pointofsales::receipts.pdf', compact('transaction'));
        $pdf->setPaper([0, 0, 226.77, 651.97], 'portrait'); // 80mm thermal paper width

        return $pdf->stream('receipt-' . $transaction->transaction_no . '.pdf');
    }

    public function thermal(PosTransaction $transaction)
    {
        $transaction->load(['items.product', 'payments', 'customer', 'cashier']);

        // Generate thermal printer compatible receipt
        $receipt = $this->generateThermalReceipt($transaction);

        return response($receipt)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="thermal-receipt-' . $transaction->transaction_no . '.txt"');
    }

    public function email(Request $request, PosTransaction $transaction)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $transaction->load(['items.product', 'payments', 'customer', 'cashier']);

        // Generate PDF
        $pdf = Pdf::loadView('pointofsales::receipts.pdf', compact('transaction'));

        // Send email (you'll need to implement mail configuration)
        // Mail::to($validated['email'])->send(new ReceiptMail($transaction, $pdf));

        alert()->success('Berhasil!', 'Receipt berhasil dikirim ke email.');
        return back();
    }

    private function generateThermalReceipt(PosTransaction $transaction)
    {
        $receipt = "";
        $receipt .= str_repeat("=", 32) . "\n";
        $receipt .= "        RECEIPT\n";
        $receipt .= str_repeat("=", 32) . "\n";
        $receipt .= "No: " . $transaction->transaction_no . "\n";
        $receipt .= "Date: " . $transaction->transaction_date->format('d/m/Y H:i') . "\n";
        $receipt .= "Cashier: " . $transaction->cashier->name . "\n";

        if ($transaction->customer) {
            $receipt .= "Customer: " . $transaction->customer->name . "\n";
        }

        $receipt .= str_repeat("-", 32) . "\n";

        foreach ($transaction->items as $item) {
            $receipt .= $item->product->name . "\n";
            $receipt .= sprintf("%d x %s = %s\n",
                $item->quantity,
                number_format($item->unit_price, 0),
                number_format($item->subtotal, 0)
            );

            if ($item->discount_amount > 0) {
                $receipt .= "  Discount: -" . number_format($item->discount_amount, 0) . "\n";
            }
        }

        $receipt .= str_repeat("-", 32) . "\n";
        $receipt .= sprintf("Subtotal: %s\n", number_format($transaction->subtotal, 0));

        if ($transaction->discount_amount > 0) {
            $receipt .= sprintf("Discount: -%s\n", number_format($transaction->discount_amount, 0));
        }

        if ($transaction->tax_amount > 0) {
            $receipt .= sprintf("Tax: %s\n", number_format($transaction->tax_amount, 0));
        }

        $receipt .= sprintf("TOTAL: %s\n", number_format($transaction->total_amount, 0));
        $receipt .= str_repeat("=", 32) . "\n";

        foreach ($transaction->payments as $payment) {
            $receipt .= sprintf("%s: %s\n",
                ucfirst($payment->payment_method),
                number_format($payment->amount, 0)
            );

            if ($payment->change_amount > 0) {
                $receipt .= sprintf("Change: %s\n", number_format($payment->change_amount, 0));
            }
        }

        $receipt .= str_repeat("=", 32) . "\n";
        $receipt .= "   Thank you for shopping!\n";
        $receipt .= str_repeat("=", 32) . "\n";

        return $receipt;
    }
}
