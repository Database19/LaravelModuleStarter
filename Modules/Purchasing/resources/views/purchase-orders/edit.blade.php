@extends('layouts.app')
@section('content')
<div class="mx-auto sm:px-6 lg:px-4" x-data="purchaseOrderForm({ products: @json($products), initialItems: @json($purchaseOrder->items->toArray()) })">
    <form action="{{ route('purchasing.purchase-orders.update', $purchaseOrder) }}" method="POST">
        @csrf
        @method('PUT')
        @include('purchasing::purchase-orders._form', ['purchaseOrder' => $purchaseOrder])
    </form>
</div>
@endsection
@push('scripts')
<script>
function purchaseOrderForm(data) {
    return {
        products: data.products || [],

        items: data.initialItems && data.initialItems.length > 0 ? data.initialItems : [{
            product_id: '',
            quantity: 1,
            unit_cost: 0
        }],

        addItem() {
            this.items.push({
                product_id: '',
                quantity: 1,
                unit_cost: 0
            });
        },

        removeItem(index) {
            if (this.items.length > 1) {
                this.items.splice(index, 1);
            }
        },

        // Fungsi untuk update harga otomatis saat produk dipilih
        updatePrice(index) {
            const productId = this.items[index].product_id;
            const product = this.products.find(p => p.id == productId);

            if (product) {
                // Ambil harga pokok (cost) dari data produk
                this.items[index].unit_cost = product.cost;
            }
        },

        // Properti komputasi untuk menghitung total keseluruhan
        get totalAmount() {
            return this.items.reduce((sum, item) => {
                const quantity = parseFloat(item.quantity) || 0;
                const unitCost = parseFloat(item.unit_cost) || 0;
                return sum + (quantity * unitCost);
            }, 0);
        },

        // Fungsi helper untuk format mata uang
        formatCurrency(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 2
            }).format(value);
        }
    }
}
</script>
@endpush
