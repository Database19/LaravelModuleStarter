<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\JournalEntry;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    public function index()
    {
        $payrolls = Payroll::with('employee.user')
            ->latest('payment_date')
            ->get()
            ->groupBy(function($payroll) {
                return Carbon::parse($payroll->pay_period_end_date)->format('F Y');
            });

        return view('humanresource::payrolls.index', compact('payrolls'));
    }

    /**
     * Menampilkan form untuk memulai proses penggajian satu periode.
     */
    public function create()
    {
        return view('humanresource::payrolls.create');
    }

    /**
     * Membuat SEMUA slip gaji untuk satu periode sebagai 'Pending'.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pay_period_end_date' => 'required|date',
        ]);

        $endDate = Carbon::parse($request->pay_period_end_date);
        $startDate = $endDate->copy()->startOfMonth();
        $paymentDate = $endDate->copy()->addDays(5); // Asumsi tgl gajian 5 hari setelah akhir periode

        // Cek apakah payroll untuk periode ini sudah ada
        if (Payroll::where('pay_period_end_date', $endDate->toDateString())->exists()) {
            return back()->with('error', 'Payroll untuk periode ini sudah pernah dibuat.');
        }

        DB::beginTransaction();
        try {
            $employees = Employee::whereNull('termination_date')->get();

            foreach ($employees as $employee) {
                // Kalkulasi sederhana
                $netSalary = $employee->basic_salary; // TODO: Kembangkan dengan tunjangan & potongan

                Payroll::create([
                    'employee_id' => $employee->id,
                    'pay_period_start_date' => $startDate,
                    'pay_period_end_date' => $endDate,
                    'payment_date' => $paymentDate,
                    'basic_salary' => $employee->basic_salary,
                    'net_salary' => $netSalary,
                    'status' => 'Pending',
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);
            }

            DB::commit();
            return redirect()->route('hr.payrolls.index')->with('success', 'Semua slip gaji untuk periode ' . $endDate->isoFormat('MMMM Y') . ' berhasil dibuat sebagai Pending.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Memproses semua slip gaji 'Pending' untuk satu periode & membuat Jurnal.
     */
    public function process(Request $request)
    {
        $request->validate(['period' => 'required|date']);
        $period = Carbon::parse($request->period);

        DB::beginTransaction();
        try {
            // 1. Ambil semua slip gaji 'Pending' untuk periode ini
            $pendingPayrolls = Payroll::where('pay_period_end_date', $period->toDateString())
                                      ->where('status', 'Pending')
                                      ->get();

            if ($pendingPayrolls->isEmpty()) {
                return back()->with('error', 'Tidak ada slip gaji pending untuk diproses pada periode ini.');
            }

            // 2. Kalkulasi total untuk Jurnal Akuntansi
            $totalGrossSalary = $pendingPayrolls->sum('basic_salary');
            $totalNetSalary = $pendingPayrolls->sum('net_salary');
            $totalTax = $pendingPayrolls->sum('tax_amount');

            // 3. Ambil Akun dari Pengaturan Akuntansi
            $bebanGajiAccountId = DB::table('accounting_settings')->where('key', 'default_salary_expense')->value('value');
            $utangGajiAccountId = DB::table('accounting_settings')->where('key', 'default_salary_payable')->value('value');

            if (!$bebanGajiAccountId || !$utangGajiAccountId) {
                throw new \Exception("Akun Beban Gaji atau Utang Gaji belum diatur.");
            }

            // 4. Buat Jurnal
            $journal = JournalEntry::create([
                'journal_number' => 'JRN-PAYROLL-' . $period->format('Ym'),
                'date' => $pendingPayrolls->first()->payment_date,
                'description' => 'Beban Gaji Periode ' . $period->isoFormat('MMMM Y'),
                'total_debit' => $totalGrossSalary,
                'total_credit' => $totalGrossSalary,
                'referenceable_type' => 'PayrollPeriod', // Referensi custom
                'referenceable_id' => $period->format('Ym'),
                'user_id' => auth()->id(),
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            // $journal->items()->create(['account_id' => $bebanGajiAccountId, 'debit' => $totalGrossSalary, 'credit' => 0, ...]);
            // $journal->items()->create(['account_id' => $utangGajiAccountId, 'debit' => 0, 'credit' => $totalNetSalary, ...]);
            // TODO: Tambahkan item jurnal untuk utang PPh 21 jika ada

            // 5. Update status semua slip gaji menjadi 'Paid'
            Payroll::whereIn('id', $pendingPayrolls->pluck('id'))->update(['status' => 'Paid']);

            DB::commit();
            return back()->with('success', 'Payroll berhasil diproses dan jurnal telah dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses payroll: ' . $e->getMessage());
        }
    }
}
