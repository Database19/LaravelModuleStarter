@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 md:p-6">
    {{-- Header & Tombol Aksi --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Transfer #{{ $transfer->transfer_number }}
            </h1>
            <div class="mt-2 flex items-center gap-2">
                <span class="text-gray-500">Status:</span>
                <span class="px-3 py-1 text-sm font-semibold rounded-full
                    @switch($transfer->status)
                        @case('draft') bg-gray-200 text-gray-800 @break
                        @case('shipped') bg-yellow-200 text-yellow-800 @break
                        @case('received') bg-green-200 text-green-800 @break
                    @endswitch">
                    {{ ucfirst($transfer->status) }}
                </span>
            </div>
        </div>

        <div class="flex items-center gap-2">
            @if($transfer->status == 'draft')
                <form action="{{ route('warehouse.transfers.ship', $transfer) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 text-sm font-medium">Kirim Barang</button>
                </form>
            @endif

            @if($transfer->status == 'shipped')
                <form action="{{ route('warehouse.transfers.receive', $transfer) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm font-medium">Terima Barang</button>
                </form>
            @endif
        </div>
    </div>

    {{-- Detail & Item --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-b pb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-700">Dari Gudang (Asal)</h3>
                <p class="text-gray-600">{{ $transfer->sourceWarehouse->name ?? 'N/A' }}</p>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-700">Ke Gudang (Tujuan)</h3>
                <p class="text-gray-600">{{ $transfer->destinationWarehouse->name ?? 'N/A' }}</p>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-700">Tanggal Transfer</h3>
                <p class="text-gray-600">{{ $transfer->transfer_date->format('d M Y') }}</p>
            </div>
        </div>

        <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Rincian Produk</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-3 text-left font-semibold text-gray-600">Produk</th>
                            <th class="p-3 text-center font-semibold text-gray-600">Kuantitas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($transfer->items as $item)
                            <tr>
                                <td class="p-3">{{ $item->product->name ?? 'Produk Dihapus' }}</td>
                                <td class="p-3 text-center">{{ $item->quantity }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
