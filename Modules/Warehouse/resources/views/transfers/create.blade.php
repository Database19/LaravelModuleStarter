@extends('layouts.app')

@section('content')
{{--
  Saya menggunakan Alpine.js untuk fungsionalitas dinamis di sisi klien.
  - x-data="transferForm()": Menginisialisasi state Alpine.js.
  - items: Array untuk menyimpan daftar produk yang akan ditransfer.
  - addItem(): Fungsi untuk menambahkan baris produk baru.
  - removeItem(index): Fungsi untuk menghapus baris produk berdasarkan indeksnya.
  - ":disabled="items.length === 1": Mencegah pengguna menghapus baris terakhir.
--}}
<div class="container mx-auto px-4 py-8" x-data="transferForm()">
    <div class="max-w-5xl mx-auto">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800">
                Buat Dokumen Transfer Stok 📝
            </h1>
        </div>

        <form action="{{ route('warehouse.transfers.store') }}" method="POST" class="space-y-8">
            @csrf

            <div class="bg-white p-6 md:p-8 rounded-xl shadow-lg border border-slate-200">
                <h2 class="text-xl font-semibold text-slate-700 mb-6 border-b pb-4">Detail Transfer</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="source_warehouse_id" class="block text-sm font-medium text-slate-600 mb-1">Gudang Asal*</label>
                        <select name="source_warehouse_id" id="source_warehouse_id" class="w-full mt-1 block rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            <option value="" disabled selected>Pilih Gudang Asal</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="destination_warehouse_id" class="block text-sm font-medium text-slate-600 mb-1">Gudang Tujuan*</label>
                        <select name="destination_warehouse_id" id="destination_warehouse_id" class="w-full mt-1 block rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            <option value="" disabled selected>Pilih Gudang Tujuan</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="transfer_date" class="block text-sm font-medium text-slate-600 mb-1">Tanggal Transfer*</label>
                        <input type="date" name="transfer_date" id="transfer_date" class="w-full mt-1 block rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 md:p-8 rounded-xl shadow-lg border border-slate-200">
                <h2 class="text-xl font-semibold text-slate-700 mb-6 border-b pb-4">Rincian Produk</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-100 text-slate-600">
                            <tr>
                                <th class="p-3 text-left font-semibold">Produk</th>
                                <th class="p-3 text-left font-semibold w-40">Kuantitas</th>
                                <th class="p-3 w-16"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            <template x-for="(item, index) in items" :key="index">
                                <tr class="hover:bg-slate-50">
                                    <td class="p-3">
                                        <select :name="`items[${index}][product_id]`" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" x-model="item.product_id" required>
                                            <option value="">Pilih Produk</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }} (Stok: {{ $product->stock }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-3">
                                        <input type="number" :name="`items[${index}][quantity]`" class="w-full text-center rounded-lg border-slate-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" x-model.number="item.quantity" min="1" required>
                                    </td>
                                    <td class="p-3 text-center">
                                        <button type="button" @click="removeItem(index)" :disabled="items.length === 1" class="text-slate-400 hover:text-red-500 disabled:text-slate-300 disabled:cursor-not-allowed transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <button type="button" @click="addItem()" class="mt-4 inline-flex items-center px-4 py-2 text-sm bg-indigo-500 text-white font-semibold rounded-lg hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Tambah Baris
                </button>
            </div>

            <div class="bg-white p-6 md:p-8 rounded-xl shadow-lg border border-slate-200">
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-end">
                    <div>
                         <label for="notes" class="block text-sm font-medium text-slate-600 mb-1">Catatan</label>
                         <textarea name="notes" id="notes" rows="4" class="w-full mt-1 block rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Opsional: Tambahkan catatan untuk transfer ini..."></textarea>
                    </div>
                     <div class="flex justify-end">
                         <button type="submit" class="w-full md:w-auto inline-flex justify-center items-center px-8 py-3 bg-emerald-600 text-white font-bold text-base rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Simpan Transfer
                         </button>
                     </div>
                 </div>
            </div>

        </form>
    </div>
</div>

<script>
    function transferForm() {
        return {
            // Inisialisasi dengan satu baris kosong
            items: [{ product_id: '', quantity: 1 }],

            // Fungsi untuk menambah baris baru
            addItem() {
                this.items.push({ product_id: '', quantity: 1 });
            },

            // Fungsi untuk menghapus baris, tidak akan menghapus jika hanya tersisa satu baris
            removeItem(index) {
                if (this.items.length > 1) {
                    this.items.splice(index, 1);
                }
            }
        }
    }
</script>
@endsection
