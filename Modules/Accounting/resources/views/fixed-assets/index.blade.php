@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4">
        <div class="bg-white shadow rounded-lg">
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h3 class="text-2xl font-semibold text-blue-600 flex items-center gap-2">
                    <i class="fas fa-building"></i>
                    Aset Tetap
                </h3>
                <a href="{{ route('accounting.fixed-assets.create') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm font-medium transition">
                    <i class="fas fa-plus"></i> Tambah Aset Tetap
                </a>
            </div>
            <div class="p-6">
                @if($fixedAssets->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Kode Aset</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Nama Aset</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Kategori</th>
                                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600">Nilai Perolehan</th>
                                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600">Nilai Buku</th>
                                    <th class="px-4 py-2 text-center text-xs font-semibold text-gray-600">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($fixedAssets as $asset)
                                    <tr>
                                        <td class="px-4 py-2 font-semibold text-gray-800">{{ $asset->asset_code }}</td>
                                        <td class="px-4 py-2 text-gray-700">{{ $asset->name }}</td>
                                        <td class="px-4 py-2">
                                            <span class="inline-block px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium">{{ $asset->category }}</span>
                                        </td>
                                        <td class="px-4 py-2 text-right text-green-600 font-semibold">
                                            {{ number_format($asset->acquisition_cost, 2) }}
                                        </td>
                                        <td class="px-4 py-2 text-right text-blue-600 font-semibold">
                                            {{ number_format($asset->book_value, 2) }}
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <div class="inline-flex gap-2">
                                                <a href="{{ route('accounting.fixed-assets.show', $asset) }}"
                                                   class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-2 py-1 rounded text-xs" title="Lihat">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('accounting.fixed-assets.edit', $asset) }}"
                                                   class="bg-yellow-100 text-yellow-700 hover:bg-yellow-200 px-2 py-1 rounded text-xs" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('accounting.fixed-assets.destroy', $asset) }}"
                                                      method="POST" class="inline"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus aset tetap ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-100 text-red-700 hover:bg-red-200 px-2 py-1 rounded text-xs" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="flex justify-center mt-6">
                        {{ $fixedAssets->links() }}
                    </div>
                @else
                    <div class="flex flex-col items-center py-16">
                        <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="No Data" class="w-20 mb-4 opacity-50">
                        <p class="text-gray-500 mb-4">Belum ada data aset tetap.</p>
                        <a href="{{ route('accounting.fixed-assets.create') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 text-base font-medium transition">
                            <i class="fas fa-plus"></i> Tambah Aset Tetap Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
