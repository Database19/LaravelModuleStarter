@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 md:p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Bill of Materials (BOM)</h1>
        <a href="{{ route('manufacturing.boms.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>
            Buat BOM Baru
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase">Nama BOM</th>
                        <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase">Produk Jadi</th>
                        <th class="p-4 text-right text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($boms as $bom)
                    <tr>
                        <td class="p-4 whitespace-nowrap font-medium text-gray-800">{{ $bom->bom_name }}</td>
                        <td class="p-4 text-gray-600">{{ $bom->product->name ?? 'N/A' }}</td>
                        <td class="p-4 text-right space-x-2">
                            <a href="{{ route('manufacturing.boms.show', $bom) }}" class="font-medium text-blue-600 hover:text-blue-800">Lihat</a>
                            <a href="{{ route('manufacturing.boms.edit', $bom) }}" class="font-medium text-yellow-600 hover:text-yellow-800">Edit</a>
                            <form action="{{ route('manufacturing.boms.destroy', $bom) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus BOM ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-medium text-red-600 hover:text-red-800">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="p-4 text-center text-gray-500">Belum ada Bill of Materials yang dibuat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-gray-50 border-t">
            {{ $boms->links() }}
        </div>
    </div>
</div>
@endsection
