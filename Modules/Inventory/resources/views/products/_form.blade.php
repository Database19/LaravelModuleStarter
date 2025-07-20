<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-4 py-5 sm:p-6">
        <div class="grid grid-cols-6 gap-6">
            <div class="col-span-6 sm:col-span-3">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="sku" class="block text-sm font-medium text-gray-700">SKU (Stock Keeping Unit)</label>
                <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="product_category_id" class="block text-sm font-medium text-gray-700">Kategori Produk</label>
                <select id="product_category_id" name="product_category_id" class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('product_category_id', $product->product_category_id ?? '') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="unit_of_measurement" class="block text-sm font-medium text-gray-700">Satuan</label>
                <input type="text" name="unit_of_measurement" id="unit_of_measurement" value="{{ old('unit_of_measurement', $product->unit_of_measurement ?? 'pcs') }}" placeholder="Contoh: pcs, kg, box" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
            </div>
             <div class="col-span-6 sm:col-span-2">
                <label for="price" class="block text-sm font-medium text-gray-700">Harga Jual</label>
                <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $product->price ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
            </div>
             <div class="col-span-6 sm:col-span-2">
                <label for="cost" class="block text-sm font-medium text-gray-700">Harga Pokok (Modal)</label>
                <input type="number" step="0.01" name="cost" id="cost" value="{{ old('cost', $product->cost ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
            </div>
            <div class="col-span-6 sm:col-span-2">
                <label for="quantity" class="block text-sm font-medium text-gray-700">Kuantitas Awal</label>
                <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $product->quantity ?? 0) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
            </div>
            <div class="col-span-6">
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description', $product->description ?? '') }}</textarea>
            </div>
        </div>
    </div>
    <div class="flex items-center justify-end gap-x-3 bg-gray-50 px-4 py-3 text-right sm:px-6">
        <a href="{{ route('inventory.products.index') }}" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
            Batal
        </a>
        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
            Simpan
        </button>
    </div>
</div>
