<?php

namespace Modules\Sales\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Modules\Sales\Events\SalesOrderConfirmed;
use App\Models\Product;

class ProcessSalesOrderConfirmation implements ShouldQueue
{
    public function handle(SalesOrderConfirmed $event): void
    {
        $salesOrder = $event->salesOrder;

        DB::transaction(function () use ($salesOrder) {
            $this->adjustStock($salesOrder);
            $this->createJournalEntries($salesOrder);
        });
    }

    private function adjustStock($salesOrder): void
    {
        foreach ($salesOrder->items as $item) {
            Product::find($item->product_id)->decrement('quantity', $item->quantity);

            DB::table('stock_movements')->insert([
                'product_id' => $item->product_id,
                'warehouse_id' => 1,
                'type' => 'out',
                'quantity' => $item->quantity,
                'reason' => 'Sales',
                'reference_type' => 'App\\Models\\SalesOrder',
                'reference_id' => $salesOrder->id,
                'user_id' => $salesOrder->user_id,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }

    private function createJournalEntries($salesOrder): void
    {
        // Jurnal untuk Pendapatan & Piutang
        $journalId = DB::table('journal_entries')->insertGetId([
            'date' => $salesOrder->order_date,
            'description' => 'Pendapatan dari Sales Order ' . $salesOrder->order_number,
            'referenceable_type' => 'App\\Models\\SalesOrder',
            'referenceable_id' => $salesOrder->id,
            'user_id' => $salesOrder->user_id, // atau user akuntan
            'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('journal_entry_items')->insert([
            // Debit: Piutang Usaha (Total tagihan)
            ['journal_entry_id' => $journalId, 'account_id' => 5, 'debit' => $salesOrder->total_amount + $salesOrder->tax_amount, 'credit' => 0],
            // Credit: Pendapatan Penjualan
            ['journal_entry_id' => $journalId, 'account_id' => 31, 'debit' => 0, 'credit' => $salesOrder->total_amount],
            // Credit: PPN Keluaran
            ['journal_entry_id' => $journalId, 'account_id' => 24, 'debit' => 0, 'credit' => $salesOrder->tax_amount],
        ]);

        // Tambahan: Anda bisa membuat jurnal kedua untuk HPP (Harga Pokok Penjualan) di sini
    }
}
