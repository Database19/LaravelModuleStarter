@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 md:p-6">
    <div class="max-w-xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Mulai Sesi Stock Opname Baru</h1>

        <form action="{{ route('warehouse.counts.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
            @csrf

            <div class="space-y-6">
                <div>
                    <label for="warehouse_id" class="block text-sm font-medium text-gray-700">Pilih Gudang*</label>
                    <select name="warehouse_id" id="warehouse_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        <option value="">-- Silakan Pilih Gudang --</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                     <p class="text-xs text-gray-500 mt-1">Sistem akan mengambil snapshot stok terkini dari gudang yang dipilih.</p>
                </div>

                <div>
                    <label for="count_date" class="block text-sm font-medium text-gray-700">Tanggal Perhitungan*</label>
                    <input type="date" name="count_date" id="count_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ now()->format('Y-m-d') }}" required>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <a href="{{ route('warehouse.counts.index') }}" class="px-6 py-2 text-sm font-medium text-gray-700 rounded-md mr-2">Batal</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700">
                    Mulai Sesi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
