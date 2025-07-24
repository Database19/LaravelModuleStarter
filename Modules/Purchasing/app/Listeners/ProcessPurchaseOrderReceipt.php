<?php

namespace Modules\Purchasing\Listeners;

use App\Models\JournalEntry;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Modules\Purchasing\Events\PurchaseOrderReceived;
use App\Models\Product;
use App\Models\StockMovement;

class ProcessPurchaseOrderReceipt implements ShouldQueue
{
    public function handle(PurchaseOrderReceived $event): void
    {
        $purchaseOrder = $event->purchaseOrder;

        // Gunakan DB Transaction untuk memastikan semua proses berhasil atau dibatalkan
        DB::transaction(function () use ($purchaseOrder) {
            // $this->updateInventoryStock($purchaseOrder);
            $this->createJournalEntry($purchaseOrder);
        });
    }

    /**
     * Memperbarui stok di modul Inventory.
     */
    // private function updateInventoryStock(object $purchaseOrder): void
    // {
    //     foreach ($purchaseOrder->items as $item) {
    //         $product = Product::find($item->product_id);
    //         if (!$product) continue;

    //         // 1. Dapatkan kuantitas saat ini SEBELUM diubah
    //         $quantityBefore = $product->quantity;

    //         // 2. Tambah kuantitas di tabel master produk
    //         $product->increment('quantity', $item->quantity);

    //         // 3. Hitung kuantitas SETELAH diubah
    //         $quantityAfter = $quantityBefore + $item->quantity;

    //         // 4. Buat catatan pergerakan stok dengan semua data yang dibutuhkan
    //         // dd($purchaseOrder->warehouse);
    //         StockMovement::create([
    //             // Buat reference number unik, misal: PO-NUMBER-PRODUCT-ID
    //             'reference_number' => $purchaseOrder->order_number . '-' . $item->product_id,
    //             'product_id' => $item->product_id,
    //             'warehouse_id' => $purchaseOrder->warehouse_id,
    //             'type' => 'in',
    //             'quantity' => $item->quantity,
    //             'quantity_before' => $quantityBefore,
    //             'quantity_after' => $quantityAfter,
    //             'reason' => 'Purchase Receipt from PO',
    //             'user_id' => $purchaseOrder->user_id,
    //             'reference_type' => get_class($purchaseOrder),
    //             'reference_id' => $purchaseOrder->id,
    //             'movement_date' => $purchaseOrder->received_date ?? now(),
    //             'created_by' => $purchaseOrder->created_by,
    //             'updated_by' => $purchaseOrder->updated_by,
    //         ]);
    //     }
    // }

    /**
     * Membuat entri jurnal di modul Accounting.
     */
    private function createJournalEntry(object $purchaseOrder): void
    {
        // Asumsi ID Akun:
        // 7  => Persediaan Barang Jadi
        // 12 => PPN Masukan
        // 21 => Utang Usaha

        // Ambil data kalkulasi dari PO
        $subtotal = $purchaseOrder->subtotal;
        $taxAmount = $purchaseOrder->tax_amount;
        $totalCredit = $subtotal + $taxAmount;

        // Buat entri untuk header jurnal
        $journal = JournalEntry::create([
            // Kolom Baru
            'journal_number' => 'JV-PO-' . $purchaseOrder->order_number,
            'total_debit' => $totalCredit, // Total debit sama dengan total kredit
            'total_credit' => $totalCredit,
            'created_by' => $purchaseOrder->created_by,
            'updated_by' => $purchaseOrder->updated_by,
            'is_posted' => 1, // Langsung di-post karena ini transaksi otomatis

            // Kolom Lama
            'date' => $purchaseOrder->received_date ?? $purchaseOrder->order_date,
            'description' => 'Pembelian dari ' . $purchaseOrder->supplier->name . ' (PO: ' . $purchaseOrder->order_number . ')',
            'referenceable_type' => get_class($purchaseOrder),
            'referenceable_id' => $purchaseOrder->id,
            'user_id' => $purchaseOrder->user_id,
        ]);

        // Buat item jurnal dengan deskripsi per baris
        $journal->items()->createMany([
            [
                'account_id' => 7,
                'description' => 'Penambahan persediaan barang',
                'debit' => $subtotal,
                'credit' => 0,
                'created_by' => $purchaseOrder->created_by,
                'updated_by' => $purchaseOrder->updated_by,
            ],
            [
                'account_id' => 12,
                'description' => 'PPN Masukan atas pembelian',
                'debit' => $taxAmount,
                'credit' => 0,
                'created_by' => $purchaseOrder->created_by,
                'updated_by' => $purchaseOrder->updated_by,
            ],
            [
                'account_id' => 25,
                'description' => 'Utang usaha kepada ' . $purchaseOrder->supplier->name,
                'debit' => 0,
                'credit' => $totalCredit,
                'created_by' => $purchaseOrder->created_by,
                'updated_by' => $purchaseOrder->updated_by,
            ],
        ]);
    }
}
