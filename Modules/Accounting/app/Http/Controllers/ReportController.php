<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    public function index()
    {
        $reports = [
            [
                'name' => 'Laporan Laba Rugi',
                'description' => 'Melihat performa pendapatan dan beban perusahaan dalam satu periode.',
                'url' => route('reports.laba_rugi'),
                'icon' => 'fas fa-chart-line' // Contoh menggunakan Font Awesome
            ],
            [
                'name' => 'Laporan Neraca',
                'description' => 'Menampilkan posisi keuangan (Aset, Liabilitas, Ekuitas) pada titik waktu tertentu.',
                'url' => route('reports.neraca'),
                'icon' => 'fas fa-balance-scale'
            ],
            [
                'name' => 'Laporan Perubahan Modal',
                'description' => 'Merangkum perubahan pada ekuitas perusahaan selama satu periode.',
                'url' => route('reports.perubahan_modal'),
                'icon' => 'fas fa-chart-pie'
            ],
            [
                'name' => 'Buku Besar',
                'description' => 'Menampilkan rincian semua transaksi untuk setiap akun akuntansi.',
                'url' => route('reports.buku_besar'),
                'icon' => 'fas fa-book'
            ],
            [
                'name' => 'Neraca Saldo',
                'description' => 'Daftar semua akun beserta saldo debit dan kredit akhirnya untuk verifikasi.',
                'url' => route('reports.neraca_saldo'),
                'icon' => 'fas fa-check-double'
            ],
            [
                'name' => 'Mutasi Saldo Akun',
                'description' => 'Melihat riwayat transaksi mendetail untuk satu akun spesifik.',
                'url' => route('reports.mutasi_saldo'),
                'icon' => 'fas fa-history'
            ]
        ];

        // Gunakan path view dari module Anda
        return view('accounting::reports.index', compact('reports'));
    }

    public function neracaSaldo(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        $accounts = Account::with(['journalEntryItems' => function ($query) use ($startDate, $endDate) {
            $query->whereHas('journalEntry', function ($subQuery) use ($startDate, $endDate) {
                $subQuery->whereBetween('date', [$startDate, $endDate]);
            });
        }])->orderBy('account_code')->get();

        $reportData = [];
        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($accounts as $account) {
            $debit = $account->journalEntryItems->sum('debit');
            $credit = $account->journalEntryItems->sum('credit');
            $balance = $debit - $credit;

            if ($balance != 0) {
                $reportData[] = [
                    'code' => $account->account_code,
                    'name' => $account->name,
                    'debit' => $balance > 0 ? $balance : 0,
                    'credit' => $balance < 0 ? abs($balance) : 0,
                ];
                if ($balance > 0) {
                    $totalDebit += $balance;
                } else {
                    $totalCredit += abs($balance);
                }
            }
        }

        return view('accounting::reports.neraca_saldo', [
            'reportData' => $reportData,
            'totalDebit' => $totalDebit,
            'totalCredit' => $totalCredit,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    public function labaRugi(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        // Ambil akun induk untuk Pendapatan & Beban
        $pendapatanAccounts = Account::where('type', 'revenue')->whereNull('parent_id')->get();
        $bebanAccounts = Account::where('type', 'expense')->whereNull('parent_id')->get();

        $totalPendapatan = 0;
        $totalBeban = 0;

        // Proses data untuk pendapatan
        $pendapatanData = $this->buildReportHierarchy($pendapatanAccounts, $startDate, $endDate);
        foreach ($pendapatanData as $data) {
            $totalPendapatan += $data['balance'];
        }

        // Proses data untuk beban
        $bebanData = $this->buildReportHierarchy($bebanAccounts, $startDate, $endDate);
         foreach ($bebanData as $data) {
            $totalBeban += $data['balance'];
        }

        $labaRugi = $totalPendapatan - $totalBeban;

        return view('accounting::reports.laba_rugi_hierarchical', compact('pendapatanData', 'bebanData', 'totalPendapatan', 'totalBeban', 'labaRugi', 'startDate', 'endDate'));
    }

    private function getAccountBalanceByType(string $type, string $startDate, string $endDate)
    {
         $summary = DB::table('journal_entry_items')
            ->join('journal_entries', 'journal_entry_items.journal_entry_id', '=', 'journal_entries.id')
            ->join('accounts', 'journal_entry_items.account_id', '=', 'accounts.id')
            ->where('accounts.type', $type)
            ->whereBetween('journal_entries.date', [$startDate, $endDate])
            ->select(DB::raw('SUM(debit) as total_debit'), DB::raw('SUM(credit) as total_credit'))
            ->first();

        return ($summary->total_debit ?? 0) - ($summary->total_credit ?? 0);
    }

    private function calculateNetIncome(string $startDate, string $endDate)
    {
        $pendapatan = $this->getAccountBalanceByType('revenue', $startDate, $endDate);
        $beban = $this->getAccountBalanceByType('expense', $startDate, $endDate);

        return abs($pendapatan) - abs($beban);
    }

    private function getAccountBalance(int $accountId, string $endDate, string $startDate = null)
    {
        $query = DB::table('journal_entry_items')
            ->join('journal_entries', 'journal_entry_items.journal_entry_id', '=', 'journal_entries.id')
            ->where('journal_entry_items.account_id', $accountId);

        if ($startDate) {
            $query->whereBetween('journal_entries.date', [$startDate, $endDate]);
        } else {
            $query->where('journal_entries.date', '<=', $endDate);
        }

        $summary = $query->select(
                DB::raw('SUM(debit) as total_debit'),
                DB::raw('SUM(credit) as total_credit')
            )->first();

        return ($summary->total_debit ?? 0) - ($summary->total_credit ?? 0);
    }

    private function buildReportHierarchy($accounts, $startDate, $endDate, $isPeriod = true)
    {
        $data = [];
        foreach ($accounts as $account) {
            $balance = 0;
            $childrenData = [];

            if ($account->children->isNotEmpty()) {
                // Jika punya anak, rekursif panggil fungsi ini untuk anak-anaknya
                $childrenData = $this->buildReportHierarchy($account->children, $startDate, $endDate, $isPeriod);
                // Saldo akun induk adalah jumlah saldo anak-anaknya
                $balance = collect($childrenData)->sum('balance');
            } else {
                // Jika tidak punya anak (akun detail), hitung saldonya
                $balance = $this->getAccountBalance($account->id, $endDate, $isPeriod ? $startDate : null);
            }

            // Normalisasi saldo (liabilitas & ekuitas nilainya negatif/kredit)
            if (in_array($account->type, ['liability', 'equity', 'revenue'])) {
                $balance = abs($balance);
            }

            $data[] = [
                'account_name' => $account->name,
                'account_code' => $account->account_code,
                'balance' => $balance,
                'children' => $childrenData,
            ];
        }
        return $data;
    }


    public function neraca(Request $request)
    {
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        // 1. Hitung Laba/Rugi Tahun Berjalan sampai dengan endDate
        // Laba/rugi tahun berjalan adalah bagian dari Ekuitas di Neraca
        $labaRugiTahunBerjalan = $this->calculateNetIncome(Carbon::parse($endDate)->startOfYear()->toDateString(), $endDate);

        // 2. Ambil semua akun neraca
        $aset = Account::where('type', 'asset')->whereNull('parent_id')->get();
        $liabilitas = Account::where('type', 'liability')->whereNull('parent_id')->get();
        $ekuitas = Account::where('type', 'equity')->whereNull('parent_id')->get();

        // 3. Bangun hierarki dan hitung saldo akhir
        $asetData = $this->buildReportHierarchy($aset, null, $endDate, false); // false = not period-specific
        $liabilitasData = $this->buildReportHierarchy($liabilitas, null, $endDate, false);
        $ekuitasData = $this->buildReportHierarchy($ekuitas, null, $endDate, false);

        // 4. Kalkulasi Total
        $totalAset = collect($asetData)->sum('balance');
        $totalLiabilitas = collect($liabilitasData)->sum('balance');
        $totalEkuitas = collect($ekuitasData)->sum('balance') + $labaRugiTahunBerjalan;

        return view('accounting::reports.neraca_hierarchical', compact('asetData', 'liabilitasData', 'ekuitasData', 'labaRugiTahunBerjalan', 'totalAset', 'totalLiabilitas', 'totalEkuitas', 'endDate'));
    }

    // Tambahkan method ini di dalam ReportController
    public function bukuBesar(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        $accounts = Account::whereHas('journalEntryItems.journalEntry', function ($q) use ($startDate, $endDate) {
            $q->whereBetween('date', [$startDate, $endDate]);
        })->orderBy('account_code')->get();

        $reportData = [];
        foreach ($accounts as $account) {
            $openingBalance = $this->getAccountBalance($account->id, Carbon::parse($startDate)->subDay()->toDateString());

            $transactions = $account->journalEntryItems()
                ->join('journal_entries', 'journal_entry_items.journal_entry_id', '=', 'journal_entries.id')
                ->whereBetween('journal_entries.date', [$startDate, $endDate])
                ->select(
                    'journal_entry_items.*',
                    'journal_entries.date as transaction_date',
                    'journal_entries.description as transaction_description'
                )
                ->orderBy('journal_entries.date', 'asc')
                ->orderBy('journal_entries.id', 'asc')
                ->get();

            $reportData[] = [
                'account' => $account,
                'opening_balance' => $openingBalance,
                'transactions' => $transactions
            ];
        }

        return view('accounting::reports.buku_besar', compact('reportData', 'startDate', 'endDate'));
    }

    // Tambahkan method ini di dalam ReportController

    public function perubahanModal(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        // 1. Hitung Saldo Awal Modal
        $equityAccounts = Account::where('type', 'equity')->pluck('id');
        $modalAwal = $this->getAccountBalanceForAccounts($equityAccounts, Carbon::parse($startDate)->subDay()->toDateString());

        // 2. Hitung Laba/Rugi Bersih periode ini
        $labaRugiPeriodeIni = $this->calculateNetIncome($startDate, $endDate);

        // Asumsi: Setoran & Prive dicatat di akun spesifik. Ganti 'Modal Disetor' & 'Prive' dengan nama akun Anda.
        $setoranModal = 0;
        $prive = 0;

        $modalSetorAccount = Account::where('name', 'Modal Disetor')->first();
        if ($modalSetorAccount) {
            $setoranModal = abs($this->getAccountBalance($modalSetorAccount->id, $endDate, $startDate));
        }

        $priveAccount = Account::where('name', 'Prive')->first();
        if ($priveAccount) {
            $prive = $this->getAccountBalance($priveAccount->id, $endDate, $startDate);
        }

        // 3. Hitung Saldo Akhir Modal
        $modalAkhir = $modalAwal + $labaRugiPeriodeIni + $setoranModal - $prive;

        return view('accounting::reports.perubahan_modal', compact('modalAwal', 'labaRugiPeriodeIni', 'setoranModal', 'prive', 'modalAkhir', 'startDate', 'endDate'));
    }

    // Tambahkan helper function ini untuk menghitung saldo dari sekumpulan akun
    private function getAccountBalanceForAccounts($accountIds, string $endDate, string $startDate = null)
    {
        $balance = 0;
        foreach ($accountIds as $accountId) {
            $balance += $this->getAccountBalance($accountId, $endDate, $startDate);
        }
        // Saldo ekuitas normalnya kredit (negatif di kalkulasi kita), jadi kita absolutekan
        return abs($balance);
    }

    // Tambahkan method ini di dalam ReportController
    public function mutasiSaldo(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());
        $accountId = $request->input('account_id');

        $accounts = Account::orderBy('account_code')->get(); // Untuk dropdown
        $selectedAccount = null;
        $reportData = null;

        if ($accountId) {
            $selectedAccount = Account::findOrFail($accountId);

            $openingBalance = $this->getAccountBalance($selectedAccount->id, Carbon::parse($startDate)->subDay()->toDateString());

            $transactions = $selectedAccount->journalEntryItems()
                ->join('journal_entries', 'journal_entry_items.journal_entry_id', '=', 'journal_entries.id')
                ->whereBetween('journal_entries.date', [$startDate, $endDate])
                ->select(
                    'journal_entry_items.*', // Pilih semua kolom dari table items
                    'journal_entries.date as transaction_date', // Ambil tanggal dari journal_entries
                    'journal_entries.description as transaction_description' // Ambil deskripsi dari journal_entries
                )
                ->orderBy('journal_entries.date', 'asc') // Sekarang sorting bisa dilakukan
                ->orderBy('journal_entries.id', 'asc')   // Tambahan sortir by id jika tanggal sama
                ->get();

            $reportData = [
                'account' => $selectedAccount,
                'opening_balance' => $openingBalance,
                'transactions' => $transactions,
            ];
        }

        return view('accounting::reports.mutasi_saldo', compact('accounts', 'selectedAccount', 'reportData', 'startDate', 'endDate'));
    }
}
