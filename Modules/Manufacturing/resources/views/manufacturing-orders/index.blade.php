@extends('layouts.app')

@section('header', 'Manufacturing Orders')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Manufacturing Orders</h1>
            <p class="mt-2 text-sm text-gray-700">Daftar semua perintah kerja untuk produksi.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('manufacturing.manufacturing-orders.create') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                Buat MO Baru
            </a>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="mt-8 flex flex-col">
        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">MO Number</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Produk</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Kuantitas</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tgl. Mulai</th>
                            <th class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900">Status</th>
                            <th class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($manufacturingOrders as $mo)
                        <tr>
                            <td class="py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $mo->mo_number }}</td>
                            <td class="px-3 py-4 text-sm text-gray-500">{{ $mo->product->name ?? 'N/A' }}</td>
                            <td class="px-3 py-4 text-sm text-gray-500">{{ $mo->quantity_to_produce }}</td>
                            <td class="px-3 py-4 text-sm text-gray-500">{{ \Carbon\Carbon::parse($mo->start_date)->format('d M Y') }}</td>
                            <td class="px-3 py-4 text-sm text-gray-500 text-center">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                    {{ $mo->status == 'Completed' ? 'bg-green-100 text-green-800' : ($mo->status == 'In Progress' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ $mo->status }}
                                </span>
                            </td>
                            <td class="relative py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <a href="{{ route('manufacturing.manufacturing-orders.show', $mo) }}" class="text-indigo-600 hover:text-indigo-900">Lihat</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-4">Tidak ada data Manufacturing Order.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $manufacturingOrders->links() }}</div>
        </div>
    </div>
</div>
@endsection
