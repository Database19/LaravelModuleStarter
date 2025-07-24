@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 md:p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Stock Opname</h1>
        <a href="{{ route('warehouse.counts.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>
            Mulai Hitung Stok
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase">Nomor Sesi</th>
                        <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase">Gudang</th>
                        <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                        <th class="p-4 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase">Dibuat Oleh</th>
                        <th class="p-4 text-right text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($counts as $count)
                    <tr>
                        <td class="p-4 whitespace-nowrap text-blue-600 font-semibold">{{ $count->count_number }}</td>
                        <td class="p-4 text-gray-700">{{ $count->warehouse->name ?? 'N/A' }}</td>
                        <td class="p-4 text-gray-500">{{ $count->count_date->format('d M Y') }}</td>
                        <td class="p-4 text-center">
                             <span class="px-3 py-1 text-xs font-semibold rounded-full
                                @switch($count->status)
                                    @case('draft') bg-gray-200 text-gray-800 @break
                                    @case('counting') bg-yellow-200 text-yellow-800 @break
                                    @case('completed') bg-green-200 text-green-800 @break
                                @endswitch">
                                {{ ucfirst($count->status) }}
                            </span>
                        </td>
                        <td class="p-4 text-gray-600">{{ $count->createdBy->name ?? 'N/A' }}</td>
                        <td class="p-4 text-right">
                            <a href="{{ route('warehouse.counts.show', $count) }}" class="font-medium text-blue-600 hover:text-blue-800">
                                {{ $count->status == 'counting' ? 'Lanjutkan Hitung' : 'Lihat Detail' }}
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500">Belum ada sesi stock opname.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-gray-50 border-t">
            {{ $counts->links() }}
        </div>
    </div>
</div>
@endsection
