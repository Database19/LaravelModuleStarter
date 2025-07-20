@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-2xl font-semibold leading-6 text-gray-900">Detail Purchase Order</h3>
            <p class="mt-1 text-sm text-gray-600">PO Number: {{ $purchase_order->order_number }}</p>
        </div>
        <div>
            <a href="{{ route('purchasing.purchase-orders.index') }}" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Kembali</a>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm font-medium text-gray-500">Supplier</p>
                <p class="font-semibold text-gray-800">{{ $purchase_order->supplier->name }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Tanggal Order</p>
                <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($purchase_order->order_date)->format('d F Y') }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Status</p>
                <p class="font-semibold text-gray-800">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                        {{ $purchase_order->status == 'Received' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $purchase_order->status }}
                    </span>
                </p>
            </div>
        </div>
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Kuantitas</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Harga Satuan</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchase_order->items as $item)
                <tr class="border-t">
                    <td class="px-6 py-3">{{ $item->product->name }}</td>
                    <td class="px-6 py-3 text-right">{{ $item->quantity }}</td>
                    <td class="px-6 py-3 text-right font-mono">{{ format_currency($item->unit_cost) }}</td>
                    <td class="px-6 py-3 text-right font-mono">{{ format_currency($item->total_cost) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50 font-bold">
                <tr>
                    <td colspan="3" class="px-6 py-3 text-right">Total</td>
                    <td class="px-6 py-3 text-right font-mono">{{ format_currency($purchase_order->total_amount) }}</td>
                </tr>
            </tfoot>
        </table>
        @if($purchase_order->status == 'ordered')
        <div class="px-6 py-4 bg-gray-50 text-right">
             <form action="{{ route('purchasing.purchase-orders.receive', $purchase_order) }}" method="POST">
                @csrf
                <button type="submit" class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">
                    Tandai Telah Diterima
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
