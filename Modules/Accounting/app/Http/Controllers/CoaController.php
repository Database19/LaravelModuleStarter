<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;
use Modules\Accounting\Http\Requests\StoreAccountRequest;
use Modules\Accounting\Http\Requests\UpdateAccountRequest;

class CoaController extends Controller
{
    public function index()
    {
        $accounts = Account::paginate(6);
        return view('accounting::coas.index', compact('accounts'));
    }

    // Mengembalikan data JSON untuk satu akun
    public function edit(Account $accounting)
    {
        return response()->json($accounting);
    }

    // Menyimpan data baru dari modal
    public function store(StoreAccountRequest $request)
    {
        Account::create($request->validated());
        return response()->json(['message' => 'Akun baru telah berhasil ditambahkan.']);
    }

    // Memperbarui data dari modal
    public function update(UpdateAccountRequest $request, Account $accounting)
    {
        $accounting->update($request->validated());
        return response()->json(['message' => 'Data akun telah berhasil diperbarui.']);
    }

    // Metode hapus tidak perlu diubah, karena menggunakan form biasa
    public function destroy(Account $accounting)
    {
        $accounting->delete();
        alert()->success('Berhasil!', 'Akun telah dihapus.');
        return redirect()->route('coa.index');
    }
}
