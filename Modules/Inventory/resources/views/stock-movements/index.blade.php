@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Riwayat Pergerakan Stok</h1>
            <p class="mt-2 text-sm text-gray-700">Catatan semua transaksi stok yang masuk dan keluar dari gudang.</p>
        </div>
    </div>

    <div class="mt-8 flex flex-col">
        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Tanggal</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Produk</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tipe</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Kuantitas</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Gudang</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Alasan/Referensi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($movements as $movement)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                {{ \Carbon\Carbon::parse($movement->created_at)->format('d M Y, H:i') }}
                            </td>
                            <td class="px-3 py-4 text-sm text-gray-500">{{ $movement->product->name ?? '-' }}</td>
                            <td class="px-3 py-4 text-sm">
                                @if($movement->type == 'in')
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Masuk</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">Keluar</span>
                                @endif
                            </td>
                            <td class="px-3 py-4 text-sm text-gray-500">{{ $movement->quantity }}</td>
                            <td class="px-3 py-4 text-sm text-gray-500">{{ $movement->warehouse->name ?? '-' }}</td>
                            <td class="px-3 py-4 text-sm text-gray-500">{{ $movement->reason }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-4 text-sm text-gray-500">Tidak ada riwayat pergerakan stok.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
             <div class="mt-4">{{ $movements->links() }}</div>
        </div>
    </div>
</div>
@endsection
