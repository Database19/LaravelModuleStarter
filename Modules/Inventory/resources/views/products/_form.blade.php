<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="p-6 space-y-6">
        {{-- Baris 1: Nama & SKU --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk*</label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="sku" class="block text-sm font-medium text-gray-700">SKU (Kode Produk)*</label>
                <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                @error('sku') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Baris 2: Kategori, Brand, Satuan --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label for="product_category_id" class="block text-sm font-medium text-gray-700">Kategori Produk*</label>
                <select name="product_category_id" id="product_category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    {{-- Asumsi $categories di-pass dari controller --}}
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ (old('product_category_id', $product->product_category_id ?? '')) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('product_category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="brand_id" class="block text-sm font-medium text-gray-700">Brand</label>
                <select name="brand_id" id="brand_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">-- Tanpa Brand --</option>
                    {{-- Asumsi $brands di-pass dari controller --}}
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ (old('brand_id', $product->brand_id ?? '')) == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="unit_id" class="block text-sm font-medium text-gray-700">Satuan*</label>
                <select name="unit_id" id="unit_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    {{-- Asumsi $units di-pass dari controller --}}
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}" {{ (old('unit_id', $product->unit_id ?? '')) == $unit->id ? 'selected' : '' }}>
                            {{ $unit->name }}
                        </option>
                    @endforeach
                </select>
                @error('unit_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Baris 3: Harga Jual & Harga Modal --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700">Harga Jual (Rp)*</label>
                <input type="number" name="price" id="price" value="{{ old('price', $product->price ?? 0) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required min="0" step="0.01">
                @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="cost" class="block text-sm font-medium text-gray-700">Harga Modal (Rp)*</label>
                <input type="number" name="cost" id="cost" value="{{ old('cost', $product->cost ?? 0) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required min="0" step="0.01">
                @error('cost') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Baris 4: Kuantitas Awal & Stok Minimum --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="quantity" class="block text-sm font-medium text-gray-700">Kuantitas Awal*</label>
                <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $product->quantity ?? 0) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required min="0" {{ isset($product) ? 'disabled' : '' }}>
                @if(isset($product))
                <p class="mt-1 text-xs text-gray-500">Kuantitas awal tidak bisa diubah. Gunakan modul Warehouse untuk penyesuaian stok.</p>
                @else
                <p class="mt-1 text-xs text-gray-500">Stok awal akan dimasukkan ke gudang default.</p>
                @endif
                @error('quantity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="min_stock" class="block text-sm font-medium text-gray-700">Stok Minimum*</label>
                <input type="number" name="min_stock" id="min_stock" value="{{ old('min_stock', $product->min_stock ?? 0) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required min="0">
                @error('min_stock') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Baris 5: Pengaturan Lain --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700">Tipe *</label>
                <select id="type" name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    <option value="product" {{ old('type', $product->type ?? 'product') == 'product' ? 'selected' : '' }}>Barang (Product)</option>
                    <option value="service" {{ old('type', $product->type ?? '') == 'service' ? 'selected' : '' }}>Jasa (Service)</option>
                    <option value="raw_material" {{ old('type', $product->type ?? '') == 'raw_material' ? 'selected' : '' }}>Bahan Mentah</option>
                </select>
            </div>
            <div>
                <label for="track_stock" class="block text-sm font-medium text-gray-700">Lacak Stok*</label>
                <select id="track_stock" name="track_stock" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    <option value="1" {{ old('track_stock', $product->track_stock ?? 1) == 1 ? 'selected' : '' }}>Ya</option>
                    <option value="0" {{ old('track_stock', $product->track_stock ?? 1) == 0 ? 'selected' : '' }}>Tidak</option>
                </select>
            </div>
            <div>
                <label for="is_active" class="block text-sm font-medium text-gray-700">Status*</label>
                <select id="is_active" name="is_active" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    <option value="1" {{ old('is_active', $product->is_active ?? 1) == 1 ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_active', $product->is_active ?? 1) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
        </div>

        {{-- Deskripsi --}}
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $product->description ?? '') }}</textarea>
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
