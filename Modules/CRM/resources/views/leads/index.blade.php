@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 md:p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Manajemen Leads</h1>
        <a href="{{ route('crm.leads.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>
            Tambah Lead
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase">Nama</th>
                        <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase">Perusahaan</th>
                        <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase">Sumber</th>
                        <th class="p-4 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase">Owner</th>
                        <th class="p-4 text-right text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($leads as $lead)
                    <tr>
                        <td class="p-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ $lead->name }}</div>
                            <div class="text-gray-500">{{ $lead->email }}</div>
                        </td>
                        <td class="p-4 text-gray-700">{{ $lead->company_name ?? 'N/A' }}</td>
                        <td class="p-4 text-gray-600">{{ $lead->source ?? 'N/A' }}</td>
                        <td class="p-4 text-center">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                @switch($lead->status)
                                    @case('new') bg-blue-100 text-blue-800 @break
                                    @case('contacted') bg-yellow-100 text-yellow-800 @break
                                    @case('qualified') bg-green-100 text-green-800 @break
                                    @case('lost') bg-red-100 text-red-800 @break
                                @endswitch">
                                {{ ucfirst($lead->status) }}
                            </span>
                        </td>
                        <td class="p-4 text-gray-600">{{ $lead->owner->name ?? 'N/A' }}</td>
                        <td class="p-4 text-right">
                            <a href="{{ route('crm.leads.show', $lead) }}" class="font-medium text-blue-600 hover:text-blue-800">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500">Belum ada data lead.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-gray-50 border-t">
            {{ $leads->links() }}
        </div>
    </div>
</div>
@endsection
