@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">📦 Purchase Orders</h1>
            <p class="mt-1 text-sm text-gray-600">Daftar semua pesanan pembelian ke supplier.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('purchasing.purchase-orders.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 4v16m8-8H4"/>
                </svg>
                Buat PO Baru
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg bg-white">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">PO Number</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Supplier</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white text-sm text-gray-700">
                @forelse ($purchaseOrders as $po)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $po->order_number }}</td>
                        <td class="px-6 py-4">{{ $po->supplier->name }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($po->order_date)->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-right font-mono">{{ format_currency($po->total_amount) }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-medium
                                {{ $po->status == 'Received' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                <span class="h-2 w-2 rounded-full
                                    {{ $po->status == 'Received' ? 'bg-green-500' : 'bg-yellow-500' }}"></span>
                                {{ $po->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('purchasing.purchase-orders.show', $po) }}"
                               class="text-indigo-600 hover:text-indigo-900 font-medium transition-all duration-150">Lihat</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data Purchase Order.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $purchaseOrders->links() }}
    </div>
</div>
@endsection
