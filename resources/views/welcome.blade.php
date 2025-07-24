<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Child ERP</title>
        {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
        {{-- <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"> --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .hero-gradient-text {
            background: linear-gradient(to right, #a78bfa, #38bdf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .feature-card:hover .feature-icon {
            transform: scale(1.1);
        }
    </style>
</head>
<body class="bg-slate-900 text-slate-300">

    <!-- Header -->
    <header class="bg-slate-900/70 backdrop-blur-lg sticky top-0 z-50 border-b border-slate-800">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="h-8 w-8 text-purple-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <span class="ml-3 text-2xl font-bold text-white">Synergy ERP</span>
                </div>
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-slate-400 hover:text-white transition-colors">Fitur</a>
                    <a href="#testimonials" class="text-slate-400 hover:text-white transition-colors">Testimoni</a>
                    <a href="#pricing" class="text-slate-400 hover:text-white transition-colors">Harga</a>
                </nav>
                <div class="flex items-center space-x-4">
                     <a href="/login" class="hidden md:block px-5 py-2 text-slate-300 font-medium rounded-lg hover:bg-slate-800 transition-colors">
                        Masuk
                    </a>
                    <a href="/register" class="px-5 py-2 bg-purple-600 text-white font-semibold rounded-lg shadow-md hover:bg-purple-700 transition-all transform hover:scale-105">
                        Mulai Sekarang
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <main>
        <section class="relative pt-24 pb-32 overflow-hidden">
            <div class="absolute inset-0 bg-grid-slate-800 [mask-image:linear-gradient(to_bottom,white,transparent)]"></div>
            <div class="container mx-auto px-6 text-center relative">
                <h1 class="text-5xl md:text-7xl font-extrabold text-white leading-tight tracking-tighter">
                    Sistem Operasi untuk
                    <br class="hidden md:block">
                    <span class="hero-gradient-text">Bisnis Anda.</span>
                </h1>
                <p class="mt-6 max-w-2xl mx-auto text-lg text-slate-400">
                    Hentikan penggunaan aplikasi terpisah. Synergy ERP mengintegrasikan semua modul inti—Keuangan, Penjualan, SDM, dan Inventaris—ke dalam satu platform cerdas.
                </p>
                <div class="mt-10 flex justify-center gap-4">
                    <a href="#" class="px-8 py-4 bg-purple-600 text-white font-semibold rounded-lg shadow-lg hover:bg-purple-700 transition-all transform hover:scale-105">
                        Jadwalkan Demo
                    </a>
                </div>
                <div class="mt-16">
                    <img src="https://placehold.co/1000x600/1e293b/a78bfa?text=ERP+Dashboard+Mockup" alt="ERP Dashboard Mockup" class="rounded-xl shadow-2xl mx-auto ring-1 ring-white/10">
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-24 bg-slate-800/50">
            <div class="container mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold text-white">Dirancang untuk Efisiensi</h2>
                    <p class="mt-4 text-lg text-slate-400">Satu platform untuk mengelola setiap aspek inti operasi Anda.</p>
                </div>

                <!-- Detailed Features Section -->
                <div class="text-center mt-24 mb-16">
                    <h3 class="text-3xl md:text-4xl font-bold text-white">Fitur Lengkap di Setiap Modul</h3>
                    <p class="mt-4 text-lg text-slate-400">Semua yang Anda butuhkan, terintegrasi dengan sempurna.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Module Group Card: Finance & HR -->
                    <div class="bg-slate-800 p-8 rounded-xl border border-slate-700">
                        <h3 class="text-2xl font-bold text-white mb-6">Finance & HR</h3>
                        <ul class="space-y-6">
                            <li>
                                <h4 class="font-semibold text-purple-400 text-lg">Accounting</h4>
                                <ul class="list-disc list-inside mt-2 text-slate-400 space-y-1 pl-2">
                                    <li>Laporan Keuangan (L/R, Neraca, dll)</li>
                                    <li>Chart of Accounts (COA) Hierarkis</li>
                                    <li>Jurnal Umum & Otomatis</li>
                                    <li>Manajemen Aset Tetap & Penyusutan</li>
                                    <li>Pengaturan Akun Otomatis</li>
                                </ul>
                            </li>
                            <li>
                                <h4 class="font-semibold text-purple-400 text-lg">Human Resource</h4>
                                <ul class="list-disc list-inside mt-2 text-slate-400 space-y-1 pl-2">
                                    <li>Manajemen Data Karyawan</li>
                                    <li>Proses Penggajian (Payroll)</li>
                                </ul>
                            </li>
                        </ul>
                    </div>

                    <!-- Module Group Card: Sales & Marketing -->
                    <div class="bg-slate-800 p-8 rounded-xl border border-slate-700">
                        <h3 class="text-2xl font-bold text-white mb-6">Sales & Marketing</h3>
                        <ul class="space-y-6">
                            <li>
                                <h4 class="font-semibold text-purple-400 text-lg">CRM</h4>
                                <ul class="list-disc list-inside mt-2 text-slate-400 space-y-1 pl-2">
                                    <li>Manajemen Leads</li>
                                    <li>Manajemen Opportunities</li>
                                    <li>Konversi Lead ke Opportunity</li>
                                </ul>
                            </li>
                            <li>
                                <h4 class="font-semibold text-purple-400 text-lg">Sales</h4>
                                <ul class="list-disc list-inside mt-2 text-slate-400 space-y-1 pl-2">
                                    <li>Manajemen Sales Order</li>
                                    <li>Alur Kerja (Konfirmasi, Kirim, Faktur)</li>
                                </ul>
                            </li>
                             <li>
                                <h4 class="font-semibold text-purple-400 text-lg">Point of Sales</h4>
                                <ul class="list-disc list-inside mt-2 text-slate-400 space-y-1 pl-2">
                                    <li>Transaksi Kasir</li>
                                </ul>
                            </li>
                        </ul>
                    </div>

                    <!-- Module Group Card: Operations -->
                    <div class="bg-slate-800 p-8 rounded-xl border border-slate-700">
                        <h3 class="text-2xl font-bold text-white mb-6">Operations</h3>
                        <ul class="space-y-6">
                            <li>
                                <h4 class="font-semibold text-purple-400 text-lg">Purchasing</h4>
                                <ul class="list-disc list-inside mt-2 text-slate-400 space-y-1 pl-2">
                                    <li>Manajemen Purchase Order</li>
                                    <li>Penerimaan Barang</li>
                                </ul>
                            </li>
                            <li>
                                <h4 class="font-semibold text-purple-400 text-lg">Inventory & Warehouse</h4>
                                 <ul class="list-disc list-inside mt-2 text-slate-400 space-y-1 pl-2">
                                    <li>Manajemen Produk & Stok per Gudang</li>
                                    <li>Transfer Stok Antar Gudang</li>
                                    <li>Stock Opname & Penyesuaian</li>
                                </ul>
                            </li>
                            <li>
                                <h4 class="font-semibold text-purple-400 text-lg">Manufacturing</h4>
                                <ul class="list-disc list-inside mt-2 text-slate-400 space-y-1 pl-2">
                                    <li>Bill of Materials (BOM)</li>
                                    <li>Manufacturing Orders</li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section id="testimonials" class="py-24">
            <div class="container mx-auto px-6">
                 <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold text-white">Dipercaya oleh Bisnis Terkemuka</h2>
                </div>
                <div class="max-w-3xl mx-auto text-center">
                    <p class="text-2xl text-slate-300 italic">"Synergy ERP mengubah cara kami beroperasi. Efisiensi meningkat 40% dalam tiga bulan pertama. Platform ini adalah game-changer."</p>
                    <div class="mt-8">
                        <img src="https://placehold.co/80x80/a78bfa/1e293b?text=AS" alt="Avatar" class="w-20 h-20 mx-auto rounded-full">
                        <p class="mt-4 font-bold text-white text-lg">Dede Febriansyah</p>
                        <p class="text-slate-400">Tim Pengembang</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="bg-slate-800">
            <div class="container mx-auto px-6 py-20 text-center">
                <h2 class="text-3xl md:text-4xl font-bold text-white">Siap Mengambil Kendali Penuh?</h2>
                <p class="mt-4 text-lg text-slate-400 max-w-2xl mx-auto">Integrasikan semua operasi bisnis Anda hari ini. Coba Synergy ERP gratis selama 14 hari.</p>
                <div class="mt-8">
                    <a href="#" class="px-8 py-4 bg-purple-600 text-white font-semibold rounded-lg shadow-lg hover:bg-purple-700 transition-all transform hover:scale-105">
                        Mulai Uji Coba Gratis
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 border-t border-slate-800">
        <div class="container mx-auto px-6 py-12">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">
                <div class="col-span-2 lg:col-span-1">
                     <div class="flex items-center">
                        <svg class="h-8 w-8 text-purple-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span class="ml-3 text-2xl font-bold text-white">Synergy ERP</span>
                    </div>
                    <p class="mt-4 text-slate-400 text-sm">&copy; 2025 Synergy ERP. Semua Hak Cipta Dilindungi.</p>
                </div>
                <div>
                    <h3 class="font-semibold text-white">Produk</h3>
                    <nav class="mt-4 space-y-2">
                        <a href="#features" class="text-slate-400 hover:text-white text-sm">Fitur</a><br>
                        <a href="#pricing" class="text-slate-400 hover:text-white text-sm">Harga</a><br>
                        <a href="#" class="text-slate-400 hover:text-white text-sm">Integrasi</a>
                    </nav>
                </div>
                <div>
                    <h3 class="font-semibold text-white">Perusahaan</h3>
                    <nav class="mt-4 space-y-2">
                        <a href="#" class="text-slate-400 hover:text-white text-sm">Tentang Kami</a><br>
                        <a href="#" class="text-slate-400 hover:text-white text-sm">Karir</a><br>
                        <a href="#" class="text-slate-400 hover:text-white text-sm">Kontak</a>
                    </nav>
                </div>
                <div>
                    <h3 class="font-semibold text-white">Sumber Daya</h3>
                    <nav class="mt-4 space-y-2">
                        <a href="#" class="text-slate-400 hover:text-white text-sm">Blog</a><br>
                        <a href="#" class="text-slate-400 hover:text-white text-sm">Pusat Bantuan</a><br>
                        <a href="#" class="text-slate-400 hover:text-white text-sm">Dokumentasi API</a>
                    </nav>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
