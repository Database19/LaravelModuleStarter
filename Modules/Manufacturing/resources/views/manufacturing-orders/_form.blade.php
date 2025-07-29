<div class="bg-white shadow-md rounded-lg overflow-hidden">
    {{-- Bagian Atas Form --}}
    <div class="px-4 py-5 sm:p-6">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
                <label for="bom_id" class="block text-sm font-medium text-gray-700">Produk yang Akan Dibuat (BOM)</label>
                <select name="bom_id" x-model="selectedBomId" @change="fetchBomDetails()" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    <option value="">Pilih Bill of Materials</option>
                    @foreach($boms as $bom)
                        <option value="{{ $bom->id }}" @selected(old('bom_id', $manufacturingOrder->bom_id ?? '') == $bom->id)>
                            {{ $bom->finishedGood->name }} ({{ $bom->name }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="quantity_to_produce" class="block text-sm font-medium text-gray-700">Kuantitas Produksi</label>
                <input type="number" name="quantity_to_produce" x-model.number="quantityToProduce" value="{{ old('quantity_to_produce', $manufacturingOrder->quantity_to_produce ?? 1) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" min="1" required>
            </div>
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai Produksi</label>
                <input type="date" name="start_date" value="{{ old('start_date', isset($manufacturingOrder) ? \Carbon\Carbon::parse($manufacturingOrder->start_date)->format('Y-m-d') : date('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
            </div>
        </div>
    </div>

    {{-- Daftar Komponen yang Dibutuhkan (Dinamis) --}}
    <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
        <h3 class="text-lg font-medium text-gray-900">Komponen yang Dibutuhkan</h3>
        <div class="mt-4 flow-root">
            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Komponen</th>
                                <th class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Butuh per Unit</th>
                                <th class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Total Dibutuhkan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <template x-if="isLoading"><tr><td colspan="3" class="text-center py-4">Memuat data komponen...</td></tr></template>
                            <template x-if="!isLoading && bomItems.length === 0"><tr><td colspan="3" class="text-center py-4 text-gray-500">Pilih BOM untuk melihat komponen.</td></tr></template>
                            <template x-for="item in bomItems" :key="item.id">
                                <tr>
                                    <td class="py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6" x-text="item.component.name"></td>
                                    <td class="px-3 py-4 text-sm text-gray-500 text-right" x-text="item.quantity"></td>
                                    <td class="px-3 py-4 text-sm text-gray-500 text-right font-semibold" x-text="item.quantity * quantityToProduce"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Tombol Aksi --}}
    <div class="flex items-center justify-end gap-x-3 bg-gray-50 px-4 py-3 text-right sm:px-6">
        <a href="{{ route('manufacturing.manufacturing-orders.index') }}" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Batal</a>
        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">Simpan</button>
    </div>
</div>
