@extends('layouts.app')
{{-- Ini akan mengisi @yield('content') di layout Anda --}}
@section('header')
    <!-- Card Container dari Canvas -->
    <div class="w-full max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-200 pb-4">
            <!-- Waktu & Sapaan -->
            <div x-data="timeData()">
                <h2 class="text-2xl font-bold text-slate-800" x-text="greeting"></h2>
                <p class="text-slate-500" x-text="currentTime"></p>
            </div>

            <!-- Notifikasi -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="relative text-slate-500 hover:text-slate-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V5a1 1 0 00-2 0v.083A6 6 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white">3</span>
                </button>

                <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl overflow-hidden z-10" style="display: none;">
                    <div class="p-4 font-bold border-b">Notifikasi</div>
                    <div class="divide-y">
                        <a href="#" class="block p-4 hover:bg-slate-50">
                            <p class="font-semibold text-slate-800">Sales Order Baru</p>
                            <p class="text-sm text-slate-500">Pesanan SO-202507-001 telah dikonfirmasi.</p>
                        </a>
                        <a href="#" class="block p-4 hover:bg-slate-50">
                            <p class="font-semibold text-slate-800">Stok Rendah</p>
                            <p class="text-sm text-slate-500">Produk "Kertas A4" telah mencapai batas minimum.</p>
                        </a>
                        <a href="#" class="block p-4 hover:bg-slate-50">
                            <p class="font-semibold text-slate-800">Tugas Jatuh Tempo</p>
                            <p class="text-sm text-slate-500">Tugas "Follow Up Klien B" akan jatuh tempo besok.</p>
                        </a>
                    </div>
                    <a href="#" class="block p-3 text-center bg-slate-50 text-sm font-medium text-blue-600 hover:bg-slate-100">Lihat Semua Notifikasi</a>
                </div>
            </div>
        </div>

        <!-- Data Penting (Actionable Insights) -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-slate-700 mb-4">Data Penting Hari Ini</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Data Card 1 -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm font-medium text-blue-600">Pesanan Penjualan Baru</p>
                    <p class="text-3xl font-bold text-blue-800 mt-1">5</p>
                    <a href="#" class="text-sm font-semibold text-blue-700 hover:underline mt-2 inline-block">Lihat Detail &rarr;</a>
                </div>
                <!-- Data Card 2 -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-sm font-medium text-yellow-600">Tugas Jatuh Tempo</p>
                    <p class="text-3xl font-bold text-yellow-800 mt-1">2</p>
                    <a href="#" class="text-sm font-semibold text-yellow-700 hover:underline mt-2 inline-block">Lihat Tugas &rarr;</a>
                </div>
                <!-- Data Card 3 -->
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <p class="text-sm font-medium text-red-600">Produk Stok Rendah</p>
                    <p class="text-3xl font-bold text-red-800 mt-1">8</p>
                    <a href="#" class="text-sm font-semibold text-red-700 hover:underline mt-2 inline-block">Buat PO &rarr;</a>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Ini akan mengisi @stack('scripts') di layout Anda --}}
@push('scripts')
<script>
    function timeData() {
        return {
            currentTime: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }),
            greeting: 'Selamat Pagi',
            updateTime() {
                const now = new Date();
                this.currentTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

                const hour = now.getHours();
                if (hour < 11) {
                    this.greeting = 'Selamat Pagi';
                } else if (hour < 15) {
                    this.greeting = 'Selamat Siang';
                } else if (hour < 19) {
                    this.greeting = 'Selamat Sore';
                } else {
                    this.greeting = 'Selamat Malam';
                }
            },
            init() {
                this.updateTime();
                setInterval(() => {
                    this.updateTime();
                }, 1000);
            }
        }
    }
</script>
@endpush
