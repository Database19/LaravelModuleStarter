@extends('layouts.app')
@section('content')
<div class="mx-auto sm:px-6 lg:px-4" x-data='purchaseOrderForm({ products: @json($products) })'>
    <form action="{{ route('purchasing.purchase-orders.store') }}" method="POST">
        @csrf
        @include('purchasing::purchase-orders._form')
    </form>
</div>
@endsection
@push('scripts')
<script>
function purchaseOrderForm(data) {
    return {
        // Mengambil data produk dari Controller
        products: data.products || [],
        items: [{ product_id: '', quantity: 1, unit_cost: 0 }],
        tax_amount: 0,
        discount_amount: 0,


        // Fungsi untuk menambah baris item baru
        addItem() {
            this.items.push({
                product_id: '',
                quantity: 1,
                unit_cost: 0
            });
        },

        // Fungsi untuk menghapus baris item
        removeItem(index) {
            // Mencegah penghapusan baris terakhir
            if (this.items.length > 1) {
                this.items.splice(index, 1);
            }
        },

        // Fungsi untuk update harga otomatis saat produk dipilih
        updatePrice(index) {
            const product = this.products.find(p => p.id == this.items[index].product_id);
            if (product) this.items[index].unit_cost = product.cost;
        },

        get subtotal() {
            return this.items.reduce((sum, item) => sum + (item.quantity * item.unit_cost), 0);
        },

        // Properti komputasi untuk menghitung total keseluruhan
        get totalAmount() {
            const tax = parseFloat(this.tax_amount) || 0;
            const discount = parseFloat(this.discount_amount) || 0;
            return (this.subtotal + tax) - discount;
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
