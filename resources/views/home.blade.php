
@extends('layouts.app')

@push('styles')
<style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
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
            border-left: 12px solid #3b82f6; /* blue-500 */
        }
        .stage-column:last-child .stage-arrow {
            display: none;
        }
        .stage-column h3 {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .module-card {
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #ffffff, #f8fafc);
        }
        .module-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        .modal-backdrop {
            transition: opacity 0.3s ease-in-out;
            backdrop-filter: blur(4px);
        }
        .modal-content {
            transition: transform 0.3s ease-in-out;
        }
        .filter-btn {
            transition: all 0.2s ease;
        }
        .filter-btn:hover {
            transform: translateY(-1px);
        }
    </style>
@endpush
@section('content')
<div class="container mx-auto px-4 py-8 md:py-12">
        <!-- Header -->
        <header class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-extrabold text-slate-800">Enterprise Resource Planning (ERP) Flow</h1>
            <p class="mt-4 text-lg text-slate-600 max-w-3xl mx-auto">Visualisasi lengkap proses bisnis end-to-end dengan 16 modul terintegrasi - dari setup, operasional harian, maintenance, hingga pelaporan strategis.</p>

            <!-- Statistics Overview -->
            <div class="mt-8 grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="text-2xl font-bold text-blue-600" id="total-modules">16</div>
                    <div class="text-sm text-slate-600">Modul Terintegrasi</div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="text-2xl font-bold text-green-600" id="total-processes">0</div>
                    <div class="text-sm text-slate-600">Proses Bisnis</div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="text-2xl font-bold text-purple-600" id="total-roles">0</div>
                    <div class="text-sm text-slate-600">Peran Pengguna</div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="text-2xl font-bold text-orange-600">6</div>
                    <div class="text-sm text-slate-600">Tahapan Utama</div>
                </div>
            </div>
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
                // === SETUP AWAL ===
                { stage: 'Setup Awal', role: 'SuperAdmin', module: 'System Settings', action: 'Setup multi-tenancy dan companies', automation: '-', output: 'Sistem multi-tenant siap, perusahaan terdaftar' },
                { stage: 'Setup Awal', role: 'Admin', module: 'System Settings', action: 'Membuat Roles & Permissions untuk setiap peran', automation: '-', output: 'Kontrol akses berbasis peran terkonfigurasi' },
                { stage: 'Setup Awal', role: 'Admin', module: 'System Settings', action: 'Membuat User accounts untuk karyawan', automation: 'Assign roles otomatis berdasarkan departemen', output: 'Karyawan dapat login dengan hak akses sesuai peran' },
                { stage: 'Setup Awal', role: 'Admin', module: 'MasterData', action: 'Setup master data: Customers, Suppliers, Categories', automation: '-', output: 'Data referensi dasar tersedia' },
                { stage: 'Setup Awal', role: 'Inventory Manager', module: 'MasterData', action: 'Input master Products, Warehouses, dan Units', automation: '-', output: 'Katalog produk dan gudang siap untuk transaksi' },
                { stage: 'Setup Awal', role: 'Akuntan', module: 'Accounting', action: 'Setup Chart of Accounts (COA) dan saldo awal', automation: '-', output: 'Struktur akuntansi dan neraca awal seimbang' },
                { stage: 'Setup Awal', role: 'Akuntan', module: 'Accounting', action: 'Konfigurasi pengaturan akuntansi dan mapping akun', automation: '-', output: 'Auto-journal mapping untuk semua transaksi' },
                { stage: 'Setup Awal', role: 'HR Manager', module: 'HumanResource', action: 'Setup data employees, departments, positions', automation: '-', output: 'Database karyawan lengkap untuk payroll dan attendance' },

                // === OPERASIONAL HARIAN ===
                { stage: 'Operasional', role: 'Sales Rep', module: 'CRM', action: 'Membuat Lead baru dari prospek', automation: '-', output: 'Lead tercatat dengan status tracking' },
                { stage: 'Operasional', role: 'Sales Rep', module: 'CRM', action: 'Qualifikasi Lead menjadi Opportunity', automation: 'Auto-create Customer record jika belum ada', output: 'Opportunity dengan forecast dan timeline' },
                { stage: 'Operasional', role: 'Sales Rep', module: 'Sales', action: 'Convert Opportunity ke Sales Order', automation: 'Auto-populate customer data dan pricing', output: 'Sales Order (Draft) siap untuk approval' },
                { stage: 'Operasional', role: 'Sales Manager', module: 'Sales', action: 'Approve Sales Order', automation: 'Validasi stok dan credit limit customer', output: 'Sales Order approved, siap untuk fulfillment' },
                { stage: 'Operasional', role: 'Warehouse Staff', module: 'Warehouse', action: 'Pick & Pack items untuk SO', automation: 'Reserve stok, generate picking list', output: 'Items ready for shipment' },
                { stage: 'Operasional', role: 'Warehouse Staff', module: 'Warehouse', action: 'Process Shipment/Delivery', automation: 'Update stok, create delivery note', output: 'SO status: Shipped, stok berkurang' },
                { stage: 'Operasional', role: 'Billing Staff', module: 'Sales', action: 'Generate Invoice dari SO', automation: 'Auto-journal: (D) Piutang, (K) Pendapatan; (D) HPP, (K) Persediaan', output: 'Invoice terbit, piutang dan revenue tercatat' },

                { stage: 'Operasional', role: 'Purchasing Staff', module: 'Purchasing', action: 'Membuat Purchase Order ke supplier', automation: 'Auto-generate dari reorder point atau MRP', output: 'PO approved, supplier notification sent' },
                { stage: 'Operasional', role: 'Warehouse Staff', module: 'Warehouse', action: 'Receive goods dari PO', automation: 'Update stok, auto-journal: (D) Inventory, (K) Accounts Payable', output: 'Stok bertambah, utang supplier tercatat' },
                { stage: 'Operasional', role: 'AP Staff', module: 'Purchasing', action: 'Process supplier payment', automation: 'Auto-journal: (D) Accounts Payable, (K) Cash/Bank', output: 'Utang supplier lunas' },

                { stage: 'Operasional', role: 'Production Planner', module: 'Manufacturing', action: 'Create Manufacturing Order (MO)', automation: 'Calculate material requirements dari BOM', output: 'Work order dengan material dan routing plan' },
                { stage: 'Operasional', role: 'Production Staff', module: 'Manufacturing', action: 'Start Production - consume materials', automation: 'Deduct raw materials dari stok berdasarkan BOM', output: 'MO status: In Progress, WIP tercatat' },
                { stage: 'Operasional', role: 'Production Staff', module: 'Manufacturing', action: 'Complete Production - finish goods', automation: 'Add finished goods ke stok, calculate production cost', output: 'MO status: Completed, finished goods tersedia' },

                { stage: 'Operasional', role: 'Cashier', module: 'PointOfSales', action: 'Process retail sales transaction', automation: 'Real-time stok check, auto-pricing, tax calculation', output: 'Receipt printed, sales recorded, stok updated' },
                { stage: 'Operasional', role: 'Cashier', module: 'PointOfSales', action: 'Handle returns dan refunds', automation: 'Reverse stok movement, refund journal entries', output: 'Return processed, stok dan accounting adjusted' },

                // === OPERASIONAL PERIODIK ===
                { stage: 'Operasional', role: 'HR Staff', module: 'HumanResource', action: 'Record daily attendance', automation: 'Auto-calculate dari biometric/check-in system', output: 'Attendance data untuk payroll calculation' },
                { stage: 'Operasional', role: 'HR Staff', module: 'HumanResource', action: 'Process leave requests', automation: 'Validate leave balance, approval workflow', output: 'Leave approved/rejected, balance updated' },
                { stage: 'Operasional', role: 'HR Staff', module: 'HumanResource', action: 'Conduct performance reviews', automation: 'Template-based evaluation, scoring calculation', output: 'Performance scores untuk appraisal dan development' },
                { stage: 'Operasional', role: 'HR Staff', module: 'HumanResource', action: 'Process monthly payroll', automation: 'Auto-calculate salary, overtime, deductions; Create journal: (D) Salary Expense, (K) Salary Payable & Tax Payable', output: 'Payslips generated, salary expense recorded' },

                { stage: 'Operasional', role: 'Project Manager', module: 'ProjectManagement', action: 'Create dan manage projects', automation: 'Template-based project setup, resource allocation', output: 'Project timeline, tasks, dan milestones defined' },
                { stage: 'Operasional', role: 'Team Member', module: 'ProjectManagement', action: 'Log time dan update task progress', automation: 'Time tracking, progress calculation, budget monitoring', output: 'Real-time project status dan cost tracking' },

                { stage: 'Operasional', role: 'Help Desk Staff', module: 'Helpdesk', action: 'Handle customer support tickets', automation: 'Auto-assignment, escalation rules, SLA tracking', output: 'Customer issues resolved, satisfaction tracked' },
                { stage: 'Operasional', role: 'Document Controller', module: 'DocumentManagement', action: 'Manage business documents', automation: 'Version control, approval workflow, retention policies', output: 'Documents organized, compliant, easily accessible' },

                // === MAINTENANCE & QUALITY ===
                { stage: 'Maintenance', role: 'Maintenance Staff', module: 'Maintenance', action: 'Schedule preventive maintenance', automation: 'Auto-schedule berdasarkan usage hours/cycles', output: 'Maintenance calendar, spare parts reserved' },
                { stage: 'Maintenance', role: 'Maintenance Staff', module: 'Maintenance', action: 'Execute maintenance work orders', automation: 'Record labor hours, parts consumed, costs', output: 'Equipment maintained, costs tracked, history logged' },
                { stage: 'Maintenance', role: 'QC Inspector', module: 'QualityControl', action: 'Perform quality inspections', automation: 'Template-based checklists, statistical sampling', output: 'Quality records, defect tracking, supplier ratings' },
                { stage: 'Maintenance', role: 'QC Manager', module: 'QualityControl', action: 'Analyze quality trends dan improvements', automation: 'Statistical analysis, trend charts, alerts', output: 'Quality improvement actions, supplier feedback' },

                // === INTERNAL PROCESSES ===
                { stage: 'Internal', role: 'Warehouse Staff', module: 'Warehouse', action: 'Perform stock transfers antar gudang', automation: 'Update multiple warehouse locations, tracking number', output: 'Stock relocated, multi-location inventory updated' },
                { stage: 'Internal', role: 'Warehouse Staff', module: 'Warehouse', action: 'Conduct cycle counting/stock opname', automation: 'Generate count sheets, variance analysis, adjustment journals', output: 'Accurate inventory, discrepancies resolved' },
                { stage: 'Internal', role: 'Finance Staff', module: 'Accounting', action: 'Process journal entries dan adjustments', automation: 'Template journals, recurring entries, approval workflow', output: 'Accurate accounting records, audit trail maintained' },
                { stage: 'Internal', role: 'Finance Staff', module: 'Accounting', action: 'Reconcile bank statements', automation: 'Auto-matching transactions, exception reporting', output: 'Bank balances reconciled, cash position accurate' },
                { stage: 'Internal', role: 'Asset Manager', module: 'Accounting', action: 'Manage fixed assets dan depreciation', automation: 'Monthly depreciation calculation, disposal tracking', output: 'Asset register updated, depreciation expense recorded' },

                // === REPORTING & ANALYSIS ===
                { stage: 'Reporting', role: 'Management', module: 'Reports', action: 'Access Executive Dashboard', automation: 'Real-time KPI calculation dari all modules', output: 'Executive insights: revenue, profitability, trends' },
                { stage: 'Reporting', role: 'Sales Manager', module: 'Reports', action: 'Analyze sales performance', automation: 'Sales analytics: by product, customer, region, rep', output: 'Sales insights untuk strategic decisions' },
                { stage: 'Reporting', role: 'CFO/Controller', module: 'Accounting', action: 'Generate financial statements', automation: 'Real-time P&L, Balance Sheet, Cash Flow dari all transactions', output: 'Accurate financial reports untuk stakeholders' },
                { stage: 'Reporting', role: 'Operations Manager', module: 'Reports', action: 'Monitor operational metrics', automation: 'Cross-module analytics: inventory turnover, production efficiency', output: 'Operational insights untuk process improvement' },
                { stage: 'Reporting', role: 'Finance Staff', module: 'Accounting', action: 'Prepare regulatory dan tax reports', automation: 'Tax calculation, compliance templates, submission formats', output: 'Compliance reports submitted on time' },

                // === PERIOD-END CLOSING ===
                { stage: 'Period Closing', role: 'System', module: 'Accounting', action: 'Auto-run monthly depreciation', automation: 'Calculate dan post depreciation untuk all fixed assets', output: 'Depreciation expense dan accumulated depreciation updated' },
                { stage: 'Period Closing', role: 'Accountant', module: 'Accounting', action: 'Month-end closing procedures', automation: 'Accrual calculations, prepaid amortization, closing checklists', output: 'Monthly books closed, variance analysis completed' },
                { stage: 'Period Closing', role: 'Accountant', module: 'Accounting', action: 'Year-end closing dan audit prep', automation: 'Closing entries, retained earnings transfer, audit trails', output: 'Year-end books closed, audit documentation ready' },
                { stage: 'Period Closing', role: 'Management', module: 'Reports', action: 'Strategic planning dan budgeting', automation: 'Historical analysis, forecasting models, variance reports', output: 'Budget plans, strategic initiatives untuk next period' },
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
                const stages = ['Setup Awal', 'Operasional', 'Maintenance', 'Internal', 'Reporting', 'Period Closing'];
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
                    const processCount = moduleData.items.length;
                    return `
                        <div class="module-card bg-white rounded-lg shadow-md p-4 cursor-pointer border-2 border-transparent transition-all duration-300 hover:border-blue-500"
                             data-module="${moduleName}"
                             data-roles='${JSON.stringify(Array.from(moduleData.roles))}'>
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-bold text-slate-800 text-sm">${moduleName}</h4>
                                <span class="inline-block px-2 py-1 bg-blue-100 text-blue-600 text-xs font-medium rounded">${processCount}</span>
                            </div>
                            <p class="text-xs text-slate-500 mb-2">${roles}</p>
                            <div class="text-xs text-slate-400">
                                <i class="fas fa-mouse-pointer"></i> Click untuk detail
                            </div>
                        </div>
                    `;
                }).join('');

                const totalProcesses = items.length;
                const uniqueRoles = [...new Set(items.map(item => item.role))].length;

                return `
                    <div class="stage-column flex-1 relative">
                        <div class="text-center mb-4">
                            <h3 class="text-xl font-bold mb-2">${stageName}</h3>
                            <div class="text-xs text-slate-500">
                                ${totalProcesses} proses • ${uniqueRoles} peran
                            </div>
                        </div>
                        <div class="space-y-3">
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
                const uniqueStages = [...new Set(items.map(item => item.stage))];
                const uniqueRoles = [...new Set(items.map(item => item.role))];

                modalTitle.textContent = `${moduleName} - ${items.length} Proses`;
                modalBody.innerHTML = `
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-semibold text-slate-600">Tahapan:</span>
                                <span class="text-blue-600">${uniqueStages.join(', ')}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-slate-600">Peran Terlibat:</span>
                                <span class="text-green-600">${uniqueRoles.join(', ')}</span>
                            </div>
                        </div>
                    </div>
                    ${items.map((item, index) => `
                        <div class="mb-6 pb-4 border-b last:border-b-0">
                            <div class="flex items-center justify-between mb-2">
                                <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded">${item.stage}</span>
                                <span class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded">${item.role}</span>
                            </div>
                            <h4 class="font-bold text-lg text-slate-800 mb-2">${index + 1}. ${item.action}</h4>

                            <div class="text-sm space-y-2">
                                <div class="p-3 bg-amber-50 rounded">
                                    <p class="font-semibold text-amber-800 mb-1">🤖 Otomatisasi Sistem:</p>
                                    <p class="text-amber-700">${item.automation}</p>
                                </div>
                                <div class="p-3 bg-green-50 rounded">
                                    <p class="font-semibold text-green-800 mb-1">✅ Hasil Akhir:</p>
                                    <p class="font-medium text-green-700">${item.output}</p>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                `;
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
            updateStatistics();

            function updateStatistics() {
                const totalProcesses = workflowData.length;
                const uniqueRoles = [...new Set(workflowData.map(item => item.role))].length;

                document.getElementById('total-processes').textContent = totalProcesses;
                document.getElementById('total-roles').textContent = uniqueRoles;
            }
        });
    </script>
@endpush
