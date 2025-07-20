<?php

namespace Database\Seeders;

use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

// use Modules\Accounting\Entities\Account; // Asumsi model Account ada di modul Accounting
// use Modules\Sales\Entities\SalesOrder; // Asumsi model SalesOrder ada di modul Sales
// use Modules\Purchasing\Entities\PurchaseOrder; // Asumsi model PurchaseOrder ada di modul Purchasing
use Carbon\Carbon;

class PaymentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accountant = User::where('email', 'accounting@erp.test')->first();
        if (!$accountant) {
            $this->command->error('Accountant user not found. Please seed users first.');
            return;
        }
        $now = Carbon::now();

        // Asumsi ID Akun berdasarkan seeder Chart of Accounts Anda
        // Ganti dengan cara yang lebih dinamis jika perlu, misal: Account::where('code', '1110')->first()->id;
        $kasBankBCA = 3;
        $kasBankMandiri = 4;
        $piutangUsaha = 5;
        $utangUsaha = 21;

        // ====================================================================
        // 1. PEMBAYARAN LUNAS DARI PELANGGAN (SO-001)
        // ====================================================================
        DB::transaction(function () use ($accountant, $now, $kasBankBCA, $piutangUsaha) {
            $paymentAmount = 28305000;
            $paymentDate = now()->subDays(5);
            $salesOrder = SalesOrder::find(1); // Ambil SO ID 1

            // Buat Customer Payment
            $customerPaymentId = DB::table('customer_payments')->insertGetId([
                'customer_id' => 1,
                'sales_order_id' => $salesOrder->id,
                'payment_date' => $paymentDate,
                'amount' => $paymentAmount,
                'payment_method' => 'Bank Transfer',
                'reference_number' => 'TRF-BCA-112233',
                'notes' => 'Pelunasan ' . $salesOrder->order_number,
                'created_by' => $accountant->id,
                'updated_by' => $accountant->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Buat Journal Entry
            $journalEntryId = DB::table('journal_entries')->insertGetId([
                'journal_number' => 'JE-' . $paymentDate->format('Ymd') . '-001',
                'date' => $paymentDate,
                'description' => 'Penerimaan pelunasan piutang dari PT. Pelanggan Jaya (' . $salesOrder->order_number . ')',
                'total_debit' => $paymentAmount,
                'total_credit' => $paymentAmount,
                'referenceable_type' => 'Modules\\Sales\\Entities\\CustomerPayment', // Sesuaikan namespace
                'referenceable_id' => $customerPaymentId,
                'user_id' => $accountant->id,
                'is_posted' => true,
                'created_by' => $accountant->id,
                'updated_by' => $accountant->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Buat Journal Entry Items
            DB::table('journal_entry_items')->insert([
                // Debit: Kas bertambah
                ['journal_entry_id' => $journalEntryId, 'description' => 'Kas masuk dari pelunasan piutang', 'account_id' => $kasBankBCA, 'debit' => $paymentAmount, 'credit' => 0, 'created_by' => $accountant->id, 'updated_by' => $accountant->id, 'created_at' => $now, 'updated_at' => $now],
                // Credit: Piutang Usaha berkurang
                ['journal_entry_id' => $journalEntryId, 'description' => 'Pengurangan piutang usaha', 'account_id' => $piutangUsaha, 'debit' => 0, 'credit' => $paymentAmount, 'created_by' => $accountant->id, 'updated_by' => $accountant->id, 'created_at' => $now, 'updated_at' => $now],
            ]);
        });

        // ====================================================================
        // 2. PEMBAYARAN UTANG KE SUPPLIER (PO-001)
        // ====================================================================
        DB::transaction(function () use ($accountant, $now, $kasBankMandiri, $utangUsaha) {
            $paymentAmount = 36630000;
            $paymentDate = now()->subDays(8);
            $purchaseOrder = PurchaseOrder::find(1); // Ambil PO ID 1

            // Buat Supplier Payment
            $supplierPaymentId = DB::table('supplier_payments')->insertGetId([
                'supplier_id' => 1,
                'purchase_order_id' => $purchaseOrder->id,
                'payment_date' => $paymentDate,
                'amount' => $paymentAmount,
                'payment_method' => 'Bank Transfer',
                'reference_number' => 'MDR-OUT-556677',
                'notes' => 'Pelunasan untuk ' . $purchaseOrder->order_number,
                'created_by' => $accountant->id,
                'updated_by' => $accountant->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Buat Journal Entry
            $journalEntryId = DB::table('journal_entries')->insertGetId([
                'journal_number' => 'JE-' . $paymentDate->format('Ymd') . '-002',
                'date' => $paymentDate,
                'description' => 'Pembayaran utang kepada Supplier Makmur (' . $purchaseOrder->order_number . ')',
                'total_debit' => $paymentAmount,
                'total_credit' => $paymentAmount,
                'referenceable_type' => 'Modules\\Purchasing\\Entities\\SupplierPayment', // Sesuaikan namespace
                'referenceable_id' => $supplierPaymentId,
                'user_id' => $accountant->id,
                'is_posted' => true,
                'created_by' => $accountant->id,
                'updated_by' => $accountant->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Buat Journal Entry Items
            DB::table('journal_entry_items')->insert([
                // Debit: Utang Usaha berkurang
                ['journal_entry_id' => $journalEntryId, 'description' => 'Pengurangan utang usaha', 'account_id' => $utangUsaha, 'debit' => $paymentAmount, 'credit' => 0, 'created_by' => $accountant->id, 'updated_by' => $accountant->id, 'created_at' => $now, 'updated_at' => $now],
                // Credit: Kas berkurang
                ['journal_entry_id' => $journalEntryId, 'description' => 'Kas keluar untuk pembayaran utang', 'account_id' => $kasBankMandiri, 'debit' => 0, 'credit' => $paymentAmount, 'created_by' => $accountant->id, 'updated_by' => $accountant->id, 'created_at' => $now, 'updated_at' => $now],
            ]);
        });
    }
}
