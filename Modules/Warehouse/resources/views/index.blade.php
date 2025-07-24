@extends('layouts.app')
@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Manajemen Gudang</h1>
        <a href="{{ route('warehouse.warehouses.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Tambah Gudang</a>
    </div>
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-4 text-left font-semibold text-gray-600">Kode</th>
                    <th class="p-4 text-left font-semibold text-gray-600">Nama Gudang</th>
                    <th class="p-4 text-left font-semibold text-gray-600">Manager</th>
                    <th class="p-4 text-center font-semibold text-gray-600">Status</th>
                    <th class="p-4 text-right font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($warehouses as $warehouse)
                <tr>
                    <td class="p-4 font-mono text-gray-500">{{ $warehouse->code }}</td>
                    <td class="p-4 font-medium text-gray-800">{{ $warehouse->name }}</td>
                    <td class="p-4 text-gray-600">{{ $warehouse->manager->name ?? 'N/A' }}</td>
                    <td class="p-4 text-center">
                        @if($warehouse->is_active)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Tidak Aktif</span>
                        @endif
                    </td>
                    <td class="p-4 text-right">
                        <a href="{{ route('warehouse.warehouses.edit', $warehouse) }}" class="font-medium text-blue-600 hover:text-blue-800">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
