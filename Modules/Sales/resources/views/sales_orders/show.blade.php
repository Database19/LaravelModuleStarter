@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 md:p-6">
    {{-- Header & Tombol Aksi --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Sales Order #{{ $salesOrder->order_number }}
            </h1>
            <div class="mt-2 flex items-center gap-2">
                <span class="text-gray-500">Status:</span>
                <span class="px-3 py-1 text-sm font-semibold rounded-full
                    @switch($salesOrder->status)
                        @case('draft') bg-gray-100 text-gray-800 @break
                        @case('confirmed') bg-blue-100 text-blue-800 @break
                        @case('shipped') bg-yellow-100 text-yellow-800 @break
                        @case('completed') bg-green-100 text-green-800 @break
                        @case('cancelled') bg-red-100 text-red-800 @break
                        @default bg-gray-100 text-gray-800
                    @endswitch">
                    {{ ucfirst($salesOrder->status) }}
                </span>
            </div>
        </div>

        {{-- Tombol Aksi Workflow --}}
        <div class="flex items-center gap-2">
            @if($salesOrder->status == 'draft')
                <a href="{{ route('sales.orders.edit', $salesOrder) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm font-medium">Edit</a>
                <form action="{{ route('sales.orders.confirm', $salesOrder) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-medium">Konfirmasi Order</button>
                </form>
            @endif

            @if($salesOrder->status == 'confirmed')
                <form action="{{ route('sales.orders.ship', $salesOrder) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 text-sm font-medium">Buat Pengiriman</button>
                </form>
            @endif

            @if($salesOrder->status == 'shipped')
                 <form action="{{ route('sales.orders.invoice', $salesOrder) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm font-medium">Buat Faktur</button>
                </form>
            @endif
        </div>
    </div>

    {{-- Detail & Item --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        {{-- Info Pelanggan & Tanggal --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-b pb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-700">Pelanggan</h3>
                <p class="text-gray-600">{{ $salesOrder->customer->name }}</p>
            </div>
            <div class="text-left md:text-right">
                <p class="text-gray-500"><strong>Tanggal Order:</strong> {{ \Carbon\Carbon::parse($salesOrder->order_date)->format('d M Y') }}</p>
                <p class="text-gray-500"><strong>Salesperson:</strong> {{ $salesOrder->user->name }}</p>
                <p class="text-gray-500"><strong>Gudang:</strong> {{ $salesOrder->warehouse->name }}</p>
            </div>
        </div>

        {{-- Tabel Rincian Produk --}}
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Rincian Pesanan</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-3 text-left font-semibold text-gray-600">Produk</th>
                            <th class="p-3 text-center font-semibold text-gray-600">Qty</th>
                            <th class="p-3 text-right font-semibold text-gray-600">Harga Satuan</th>
                            <th class="p-3 text-right font-semibold text-gray-600">Diskon</th>
                            <th class="p-3 text-right font-semibold text-gray-600">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($salesOrder->items as $item)
                            <tr>
                                <td class="p-3">{{ $item->product->name }}</td>
                                <td class="p-3 text-center">{{ $item->quantity }}</td>
                                <td class="p-3 text-right">{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td class="p-3 text-right">{{ number_format($item->discount_amount, 0, ',', '.') }}</td>
                                <td class="p-3 text-right font-medium">{{ number_format($item->total_price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Kalkulasi Total --}}
        <div class="mt-6 flex justify-end">
            <div class="w-full max-w-sm space-y-2 text-gray-700">
                <div class="flex justify-between">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($salesOrder->subtotal, 0, ',', '.') }}</span>
                </div>
                 <div class="flex justify-between">
                    <span>Diskon</span>
                    <span>- Rp {{ number_format($salesOrder->discount_amount, 0, ',', '.') }}</span>
                </div>
                 <div class="flex justify-between">
                    <span>Pajak</span>
                    <span>+ Rp {{ number_format($salesOrder->tax_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-bold text-lg text-gray-800 border-t pt-2">
                    <span>Grand Total</span>
                    <span>Rp {{ number_format($salesOrder->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
