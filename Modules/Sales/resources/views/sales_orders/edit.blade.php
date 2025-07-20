@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 md:p-6" x-data="salesOrderForm()">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Sales Order #{{ $salesOrder->order_number }}</h1>

        <form action="{{ route('sales.orders.update', $salesOrder) }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
            @method('PUT')
            @csrf

            {{-- Order Details - Isi dengan value yang ada --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-700">Pelanggan*</label>
                    <select name="customer_id" id="customer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                         @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ $salesOrder->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="order_date" class="block text-sm font-medium text-gray-700">Tanggal Order*</label>
                    <input type="date" name="order_date" id="order_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ \Carbon\Carbon::parse($salesOrder->order_date)->format('Y-m-d') }}" required>
                </div>
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700">Salesperson*</label>
                    <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                         @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $salesOrder->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="warehouse_id" class="block text-sm font-medium text-gray-700">Gudang*</label>
                    <select name="warehouse_id" id="warehouse_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                         @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ $salesOrder->warehouse_id == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Items Table --}}
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Rincian Produk</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm mb-4">
                    {{-- ... (thead sama persis seperti di create.blade.php) ... --}}
                </table>
            </div>
            {{-- ... (Tombol + Tambah Baris, Total, Catatan, dan Tombol Simpan sama persis seperti di create.blade.php) ... --}}

        </form>
    </div>
</div>

<script>
    function salesOrderForm() {
        return {
            // Inisialisasi data dari $salesOrder
            items: @json($salesOrder->items->map(fn($item) => [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'unit_price' => (float) $item->unit_price,
                'discount' => (float) $item->discount_amount,
            ])) || [{ product_id: '', quantity: 1, unit_price: 0, discount: 0 }],

            order_discount: {{ $salesOrder->discount_amount ?? 0 }},
            tax: {{ $salesOrder->tax_amount ?? 0 }},

            // ... (sisa fungsi lainnya SAMA PERSIS dengan create.blade.php) ...
            products: @json($products->mapWithKeys(fn($p) => [$p->id => ($p->sale_price ?? $p->price)])),
            subtotal: 0,
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
