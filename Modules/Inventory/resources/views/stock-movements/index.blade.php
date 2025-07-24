@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
    <header class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-bold leading-7 text-slate-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Riwayat Pergerakan Stok
                </h1>
                <p class="mt-2 text-sm text-slate-600">
                    Catatan semua transaksi stok yang masuk dan keluar dari gudang.
                </p>
            </div>
            <div class="flex md:mt-0 md:ml-4">
                {{-- Tombol Aksi (Contoh) --}}
                <button type="button" class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Export Data
                </button>
            </div>
        </div>
    </header>

    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider sm:pl-6">Tanggal</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Produk</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kuantitas</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Gudang</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Referensi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse ($movements as $movement)
                    <tr class="hover:bg-slate-50 transition-colors duration-150">
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                            <div class="font-medium text-slate-900">{{ \Carbon\Carbon::parse($movement->created_at)->format('d M Y') }}</div>
                            <div class="text-slate-500">{{ \Carbon\Carbon::parse($movement->created_at)->format('H:i:s') }}</div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                            <div class="font-medium text-slate-900">{{ $movement->product->name ?? 'N/A' }}</div>
                            <div class="text-slate-500">SKU: {{ $movement->product->sku ?? '-' }}</div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm font-bold text-center">
                            @if($movement->type == 'in')
                                <span class="text-green-600 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l.293.293a1 1 0 001.414-1.414l-3-3z" clip-rule="evenodd" /></svg>
                                    +{{ $movement->quantity }}
                                </span>
                            @else
                                <span class="text-red-600 flex items-center">
                                     <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-.707-4.707a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 1.414L9 9.586V7a1 1 0 10-2 0v2.586l-.293-.293a1 1 0 00-1.414 1.414l3 3z" clip-rule="evenodd" /></svg>
                                    -{{ $movement->quantity }}
                                </span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-600">{{ $movement->warehouse->name ?? 'N/A' }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-600">{{ $movement->reason ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="text-center py-16">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4M4 7s-2 2-2 4s2 4 2 4m16-8s2-2 2-4s-2-4-2-4" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-slate-900">Belum Ada Riwayat</h3>
                                <p class="mt-1 text-sm text-slate-500">Tidak ada data pergerakan stok yang dapat ditampilkan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($movements->hasPages())
            <div class="bg-white px-4 py-3 sm:px-6 border-t border-slate-200">
                {{ $movements->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
