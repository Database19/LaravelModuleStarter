@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Manajemen Supplier</h1>
            <p class="mt-2 text-sm text-gray-700">Daftar semua supplier.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('master-data.supplier.create') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                Tambah Supplier
            </a>
        </div>
    </div>
    <div class="mt-8 flex flex-col">
        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Nama</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Contact Person</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Email</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Telepon</th>
                            <th class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($suppliers as $supplier)
                        <tr>
                            <td class="py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $supplier->name }}</td>
                            <td class="px-3 py-4 text-sm text-gray-500">{{ $supplier->contact_person }}</td>
                            <td class="px-3 py-4 text-sm text-gray-500">{{ $supplier->email }}</td>
                            <td class="px-3 py-4 text-sm text-gray-500">{{ $supplier->phone }}</td>
                            <td class="relative py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <a href="{{ route('master-data.supplier.edit', $supplier) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                <x-delete-button :action="route('master-data.supplier.destroy', $supplier)" />
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-4">Tidak ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $suppliers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
