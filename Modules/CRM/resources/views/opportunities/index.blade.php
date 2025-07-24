@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 md:p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Manajemen Opportunities</h1>
        <a href="{{ route('crm.opportunities.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>
            Tambah Opportunity
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase">Nama Opportunity</th>
                        <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase">Pelanggan</th>
                        <th class="p-4 text-right text-xs font-semibold text-gray-600 uppercase">Perkiraan Nilai</th>
                        <th class="p-4 text-center text-xs font-semibold text-gray-600 uppercase">Tahapan</th>
                        <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase">Owner</th>
                        <th class="p-4 text-right text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($opportunities as $opportunity)
                    <tr>
                        <td class="p-4 font-medium text-gray-900">{{ $opportunity->name }}</td>
                        <td class="p-4 text-gray-700">{{ $opportunity->customer->name ?? 'N/A' }}</td>
                        <td class="p-4 text-right text-gray-600">Rp {{ number_format($opportunity->expected_value, 0, ',', '.') }}</td>
                        <td class="p-4 text-center">
                             <span class="px-3 py-1 text-xs font-semibold rounded-full
                                @switch($opportunity->stage)
                                    @case('prospecting') bg-blue-100 text-blue-800 @break
                                    @case('proposal') bg-indigo-100 text-indigo-800 @break
                                    @case('negotiation') bg-yellow-100 text-yellow-800 @break
                                    @case('won') bg-green-100 text-green-800 @break
                                    @case('lost') bg-red-100 text-red-800 @break
                                @endswitch">
                                {{ ucfirst($opportunity->stage) }}
                            </span>
                        </td>
                        <td class="p-4 text-gray-600">{{ $opportunity->owner->name ?? 'N/A' }}</td>
                        <td class="p-4 text-right">
                            <a href="{{ route('crm.opportunities.show', $opportunity) }}" class="font-medium text-blue-600 hover:text-blue-800">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500">Belum ada data opportunity.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-gray-50 border-t">
            {{ $opportunities->links() }}
        </div>
    </div>
</div>
@endsection
