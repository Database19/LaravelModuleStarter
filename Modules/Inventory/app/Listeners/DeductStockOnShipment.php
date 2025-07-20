<?php

namespace Modules\Inventory\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeductStockOnShipment
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
    public function handle(ProductShipped $event)
    {
        $salesOrder = $event->salesOrder;

        foreach ($salesOrder->items as $item) {
            // Panggil service di modul inventory untuk mengurangi stok
            // Ini adalah contoh, sesuaikan dengan logika Anda
            StockService::deduct(
                $item->product_id,
                $salesOrder->warehouse_id,
                $item->quantity,
                'sales', // Tipe pergerakan stok
                $salesOrder->id // Referensi
            );
        }
    }
}
