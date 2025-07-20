@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 md:p-6" x-data="salesOrderForm()">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Buat Sales Order Baru</h1>

        <form action="{{ route('sales.orders.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
            @csrf
            {{-- Order Details --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-700">Pelanggan*</label>
                    <select name="customer_id" id="customer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        <option value="">Pilih Pelanggan</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="order_date" class="block text-sm font-medium text-gray-700">Tanggal Order*</label>
                    <input type="date" name="order_date" id="order_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ now()->format('Y-m-d') }}" required>
                </div>
                 <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700">Salesperson*</label>
                    <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                         @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ auth()->id() == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                 <div>
                    <label for="warehouse_id" class="block text-sm font-medium text-gray-700">Gudang*</label>
                    <select name="warehouse_id" id="warehouse_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                         @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Items Table --}}
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Rincian Produk</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm mb-4">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-2 text-left font-semibold text-gray-600">Produk</th>
                            <th class="p-2 text-left font-semibold text-gray-600 w-24">Qty</th>
                            <th class="p-2 text-left font-semibold text-gray-600 w-40">Harga Satuan</th>
                            <th class="p-2 text-left font-semibold text-gray-600 w-32">Diskon Item</th>
                            <th class="p-2 text-right font-semibold text-gray-600 w-48">Subtotal</th>
                            <th class="p-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in items" :key="index">
                            <tr class="border-b">
                                <td class="p-2">
                                    <select :name="`items[${index}][product_id]`" class="w-full rounded-md border-gray-300" x-model="item.product_id" @change="updatePrice(index)" required>
                                        <option value="">Pilih Produk</option>
                                        @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->sale_price ?? $product->price }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="p-2"><input type="number" :name="`items[${index}][quantity]`" class="w-full text-center rounded-md border-gray-300" x-model.number="item.quantity" @input="calculateTotals()" min="1" required></td>
                                <td class="p-2"><input type="number" :name="`items[${index}][unit_price]`" class="w-full text-right rounded-md border-gray-300" x-model.number="item.unit_price" @input="calculateTotals()" min="0" required></td>
                                <td class="p-2"><input type="number" :name="`items[${index}][discount_amount]`" class="w-full text-right rounded-md border-gray-300" x-model.number="item.discount" @input="calculateTotals()" min="0"></td>
                                <td class="p-2 text-right" x-text="formatCurrency((item.quantity * item.unit_price) - item.discount)"></td>
                                <td class="p-2"><button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-700 font-bold">&times;</button></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <button type="button" @click="addItem()" class="px-3 py-1 text-sm bg-blue-500 text-white rounded-md hover:bg-blue-600">+ Tambah Baris</button>

            {{-- Footer & Submit --}}
            <div class="mt-6 flex flex-col md:flex-row justify-between gap-6">
                <div class="w-full md:w-1/2">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                    <textarea name="notes" id="notes" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                </div>
                <div class="w-full md:w-1/3 space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium" x-text="formatCurrency(subtotal)"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <label for="discount_amount" class="text-gray-600">Diskon Order</label>
                        <input type="number" name="discount_amount" class="w-32 text-right rounded-md border-gray-300" x-model.number="order_discount" @input="calculateTotals()" min="0">
                    </div>
                     <div class="flex justify-between items-center">
                        <label for="tax_amount" class="text-gray-600">Pajak</label>
                        <input type="number" name="tax_amount" class="w-32 text-right rounded-md border-gray-300" x-model.number="tax" @input="calculateTotals()" min="0">
                    </div>
                    <div class="flex justify-between font-bold text-lg border-t pt-2 mt-2">
                        <span>Grand Total</span>
                        <span x-text="formatCurrency(grandTotal)"></span>
                    </div>
                     <div class="mt-4">
                        <button type="submit" class="w-full px-6 py-3 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700">Simpan Sales Order</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function salesOrderForm() {
        return {
            items: [{ product_id: '', quantity: 1, unit_price: 0, discount: 0 }],
            products: @json($products->mapWithKeys(fn($p) => [$p->id => ($p->sale_price ?? $p->price)])),
            subtotal: 0,
            order_discount: 0,
            tax: 0,
            grandTotal: 0,

            init() {
                this.calculateTotals();
            },
            addItem() {
                this.items.push({ product_id: '', quantity: 1, unit_price: 0, discount: 0 });
            },
            removeItem(index) {
                this.items.splice(index, 1);
                this.calculateTotals();
            },
            updatePrice(index) {
                let productId = this.items[index].product_id;
                this.items[index].unit_price = this.products[productId] || 0;
                this.calculateTotals();
            },
            calculateTotals() {
                let calculatedSubtotal = 0;
                this.items.forEach(item => {
                    calculatedSubtotal += (item.quantity * item.unit_price) - item.discount;
                });
                this.subtotal = calculatedSubtotal;
                this.grandTotal = this.subtotal - this.order_discount + this.tax;
            },
            formatCurrency(value) {
                if (isNaN(value)) value = 0;
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
            }
        }
    }
</script>
@endsection
