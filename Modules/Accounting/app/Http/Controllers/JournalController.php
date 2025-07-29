<?php
namespace Modules\Accounting\Http\Controllers;

use App\Models\Account;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class JournalController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $journals = JournalEntry::with(['items'])
                ->withSum('items as total_debit', 'debit')
                ->withSum('items as total_credit', 'credit')
                ->select('journal_entries.*');

            return DataTables::eloquent($journals)
                ->addIndexColumn()
                ->addColumn('status', function($journal) {
                    return $journal->is_posted ? 'posted' : 'draft';
                })
                ->editColumn('date', function($journal) {
                    return $journal->date;
                })
                ->editColumn('total_debit', function($journal) {
                    return $journal->total_debit ?: 0;
                })
                ->editColumn('total_credit', function($journal) {
                    return $journal->total_credit ?: 0;
                })
                ->addColumn('entries', function($journal) {
                    return $journal->items->map(function($item) {
                        return [
                            'account_id' => $item->account_id,
                            'description' => $item->description,
                            'debit' => $item->debit,
                            'credit' => $item->credit
                        ];
                    });
                })
                ->addColumn('action', function($journal) {
                    return $journal->id;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // Handle export
        if ($request->has('export') && $request->export === 'excel') {
            return $this->exportToExcel();
        }

        return view('accounting::journals.index');
    }

    /**
     * Export data to Excel/CSV
     */
    public function exportToExcel()
    {
        $journals = JournalEntry::with(['items'])
            ->withSum('items as total_debit', 'debit')
            ->withSum('items as total_credit', 'credit')
            ->orderBy('date', 'desc')
            ->get();

        $csvData = "Tanggal,Deskripsi,Total Debit,Total Kredit,Status\n";

        foreach ($journals as $journal) {
            $csvData .= sprintf(
                '"%s","%s","%s","%s","%s"' . "\n",
                $journal->date,
                $journal->description,
                number_format($journal->total_debit ?: 0, 2),
                number_format($journal->total_credit ?: 0, 2),
                $journal->is_posted ? 'Posted' : 'Draft'
            );
        }

        $filename = 'journals_' . date('Y-m-d_H-i-s') . '.csv';

        return response($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function create()
    {
        $accounts = Account::where('is_active', true)->orderBy('account_code')->get();
        return view('accounting::journals.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateJournal($request);

        DB::transaction(function () use ($validated) {
            $journal = JournalEntry::create([
                'date' => $validated['date'],
                'description' => $validated['description'],
                'user_id' => Auth::id(),
            ]);
            $journal->items()->createMany($validated['items']);
        });

        alert()->success('Berhasil!', 'Jurnal baru telah berhasil dibuat.');
        return redirect()->route('accounting.journals.index');
    }

    public function show(JournalEntry $journal)
    {
        $journal->load('items.account');
        return view('accounting::journals.show', compact('journal'));
    }

    public function edit(JournalEntry $journal)
    {
        $journal->load('items');
        $accounts = Account::where('is_active', true)->orderBy('account_code')->get();
        return view('accounting::journals.edit', compact('journal', 'accounts'));
    }

    public function update(Request $request, JournalEntry $journal)
    {
        $validated = $this->validateJournal($request);

        DB::transaction(function () use ($validated, $journal) {
            $journal->update([
                'date' => $validated['date'],
                'description' => $validated['description'],
            ]);
            $journal->items()->delete();
            $journal->items()->createMany($validated['items']);
        });

        alert()->success('Berhasil!', 'Jurnal berhasil diperbarui.');
        return redirect()->route('accounting.journals.index');
    }

    public function destroy(JournalEntry $journal)
    {
        $journal->delete();
        alert()->success('Berhasil!', 'Jurnal telah dihapus.');
        return redirect()->route('accounting.journals.index');
    }

    private function validateJournal(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'items' => 'required|array|min:2',
            'items.*.account_id' => 'required|exists:accounts,id',
            'items.*.debit' => 'nullable|numeric|min:0|required_without:items.*.credit',
            'items.*.credit' => 'nullable|numeric|min:0|required_without:items.*.debit',
        ]);

        $totalDebit = collect($validated['items'])->sum('debit');
        $totalCredit = collect($validated['items'])->sum('credit');

        if (round($totalDebit, 2) !== round($totalCredit, 2) || $totalDebit == 0) {
            throw ValidationException::withMessages(['items' => 'Total Debit dan Kredit harus seimbang dan tidak boleh nol.']);
        }
        return $validated;
    }
}
