<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CoaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $accounts = Account::with(['parent', 'children'])
                ->select('accounts.*');

            return DataTables::eloquent($accounts)
                ->addIndexColumn()
                ->addColumn('parent_name', function($account) {
                    return $account->parent ? $account->parent->name : '-';
                })
                ->addColumn('sub_accounts_count', function($account) {
                    return $account->children->count();
                })
                ->addColumn('status', function($account) {
                    return $account->is_active ? 'aktif' : 'nonaktif';
                })
                ->editColumn('type', function($account) {
                    return $account->type;
                })
                ->editColumn('sub_type', function($account) {
                    return $account->sub_type;
                })
                ->editColumn('is_active', function($account) {
                    return $account->is_active;
                })
                ->addColumn('action', function($account) {
                    return $account->id; // hanya ID, untuk dipakai di JS
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // Handle export
        if ($request->has('export') && $request->export === 'excel') {
            return $this->exportToExcel();
        }

        return view('accounting::coas.index');
    }

    /**
     * Export data to Excel/CSV
     */
    public function exportToExcel()
    {
        $accounts = Account::with(['parent'])
            ->select(['account_code', 'name', 'type', 'sub_type', 'parent_id', 'is_active'])
            ->orderBy('account_code')
            ->get();

        $csvData = "Kode Akun,Nama Akun,Tipe,Sub Tipe,Parent,Status\n";

        foreach ($accounts as $account) {
            $csvData .= sprintf(
                '"%s","%s","%s","%s","%s","%s"' . "\n",
                $account->account_code,
                $account->name,
                ucfirst($account->type),
                $account->sub_type ? ucfirst($account->sub_type) : '-',
                $account->parent ? $account->parent->name : '-',
                $account->is_active ? 'Aktif' : 'Tidak Aktif'
            );
        }

        $filename = 'chart_of_accounts_' . date('Y-m-d_H-i-s') . '.csv';

        return response($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function create()
    {
        try {
            $parentAccounts = Account::where('company_id', Auth::user()->company_id ?? 1)
                ->whereNull('parent_id')
                ->where('is_active', true)
                ->orderBy('account_code')
                ->get();

            return view('accounting::coas.create', compact('parentAccounts'));
        } catch (\Exception $e) {
            $parentAccounts = collect();
            return view('accounting::coas.create', compact('parentAccounts'));
        }
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'account_code' => 'required|string|max:20|unique:accounts',
            'name' => 'required|string|max:255',
            'type' => 'required|in:Asset,Liability,Equity,Revenue,Expense',
            'sub_type' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:accounts,id',
            'is_active' => 'boolean'
        ]);

        try {
            Account::create([
                'account_code' => $request->account_code,
                'name' => $request->name,
                'type' => $request->type,
                'sub_type' => $request->sub_type,
                'parent_id' => $request->parent_id,
                'is_active' => $request->boolean('is_active', true),
                'company_id' => Auth::user()->company_id ?? 1,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ]);

            return redirect()->route('accounting.coa.index')
                ->with('success', 'Account created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create account: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $account = Account::where('company_id', Auth::user()->company_id ?? 1)
            ->with(['parent', 'children', 'createdBy', 'journalEntryItems'])
            ->findOrFail($id);

        return view('accounting::coas.show', compact('account'));
    }

    public function edit($id)
    {
        try {
            $account = Account::where('company_id', Auth::user()->company_id ?? 1)
                ->findOrFail($id);

            $parentAccounts = Account::where('company_id', Auth::user()->company_id ?? 1)
                ->whereNull('parent_id')
                ->where('id', '!=', $id)
                ->where('is_active', true)
                ->orderBy('account_code')
                ->get();

            return view('accounting::coas.edit', compact('account', 'parentAccounts'));
        } catch (\Exception $e) {
            return redirect()->route('accounting.coa.index')
                ->with('error', 'Account not found.');
        }
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'account_code' => 'required|string|max:20|unique:accounts,account_code,' . $id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:Asset,Liability,Equity,Revenue,Expense',
            'sub_type' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:accounts,id',
            'is_active' => 'boolean'
        ]);

        try {
            $account = Account::where('company_id', Auth::user()->company_id ?? 1)
                ->findOrFail($id);

            $account->update([
                'account_code' => $request->account_code,
                'name' => $request->name,
                'type' => $request->type,
                'sub_type' => $request->sub_type,
                'parent_id' => $request->parent_id,
                'is_active' => $request->boolean('is_active', true),
                'updated_by' => Auth::id()
            ]);

            return redirect()->route('accounting.coa.index')
                ->with('success', 'Account updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update account: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $account = Account::where('company_id', Auth::user()->company_id ?? 1)
                ->findOrFail($id);

            // Check if account has children or journal entries
            if ($account->children()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete account with sub-accounts.');
            }

            if ($account->journalEntryItems()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete account with journal entries.');
            }

            $account->delete();

            return redirect()->route('accounting.coa.index')
                ->with('success', 'Account deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete account: ' . $e->getMessage());
        }
    }

    /**
     * API endpoint to get accounts for dropdown
     */
    public function getAccountsApi()
    {
        $accounts = Account::where('is_active', true)
            ->select(['id', 'account_code', 'name', 'type'])
            ->orderBy('account_code')
            ->get();

        return response()->json($accounts);
    }
}
