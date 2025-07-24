<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FixedAsset;
use App\Models\JournalEntry;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PostDepreciationJournal extends Command
{
    protected $signature = 'depreciation:post-journal';
    protected $description = 'Calculate and post monthly depreciation journal for all active fixed assets.';

    public function handle()
    {
        $this->info('Starting monthly depreciation process...');
        $today = Carbon::today();

        // Ambil semua aset yang masih aktif dan belum sepenuhnya terdepresiasi
        $activeAssets = FixedAsset::where('status', 'active')
            ->whereDate('purchase_date', '<=', $today)
            ->get();

        if ($activeAssets->isEmpty()) {
            $this->info('No active assets to depreciate. Exiting.');
            return 0;
        }

        DB::beginTransaction();
        try {
            foreach ($activeAssets as $asset) {
                $totalDepreciated = JournalEntry::where('referenceable_type', FixedAsset::class)
                    ->where('referenceable_id', $asset->id)
                    ->join('journal_entry_items', 'journal_entries.id', '=', 'journal_entry_items.journal_entry_id')
                    ->where('journal_entry_items.account_id', $asset->accumulated_depreciation_account_id)
                    ->sum('journal_entry_items.credit');

                $depreciableValue = $asset->purchase_cost - $asset->salvage_value;
                if ($totalDepreciated >= $depreciableValue) {
                    $asset->update(['status' => 'fully_depreciated']);
                    continue;
                }

                $amountToPost = $asset->monthly_depreciation;

                if (($totalDepreciated + $amountToPost) > $depreciableValue) {
                    $amountToPost = $depreciableValue - $totalDepreciated;
                }

                if ($amountToPost <= 0) continue;

                // Buat Jurnal
                $journal = JournalEntry::create([
                    'journal_number' => 'JRN-DEP-' . $asset->asset_code . '-' . $today->format('Ym'),
                    'date' => $today->endOfMonth()->toDateString(),
                    'description' => 'Penyusutan bulanan untuk ' . $asset->asset_name,
                    'total_debit' => $amountToPost,
                    'total_credit' => $amountToPost,
                    'referenceable_type' => FixedAsset::class,
                    'referenceable_id' => $asset->id,
                    'user_id' => $asset->created_by,
                    'created_by' => $asset->created_by,
                    'updated_by' => $asset->created_by,
                ]);

                // Item Jurnal Debit (Beban)
                $journal->items()->create([
                    'account_id' => $asset->depreciation_expense_account_id,
                    'description' => 'Beban Penyusutan - ' . $asset->asset_name,
                    'debit' => $amountToPost, 'credit' => 0,
                ]);

                // Item Jurnal Kredit (Akumulasi)
                $journal->items()->create([
                    'account_id' => $asset->accumulated_depreciation_account_id,
                    'description' => 'Akumulasi Penyusutan - ' . $asset->asset_name,
                    'debit' => 0, 'credit' => $amountToPost,
                ]);
            }

            DB::commit();
            $this->info('Monthly depreciation process completed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('An error occurred: ' . $e->getMessage());
        }
    }
}
