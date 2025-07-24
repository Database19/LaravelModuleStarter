@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 md:p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $bom->bom_name }}</h1>
            <p class="text-lg text-gray-600">Untuk Produk: <span class="font-semibold">{{ $bom->product->name }}</span></p>
        </div>
        <a href="{{ route('manufacturing.boms.edit', $bom) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm font-medium">Edit</a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6">
            <h3 class="text-sm font-medium text-gray-500">Deskripsi</h3>
            <p class="mt-1 text-gray-900">{{ $bom->description ?? '-' }}</p>
        </div>

        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-t pt-6">Daftar Komponen</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 text-left font-semibold text-gray-600">Nama Komponen (Bahan Baku)</th>
                        <th class="p-3 text-right font-semibold text-gray-600">Kuantitas yang Dibutuhkan</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($bom->items as $item)
                        <tr>
                            <td class="p-3 font-medium text-gray-800">{{ $item->component->name ?? 'Komponen Dihapus' }}</td>
                            <td class="p-3 text-right text-gray-600">{{ $item->quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
