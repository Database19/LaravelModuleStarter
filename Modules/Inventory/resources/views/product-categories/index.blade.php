@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Kategori Produk</h1>
            <p class="mt-2 text-sm text-gray-700">Kelola semua kategori untuk produk Anda.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('inventory.product-categories.create') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                Tambah Kategori
            </a>
        </div>
    </div>

    <div class="mt-8 flex flex-col">
        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Nama Kategori</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Jumlah Produk</th>
                            <th class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($categories as $category)
                        <tr>
                            <td class="py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $category->name }}</td>
                            <td class="px-3 py-4 text-sm text-gray-500">{{ $category->products_count }}</td>
                            <td class="relative py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 space-x-4">
                                <a href="{{ route('inventory.product-categories.edit', $category->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                <x-delete-button :action="route('inventory.product-categories.destroy', $category->id)" />
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-4 text-sm text-gray-500">Tidak ada data kategori.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
             <div class="mt-4">{{ $categories->links() }}</div>
        </div>
    </div>
</div>
@endsection
