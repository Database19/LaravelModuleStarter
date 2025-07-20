<?php

namespace Modules\Accounting\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Sales\Events\SalesOrderInvoiced;

class CreateJournalOnInvoice
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SalesOrderInvoiced $event)
    {
        $salesOrder = $event->salesOrder;

        // Asumsi Anda punya service untuk membuat jurnal
        // dan punya setting akun default
        $piutangAccountId = config('accounting.accounts.piutang_usaha');
        $pendapatanAccountId = config('accounting.accounts.pendapatan_penjualan');

        JournalService::create([
            'date' => $salesOrder->order_date,
            'description' => 'Penjualan berdasarkan ' . $salesOrder->order_number,
            'items' => [
                ['account_id' => $piutangAccountId, 'debit' => $salesOrder->total_amount, 'credit' => 0],
                ['account_id' => $pendapatanAccountId, 'debit' => 0, 'credit' => $salesOrder->total_amount],
            ]
        ]);
    }
}
