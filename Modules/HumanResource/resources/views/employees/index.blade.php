@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 md:p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Manajemen Karyawan</h1>
        <a href="{{ route('humanresource.employees.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Tambah Karyawan
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama</th>
                        <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jabatan</th>
                        <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Departemen</th>
                        <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal Masuk</th>
                        <th class="p-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($employees as $employee)
                    <tr>
                        <td class="p-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0">
                                    {{-- Placeholder untuk foto profil --}}
                                    <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($employee->user->name) }}&color=7F9CF5&background=EBF4FF" alt="">
                                </div>
                                <div class="ml-4">
                                    <div class="font-medium text-gray-900">{{ $employee->user->name }}</div>
                                    <div class="text-gray-500">{{ $employee->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 text-gray-700">{{ $employee->job_title }}</td>
                        <td class="p-4 text-gray-700">{{ $employee->department->name ?? 'N/A' }}</td>
                        <td class="p-4 text-gray-500">{{ $employee->hire_date->format('d M Y') }}</td>
                        <td class="p-4 text-right">
                            <a href="{{ route('humanresource.employees.show', $employee) }}" class="font-medium text-blue-600 hover:text-blue-800">Lihat</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500">Belum ada data karyawan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-gray-50 border-t">
            {{ $employees->links() }}
        </div>
    </div>
</div>
@endsection
