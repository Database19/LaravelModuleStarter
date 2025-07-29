@extends('layouts.app')

@section('header', 'Detail Manufacturing Order')

@section('content')
<div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-2xl font-semibold leading-6 text-gray-900">Detail MO: {{ $manufacturingOrder->mo_number }}</h3>
            <p class="mt-1 text-sm text-gray-600">Status: {{ $manufacturingOrder->status }}</p>
        </div>
        <div>
            <a href="{{ route('manufacturing.manufacturing-orders.index') }}" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Kembali</a>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <p class="text-sm font-medium text-gray-500">Produk</p>
                <p class="font-semibold text-gray-800">{{ $manufacturingOrder->product->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Kuantitas Produksi</p>
                <p class="font-semibold text-gray-800">{{ $manufacturingOrder->quantity_to_produce }} unit</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Tanggal Mulai</p>
                <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($manufacturingOrder->start_date)->format('d F Y') }}</p>
            </div>
        </div>

        <div class="px-6 py-4">
            <h4 class="font-medium text-gray-800">Komponen yang Dibutuhkan</h4>
            <table class="min-w-full mt-2">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Komponen</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Butuh per Unit</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Total Dibutuhkan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($manufacturingOrder->bom->items as $item)
                    <tr>
                        <td class="px-4 py-2">{{ $item->component->name }}</td>
                        <td class="px-4 py-2 text-right">{{ $item->quantity }}</td>
                        <td class="px-4 py-2 text-right font-semibold">{{ $item->quantity * $manufacturingOrder->quantity_to_produce }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
