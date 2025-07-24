
@extends('layouts.app')

@push('styles')
<style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc; /* slate-50 */
        }
        .stage-arrow {
            content: '';
            position: absolute;
            top: 50%;
            right: -2.5rem;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-top: 12px solid transparent;
            border-bottom: 12px solid transparent;
            border-left: 12px solid #cbd5e1; /* slate-300 */
        }
        .stage-column:last-child .stage-arrow {
            display: none;
        }
        .modal-backdrop {
            transition: opacity 0.3s ease-in-out;
        }
        .modal-content {
            transition: transform 0.3s ease-in-out;
        }
    </style>
@endpush
@section('content')
<div class="container mx-auto px-4 py-8 md:py-12">
        <!-- Header -->
        <header class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-extrabold text-slate-800">Diagram Alur Kerja ERP</h1>
            <p class="mt-4 text-lg text-slate-600 max-w-3xl mx-auto">Visualisasi interaktif dari proses bisnis end-to-end, dari setup hingga pelaporan.</p>
        </header>

        <!-- Filter Section -->
        <div id="filter-container" class="bg-white rounded-xl shadow-md p-4 mb-10 top-4 z-40">
            <div class="flex flex-wrap items-center gap-3">
                <span class="font-semibold text-slate-700">Sorot berdasarkan Peran:</span>
                <!-- Filter buttons will be injected here by JS -->
            </div>
        </div>

        <!-- Main Diagram -->
        <div id="diagram-container" class="flex flex-col md:flex-row md:space-x-20 space-y-10 md:space-y-0">
            <!-- Diagram columns will be injected here by JS -->
        </div>
    </div>

    <!-- Modal for Details -->
    <div id="details-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden modal-backdrop">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col modal-content transform scale-95">
            <div class="flex justify-between items-center p-5 border-b">
                <h2 id="modal-title" class="text-2xl font-bold text-slate-800">Detail Proses</h2>
                <button id="modal-close-btn" class="text-slate-500 hover:text-slate-800">&times;</button>
            </div>
            <div id="modal-body" class="p-6 overflow-y-auto">
                <!-- Modal content will be injected here by JS -->
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const workflowData = [
                { stage: 'Setup Awal', role: 'Admin', module: 'System Settings', action: 'Login, membuat Roles & Permissions', automation: '-', output: 'Peran pengguna dengan hak akses yang jelas' },
                { stage: 'Setup Awal', role: 'Admin', module: 'System Settings', action: 'Membuat akun User untuk setiap karyawan', automation: '-', output: 'Karyawan bisa login ke sistem' },
                { stage: 'Setup Awal', role: 'Admin / Akuntan', module: 'Accounting Settings', action: 'Memetakan proses (Penjualan, Utang, dll) ke Akun COA', automation: '-', output: '"Otak" untuk semua jurnal otomatis' },
                { stage: 'Setup Awal', role: 'Admin / Staf Terkait', module: 'Master Data', action: 'Input data: Customers, Suppliers, Products, Warehouses, Departments', automation: '-', output: 'Data induk siap digunakan untuk transaksi' },
                { stage: 'Setup Awal', role: 'Akuntan', module: 'Accounting', action: 'Input Chart of Accounts (COA) & Saldo Awal', automation: '-', output: 'Laporan keuangan awal seimbang' },

                { stage: 'Operasional', role: 'Sales', module: 'CRM', action: 'Membuat Lead baru', automation: '-', output: 'Data calon pelanggan tercatat' },
                { stage: 'Operasional', role: 'Sales', module: 'CRM', action: 'Mengkonversi Lead menjadi Opportunity', automation: 'Membuat data Customer baru jika belum ada', output: 'Lead menjadi "Qualified", Opportunity baru dibuat' },
                { stage: 'Operasional', role: 'Sales', module: 'Sales', action: 'Membuat Sales Order dari Opportunity', automation: 'Mengisi data customer secara otomatis', output: 'Dokumen Sales Order (status: Draft)' },
                { stage: 'Operasional', role: 'Staf Gudang', module: 'Warehouse', action: 'Memproses "Shipment" (Pengiriman) dari SO', automation: 'Mengurangi kuantitas di `warehouse_stock`', output: 'Status SO menjadi "Shipped", Stok fisik berkurang' },
                { stage: 'Operasional', role: 'Sales / Akuntan', module: 'Sales', action: 'Membuat "Invoice" (Faktur) dari SO', automation: 'Membuat Jurnal: (D) Piutang, (K) Pendapatan; (D) HPP, (K) Persediaan', output: 'Status SO menjadi "Completed", Piutang tercatat' },
                { stage: 'Operasional', role: 'Akuntan', module: 'Accounting', action: 'Mencatat pembayaran dari pelanggan', automation: 'Membuat Jurnal: (D) Kas/Bank, (K) Piutang', output: 'Piutang lunas' },

                { stage: 'Operasional', role: 'Staf Purchasing', module: 'Purchasing', action: 'Membuat Purchase Order (PO) ke supplier', automation: '-', output: 'Dokumen Purchase Order (status: Ordered)' },
                { stage: 'Operasional', role: 'Staf Gudang', module: 'Warehouse', action: 'Memproses "Penerimaan Barang" dari PO', automation: 'Menambah kuantitas di `warehouse_stock` & Membuat Jurnal: (D) Persediaan, (K) Utang Usaha', output: 'Status PO menjadi "Received", Stok bertambah, Utang tercatat' },
                { stage: 'Operasional', role: 'Akuntan', module: 'Accounting', action: 'Mencatat pembayaran ke supplier', automation: 'Membuat Jurnal: (D) Utang Usaha, (K) Kas/Bank', output: 'Utang lunas' },

                { stage: 'Operasional', role: 'Staf Produksi', module: 'Manufacturing', action: 'Membuat Manufacturing Order (MO)', automation: '-', output: 'Dokumen Perintah Kerja (status: Draft)' },
                { stage: 'Operasional', role: 'Staf Produksi', module: 'Manufacturing', action: 'Memulai Produksi dari MO', automation: 'Mengurangi stok bahan baku dari `warehouse_stock` sesuai BOM', output: 'Status MO menjadi "In Progress"' },
                { stage: 'Operasional', role: 'Staf Produksi', module: 'Manufacturing', action: 'Menyelesaikan Produksi dari MO', automation: 'Menambah stok produk jadi ke `warehouse_stock`', output: 'Status MO menjadi "Done"' },

                { stage: 'Operasional', role: 'Staf HR', module: 'Human Resource', action: 'Membuat & memproses Payroll untuk satu periode', automation: 'Membuat Jurnal: (D) Beban Gaji, (K) Utang Gaji & Utang Pajak', output: 'Slip gaji dibuat, beban & utang gaji tercatat' },

                { stage: 'Internal', role: 'Staf Gudang', module: 'Warehouse', action: 'Membuat dokumen Stock Transfer', automation: '-', output: 'Dokumen Transfer Stok (status: Draft)' },
                { stage: 'Internal', role: 'Staf Gudang', module: 'Warehouse', action: 'Memproses hasil Stock Count', automation: 'Menyesuaikan `warehouse_stock` & Membuat Jurnal Penyesuaian Persediaan', output: 'Stok akurat, selisih tercatat di akuntansi' },
                { stage: 'Internal', role: 'Manajer Proyek', module: 'Project Management', action: 'Membuat Proyek & Tugas (Tasks)', automation: '-', output: 'Proyek dan tugas terdaftar di sistem' },

                { stage: 'Akhir Periode', role: 'Sistem (Otomatis)', module: 'Fixed Asset', action: 'Menjalankan scheduler bulanan', automation: 'Membuat Jurnal Penyusutan untuk semua aset', output: 'Beban & Akumulasi Penyusutan tercatat' },
                { stage: 'Akhir Periode', role: 'Akuntan', module: 'Accounting', action: 'Menjalankan proses Tutup Buku Akhir Tahun', automation: 'Membuat Jurnal Penutup & memindahkan laba ke Laba Ditahan', output: 'Akun Laba/Rugi menjadi nol, siap untuk periode baru' },
                { stage: 'Akhir Periode', role: 'Manajemen', module: 'Reporting', action: 'Mengakses menu Laporan Keuangan', automation: 'Mengambil & mengolah semua data transaksi secara real-time', output: 'Laporan Laba Rugi, Neraca, dll. yang up-to-date' },
            ];

            const diagramContainer = document.getElementById('diagram-container');
            const filterContainer = document.getElementById('filter-container').querySelector('div');
            const modal = document.getElementById('details-modal');
            const modalTitle = document.getElementById('modal-title');
            const modalBody = document.getElementById('modal-body');
            const modalCloseBtn = document.getElementById('modal-close-btn');

            let currentFilter = 'All';

            function renderDiagram() {
                diagramContainer.innerHTML = '';
                const stages = ['Setup Awal', 'Operasional', 'Internal', 'Akhir Periode'];
                const groupedByStage = stages.reduce((acc, stage) => {
                    acc[stage] = workflowData.filter(item => item.stage === stage);
                    return acc;
                }, {});

                for (const stageName of stages) {
                    const items = groupedByStage[stageName];
                    if (items.length > 0) {
                        diagramContainer.innerHTML += createStageColumnHTML(stageName, items);
                    }
                }
                addEventListeners();
                applyFilter();
            }

            function createStageColumnHTML(stageName, items) {
                const groupedByModule = items.reduce((acc, item) => {
                    if (!acc[item.module]) {
                        acc[item.module] = { roles: new Set(), items: [] };
                    }
                    acc[item.module].roles.add(item.role);
                    acc[item.module].items.push(item);
                    return acc;
                }, {});

                const moduleCards = Object.keys(groupedByModule).map(moduleName => {
                    const moduleData = groupedByModule[moduleName];
                    const roles = Array.from(moduleData.roles).join(', ');
                    return `
                        <div class="module-card bg-white rounded-lg shadow-md p-4 cursor-pointer border-2 border-transparent transition-all duration-300"
                             data-module="${moduleName}"
                             data-roles='${JSON.stringify(Array.from(moduleData.roles))}'>
                            <h4 class="font-bold text-slate-800">${moduleName}</h4>
                            <p class="text-xs text-slate-500 mt-1">${roles}</p>
                        </div>
                    `;
                }).join('');

                return `
                    <div class="stage-column flex-1 relative">
                        <h3 class="text-xl font-bold text-blue-600 mb-4 text-center">${stageName}</h3>
                        <div class="space-y-4">
                            ${moduleCards}
                        </div>
                        <div class="stage-arrow hidden md:block"></div>
                    </div>
                `;
            }

            function renderFilters() {
                const roles = ['All', ...new Set(workflowData.map(item => item.role))];
                filterContainer.innerHTML = roles.map(role => `
                    <button class="filter-btn px-4 py-1.5 text-sm font-semibold rounded-full transition-colors ${role === 'All' ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-700 hover:bg-slate-300'}" data-role="${role}">
                        ${role}
                    </button>
                `).join('');
            }

            function applyFilter() {
                document.querySelectorAll('.module-card').forEach(card => {
                    const roles = JSON.parse(card.dataset.roles);
                    if (currentFilter === 'All' || roles.includes(currentFilter)) {
                        card.classList.remove('opacity-30');
                        card.classList.add('border-blue-500');
                    } else {
                        card.classList.add('opacity-30');
                        card.classList.remove('border-blue-500');
                    }
                });
            }

            function openModal(moduleName) {
                const items = workflowData.filter(item => item.module === moduleName);
                modalTitle.textContent = `Detail Proses: ${moduleName}`;
                modalBody.innerHTML = items.map(item => `
                    <div class="mb-6 pb-4 border-b last:border-b-0">
                        <p class="font-bold text-lg text-slate-800">${item.action}</p>
                        <p class="text-sm text-slate-500 mb-3">Peran: ${item.role}</p>
                        <div class="text-sm">
                            <p class="font-semibold text-slate-600">Otomatisasi Sistem:</p>
                            <p class="text-slate-700 mb-2">${item.automation}</p>
                            <p class="font-semibold text-slate-600">Hasil Akhir:</p>
                            <p class="font-medium text-green-600">${item.output}</p>
                        </div>
                    </div>
                `).join('');
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    modal.querySelector('.modal-content').classList.remove('scale-95');
                }, 10);
            }

            function closeModal() {
                 modal.classList.add('opacity-0');
                 modal.querySelector('.modal-content').classList.add('scale-95');
                 setTimeout(() => modal.classList.add('hidden'), 300);
            }

            function addEventListeners() {
                filterContainer.querySelectorAll('.filter-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        currentFilter = this.dataset.role;
                        filterContainer.querySelectorAll('.filter-btn').forEach(b => {
                            b.classList.remove('bg-blue-600', 'text-white');
                            b.classList.add('bg-slate-200', 'text-slate-700', 'hover:bg-slate-300');
                        });
                        this.classList.add('bg-blue-600', 'text-white');
                        this.classList.remove('bg-slate-200', 'text-slate-700', 'hover:bg-slate-300');
                        applyFilter();
                    });
                });

                document.querySelectorAll('.module-card').forEach(card => {
                    card.addEventListener('click', () => openModal(card.dataset.module));
                });

                modalCloseBtn.addEventListener('click', closeModal);
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) closeModal();
                });
            }

            renderFilters();
            renderDiagram();
        });
    </script>
@endpush
