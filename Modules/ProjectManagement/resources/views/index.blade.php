@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 md:p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Manajemen Proyek</h1>
        <a href="{{ route('project.management.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>
            Buat Proyek Baru
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase">Nama Proyek</th>
                        <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase">Pelanggan</th>
                        <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase">Manajer Proyek</th>
                        <th class="p-4 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="p-4 text-right text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($projects as $project)
                    <tr>
                        <td class="p-4">
                            <div class="font-medium text-gray-900">{{ $project->name }}</div>
                            <div class="text-gray-500 text-xs">{{ $project->code }}</div>
                        </td>
                        <td class="p-4 text-gray-700">{{ $project->customer->name ?? 'Internal' }}</td>
                        <td class="p-4 text-gray-700">{{ $project->manager->name ?? 'N/A' }}</td>
                        <td class="p-4 text-center">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $project->status->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <a href="{{ route('project.management.show', $project) }}" class="font-medium text-blue-600 hover:text-blue-800">Lihat Papan Tugas</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500">Belum ada proyek yang dibuat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-gray-50 border-t">
            {{ $projects->links() }}
        </div>
    </div>
</div>
@endsection
