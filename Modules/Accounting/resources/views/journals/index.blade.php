@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Jurnal Umum</h1>
            <p class="mt-2 text-sm text-gray-700">Daftar semua entri jurnal yang tercatat.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('journals.create') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                Buat Jurnal Baru
            </a>
        </div>
    </div>

    <div class="mt-8 flex flex-col">
        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Tanggal</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Deskripsi</th>
                            <th class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Total</th>
                            <th class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($journals as $journal)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ \Carbon\Carbon::parse($journal->date)->format('d M Y') }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ Str::limit($journal->description, 50) }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-800 text-right font-mono">
                                Rp {{ number_format($journal->total_debit ?? 0, 2, ',', '.') }}
                            </td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 space-x-4">
                                <a href="{{ route('journals.show', $journal) }}" class="text-gray-500 hover:text-indigo-600">Lihat</a>
                                <a href="{{ route('journals.edit', $journal) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4 text-sm text-gray-500">Tidak ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
             <div class="mt-4">{{ $journals->links() }}</div>
        </div>
    </div>
</div>
@endsection
