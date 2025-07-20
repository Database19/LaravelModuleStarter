<div class="mx-auto bg-white shadow-md rounded-lg overflow-hidden mt-6">
        {{-- Tombol --}}
    <div class="px-6 py-4 flex flex-col sm:flex-row items-center justify-between bg-gray-100 border-t gap-4">
        <button type="submit" class="inline-flex items-center px-6 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-700 shadow-sm">
            💾 Simpan Purchase Order
        </button>
    </div>
    {{-- Header Form --}}
    <div class="px-6 py-6 grid grid-cols-6 gap-6 border-b bg-gray-50">
        <div class="col-span-6 sm:col-span-3">
            <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier</label>
            <select name="supplier_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-span-6 sm:col-span-3">
            <label for="warehouse_id" class="block text-sm font-medium text-gray-700">Terima di Gudang</label>
            <select name="warehouse_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-span-6 sm:col-span-3">
            <label for="order_date" class="block text-sm font-medium text-gray-700">Tanggal Order</label>
            <input type="date" name="order_date" value="{{ old('order_date', date('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>
    </div>

    {{-- Tabel Item PO --}}
    <div class="overflow-x-auto mt-1">
        <table class="min-w-full text-sm divide-y divide-gray-200">
            <button type="button" @click="addItem()" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-md hover:bg-green-700 shadow-sm">
            + Tambah Baris
        </button>
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left font-medium text-gray-700">Produk</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-700 w-24">Kuantitas</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-700 w-40">Harga Satuan</th>
                    <th class="px-6 py-3 text-right font-medium text-gray-700">Subtotal</th>
                    <th class="w-10"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <template x-for="(item, index) in items" :key="index">
                    <tr>
                        <td class="px-6 py-2">
                            <select :name="`items[${index}][product_id]`" x-model="item.product_id" @change="updatePrice(index)" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Pilih Produk</option>
                                <template x-for="product in products" :key="product.id">
                                    <option :value="product.id" x-text="product.name"></option>
                                </template>
                            </select>
                        </td>
                        <td class="px-6 py-2">
                            <input type="number" min="1" :name="`items[${index}][quantity]`" x-model.number="item.quantity" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </td>
                        <td class="px-6 py-2">
                            <input type="number" step="0.01" :name="`items[${index}][unit_cost]`" x-model.number="item.unit_cost" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </td>
                        <td class="px-6 py-2 text-right font-mono text-gray-700" x-text="formatCurrency(item.quantity * item.unit_cost)"></td>
                        <td class="px-6 py-2 text-center">
                            <button type="button" @click="removeItem(index)" x-show="items.length > 1" class="text-red-500 hover:text-red-700 text-lg font-bold">&times;</button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- Catatan dan Total --}}
    <div class="px-6 py-6 grid grid-cols-1 sm:grid-cols-5 gap-6">
        <div class="sm:col-span-3">
            <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
            <textarea name="notes" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
        </div>
        <div class="sm:col-span-2 space-y-3 bg-gray-50 p-4 rounded-lg">
            <div class="flex justify-between text-sm text-gray-700">
                <span>Subtotal</span>
                <span class="font-mono font-medium" x-text="formatCurrency(subtotal)"></span>
            </div>
            <div class="flex justify-between items-center text-sm text-gray-700">
                <label for="tax_amount" class="w-1/2">Pajak (PPN)</label>
                <input type="number" step="0.01" name="tax_amount" x-model.number="tax_amount" class="w-1/2 rounded-md border-gray-300 shadow-sm text-right">
            </div>
            <div class="flex justify-between items-center text-sm text-gray-700">
                <label for="discount_amount" class="w-1/2">Diskon</label>
                <input type="number" step="0.01" name="discount_amount" x-model.number="discount_amount" class="w-1/2 rounded-md border-gray-300 shadow-sm text-right">
            </div>
            <div class="flex justify-between items-center border-t pt-3 text-lg font-bold text-gray-900">
                <span>Total</span>
                <span class="font-mono" x-text="formatCurrency(totalAmount)"></span>
            </div>
        </div>
    </div>
</div>
