<?php

namespace Modules\Manufacturing\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Modules\Manufacturing\Events\ManufacturingOrderCompleted;
// Import model dari lokasi yang sesuai
use App\Models\Product;
use App\Models\BomItem;

class UpdateStockOnManufacturingCompletion implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param \Modules\Manufacturing\Events\ManufacturingOrderCompleted $event
     * @return void
     */
    public function handle(ManufacturingOrderCompleted $event): void
    {
        $mo = $event->manufacturingOrder;

        // Gunakan Transaction untuk memastikan konsistensi data
        DB::transaction(function () use ($mo) {

            // 1. KURANGI STOK KOMPONEN/BAHAN BAKU
            $bomItems = BomItem::where('bom_id', $mo->bom_id)->get();

            foreach ($bomItems as $item) {
                $quantityToConsume = $item->quantity * $mo->quantity_produced;

                // Kurangi stok utama
                Product::find($item->component_product_id)->decrement('quantity', $quantityToConsume);

                // Catat pergerakan stok keluar
                DB::table('stock_movements')->insert([
                    'product_id' => $item->component_product_id,
                    'warehouse_id' => 1, // Asumsi gudang pusat
                    'type' => 'out',
                    'quantity' => $quantityToConsume,
                    'reason' => 'Manufacturing Consumption',
                    'reference_type' => 'App\\Models\\ManufacturingOrder', // Sesuaikan jika model ada di dalam modul
                    'reference_id' => $mo->id,
                    'user_id' => $mo->created_by,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }

            // 2. TAMBAH STOK BARANG JADI
            Product::find($mo->product_id)->increment('quantity', $mo->quantity_produced);

            // Catat pergerakan stok masuk
            DB::table('stock_movements')->insert([
                'product_id' => $mo->product_id,
                'warehouse_id' => 1, // Asumsi gudang pusat
                'type' => 'in',
                'quantity' => $mo->quantity_produced,
                'reason' => 'Finished Production',
                'reference_type' => 'App\\Models\\ManufacturingOrder', // Sesuaikan jika model ada di dalam modul
                'reference_id' => $mo->id,
                'user_id' => $mo->created_by,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        });
    }
}
