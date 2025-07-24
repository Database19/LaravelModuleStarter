@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 md:p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Transfer Stok</h1>
        <a href="{{ route('warehouse.transfers.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>
            Buat Transfer Baru
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase">Nomor Transfer</th>
                        <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase">Gudang Asal</th>
                        <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase">Gudang Tujuan</th>
                        <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                        <th class="p-4 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="p-4 text-right text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($transfers as $transfer)
                    <tr>
                        <td class="p-4 whitespace-nowrap text-blue-600 font-semibold">{{ $transfer->transfer_number }}</td>
                        <td class="p-4 text-gray-700">{{ $transfer->sourceWarehouse->name ?? 'N/A' }}</td>
                        <td class="p-4 text-gray-700">{{ $transfer->destinationWarehouse->name ?? 'N/A' }}</td>
                        <td class="p-4 text-gray-500">{{ $transfer->transfer_date->format('d M Y') }}</td>
                        <td class="p-4 text-center">
                             <span class="px-3 py-1 text-xs font-semibold rounded-full
                                @switch($transfer->status)
                                    @case('draft') bg-gray-200 text-gray-800 @break
                                    @case('shipped') bg-yellow-200 text-yellow-800 @break
                                    @case('received') bg-green-200 text-green-800 @break
                                @endswitch">
                                {{ ucfirst($transfer->status) }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <a href="{{ route('warehouse.transfers.show', $transfer) }}" class="font-medium text-blue-600 hover:text-blue-800">Lihat Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500">Belum ada data transfer stok.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-gray-50 border-t">
            {{ $transfers->links() }}
        </div>
    </div>
</div>
@endsection
