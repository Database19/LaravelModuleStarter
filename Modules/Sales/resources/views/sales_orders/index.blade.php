@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 md:p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Sales Orders</h1>
        <a href="{{ route('sales.orders.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Buat Sales Order
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Order #</th>
                        <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pelanggan</th>
                        <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="p-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="p-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                        <th class="p-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($salesOrders as $order)
                    <tr>
                        <td class="p-4 whitespace-nowrap text-blue-600 font-semibold">{{ $order->order_number }}</td>
                        <td class="p-4 text-gray-700">{{ $order->customer->name }}</td>
                        <td class="p-4 text-gray-500">{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td>
                        <td class="p-4 text-center">
                             <span class="px-3 py-1 text-xs font-semibold rounded-full
                                @switch($order->status)
                                    @case('draft') bg-gray-200 text-gray-800 @break
                                    @case('confirmed') bg-blue-200 text-blue-800 @break
                                    @case('shipped') bg-yellow-200 text-yellow-800 @break
                                    @case('completed') bg-green-200 text-green-800 @break
                                    @case('cancelled') bg-red-200 text-red-800 @break
                                    @default bg-gray-200 text-gray-800
                                @endswitch">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="p-4 text-right font-medium text-gray-800">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td class="p-4 text-right">
                            <a href="{{ route('sales.orders.show', $order) }}" class="font-medium text-blue-600 hover:text-blue-800">Lihat</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500">Belum ada Sales Order.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-gray-50 border-t">
            {{ $salesOrders->links() }}
        </div>
    </div>
</div>
@endsection
