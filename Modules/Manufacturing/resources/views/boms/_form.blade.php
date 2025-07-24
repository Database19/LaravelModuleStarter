{{-- Menyiapkan data awal untuk Alpine.js di dalam blok PHP --}}
@php
    $initialItems = old('items', isset($bom) ? $bom->items->map(fn($item) => [
        'component_product_id' => $item->component_product_id,
        'quantity' => $item->quantity
    ]) : [['component_product_id' => '', 'quantity' => 1]]);
@endphp

<div class="space-y-6" x-data="bomForm()">
    {{-- Detail Utama BOM --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="bom_name" class="block text-sm font-medium text-gray-700">Nama Bill of Materials*</label>
            <input type="text" name="bom_name" id="bom_name" value="{{ old('bom_name', $bom->bom_name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>
        <div>
            <label for="product_id" class="block text-sm font-medium text-gray-700">Produk Jadi*</label>
            @if(isset($bom))
                {{-- Saat edit, field ini tidak bisa diubah --}}
                <input type="text" value="{{ $finishedProduct->name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" disabled>
                <input type="hidden" name="product_id" value="{{ $bom->product_id }}">
            @else
                {{-- Saat create, user bisa memilih --}}
                <select name="product_id" id="product_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    <option value="">Pilih Produk Jadi</option>
                    @foreach($finishedProducts as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            @endif
        </div>
        <div class="md:col-span-2">
            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $bom->description ?? '') }}</textarea>
        </div>
    </div>

    {{-- Tabel Komponen/Bahan Baku --}}
    <h3 class="text-lg font-semibold text-gray-800 pt-4 border-t">Komponen (Bahan Baku)</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-2 text-left font-semibold text-gray-600">Bahan Baku</th>
                    <th class="p-2 text-left font-semibold text-gray-600 w-32">Kuantitas</th>
                    <th class="p-2 w-12"></th>
                </tr>
            </thead>
            <tbody>
                <template x-for="(item, index) in items" :key="index">
                    <tr class="border-b">
                        <td class="p-2">
                            <select :name="`items[${index}][component_product_id]`" class="w-full rounded-md border-gray-300" x-model="item.component_product_id" required>
                                <option value="">Pilih Bahan Baku</option>
                                @foreach($rawMaterials as $material)
                                <option value="{{ $material->id }}">{{ $material->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="p-2"><input type="number" :name="`items[${index}][quantity]`" class="w-full text-center rounded-md border-gray-300" x-model.number="item.quantity" min="0.0001" step="0.0001" required></td>
                        <td class="p-2 text-center"><button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-700 font-bold">&times;</button></td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
    <button type="button" @click="addItem()" class="px-3 py-1 text-sm bg-blue-500 text-white rounded-md hover:bg-blue-600">+ Tambah Komponen</button>
</div>

<script>
    function bomForm() {
        return {
            items: @json($initialItems),
            addItem() {
                this.items.push({ component_product_id: '', quantity: 1 });
            },
            removeItem(index) {
                this.items.splice(index, 1);
            }
        }
    }
</script>
