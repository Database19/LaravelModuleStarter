<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-4 py-5 sm:p-6">
        <div class="grid grid-cols-1 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi (Opsional)</label>
                <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description', $category->description ?? '') }}</textarea>
            </div>
        </div>
    </div>
    <div class="flex items-center justify-end gap-x-3 bg-gray-50 px-4 py-3 text-right sm:px-6">
        <a href="{{ route('inventory.product-categories.index') }}" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
            Batal
        </a>
        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
            Simpan
        </button>
    </div>
</div>
