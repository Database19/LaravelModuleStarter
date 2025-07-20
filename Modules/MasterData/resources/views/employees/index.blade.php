@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Manajemen Karyawan</h1>
            <p class="mt-2 text-sm text-gray-700">Daftar semua karyawan.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('master-data.employees.create') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                Tambah Karyawan
            </a>
        </div>
    </div>
    <div class="mt-8 flex flex-col">
        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3.5 pl-4 ...">Nama</th>
                            <th class="px-3 py-3.5 ...">Jabatan</th>
                            <th class="px-3 py-3.5 ...">Role</th>
                            <th class="px-3 py-3.5 ...">Email</th>
                            <th class="relative py-3.5 ..."><span class="sr-only">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($employees as $employee)
                        <tr>
                            <td class="py-4 pl-4 ...">{{ $employee->user->name }}</td>
                            <td class="px-3 py-4 ...">{{ $employee->job_title }}</td>
                            <td class="px-3 py-4 ...">{{ $employee->user->roles->first()->name ?? '-' }}</td>
                            <td class="px-3 py-4 ...">{{ $employee->user->email }}</td>
                            <td class="relative py-4 ... text-right">
                                <a href="{{ route('master-data.employees.edit', $employee) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                <x-delete-button :action="route('master-data.employees.destroy', $employee)" />
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-4">Tidak ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $employees->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
