<?php

namespace Modules\Accounting\Listeners;

use App\Models\SalesOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Modules\Accounting\Events\CustomerPaymentRecorded;

class ProcessCustomerPayment implements ShouldQueue
{
    public function handle(CustomerPaymentRecorded $event): void
    {
        $payment = $event->payment;

        // Gunakan DB Transaction untuk memastikan semua proses berhasil
        DB::transaction(function () use ($payment) {
            $this->createJournalEntry($payment);
            $this->updateSalesOrderStatus($payment);
        });

        // Anda bisa mengaktifkan baris ini jika sudah membuat Mail class
        // Mail::to($payment->customer->email)->send(new PaymentConfirmationMail($payment));
    }

    /**
     * Membuat entri jurnal untuk penerimaan kas.
     */
    private function createJournalEntry($payment): void
    {
        $journalId = DB::table('journal_entries')->insertGetId([
            'date' => $payment->payment_date,
            'description' => 'Penerimaan pembayaran dari ' . $payment->customer->name . ' untuk SO ' . $payment->salesOrder->order_number,
            'referenceable_type' => 'App\\Models\\CustomerPayment',
            'referenceable_id' => $payment->id,
            'user_id' => $payment->created_by,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('journal_entry_items')->insert([
            // Debit: Kas bertambah (Asumsi akun Bank BCA dengan ID 3)
            ['journal_entry_id' => $journalId, 'account_id' => 3, 'debit' => $payment->amount, 'credit' => 0],
            // Credit: Piutang Usaha berkurang (Asumsi akun Piutang Usaha dengan ID 5)
            ['journal_entry_id' => $journalId, 'account_id' => 5, 'debit' => 0, 'credit' => $payment->amount],
        ]);
    }

    /**
     * Memperbarui status Sales Order menjadi Lunas atau Lunas Sebagian.
     */
    private function updateSalesOrderStatus($payment): void
    {
        $salesOrder = SalesOrder::find($payment->sales_order_id);

        // Hitung total yang sudah dibayar untuk SO ini
        $totalPaid = DB::table('customer_payments')
                        ->where('sales_order_id', $salesOrder->id)
                        ->sum('amount');

        // Hitung total tagihan (termasuk pajak)
        $totalBilled = $salesOrder->total_amount + $salesOrder->tax_amount;

        if ($totalPaid >= $totalBilled) {
            $salesOrder->status = 'Paid'; // Ganti jadi Lunas
        } else {
            $salesOrder->status = 'Partially Paid'; // Ganti jadi Lunas Sebagian
        }
        $salesOrder->save();
    }
}
