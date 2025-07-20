<?php
namespace Modules\Accounting\Http\Controllers;

use App\Models\Account;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class JournalController extends Controller
{
    public function index()
    {
        $journals = JournalEntry::withSum('items as total_debit', 'debit')->latest()->paginate(15);
        return view('accounting::journals.index', compact('journals'));
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
                'user_id' => auth()->id(),
            ]);
            $journal->items()->createMany($validated['items']);
        });

        alert()->success('Berhasil!', 'Jurnal baru telah berhasil dibuat.');
        return redirect()->route('journals.index');
    }

    public function show(JournalEntry $journal)
    {
        $journal->load('items.account');

        // dd($journal);
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

        alert()->success('Berhasil!', 'Jurnal telah berhasil diperbarui.');
        return redirect()->route('journals.index');
    }

    public function destroy(JournalEntry $journal)
    {
        $journal->delete();
        alert()->success('Berhasil!', 'Jurnal telah dihapus.');
        return redirect()->route('journals.index');
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
