@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-2xl font-semibold leading-6 text-gray-900">Detail Jurnal Umum</h3>
            <p class="mt-1 text-sm text-gray-600">Jurnal #{{ $journal->id }}</p>
        </div>
        <div>
            <a href="{{ route('journals.index') }}" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Kembali</a>
            <a href="{{ route('journals.edit', $journal) }}" class="ml-3 rounded-md bg-yellow-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-yellow-400">Edit Jurnal</a>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b">
            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($journal->date)->format('d F Y') }}</p>
            <p><strong>Deskripsi:</strong> {{ $journal->description }}</p>
        </div>
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Akun</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Debit</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Kredit</th>
                </tr>
            </thead>
            <tbody>
                @foreach($journal->items as $item)
                <tr class="border-t">
                    <td class="px-6 py-3">{{ $item->account->account_code }} - {{ $item->account->name }}</td>
                    <td class="px-6 py-3 text-right">Rp {{ number_format($item->debit ?? 0, 2, ',', '.') }}</td>
                    <td class="px-6 py-3 text-right">Rp {{ number_format($item->credit ?? 0, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50 font-bold">
                <tr>
                    <td class="px-6 py-3 text-right">Total</td>
                    <td class="px-6 py-3 text-right">Rp {{ number_format($journal->items->sum('debit') ?? 0, 2, ',', '.') }}</td>
                    <td class="px-6 py-3 text-right">Rp {{ number_format($journal->items->sum('credit') ?? 0, 2, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
