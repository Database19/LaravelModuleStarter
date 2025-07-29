@extends('layouts.app')

@section('content')
<div class="px-6 py-4" x-data="journalManager()">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 mb-1">Jurnal Umum</h1>
            <p class="text-sm text-gray-500">Daftar semua entri jurnal yang tercatat dalam sistem</p>
        </div>
        <div class="mt-3 sm:mt-0">
            <button @click="openCreateModal()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-500 focus:outline-none">
                <i class="fas fa-plus"></i> Buat Jurnal Baru
            </button>
        </div>
    </div>

    {{-- Main Card --}}
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        {{-- <div class="flex justify-between items-center px-4 py-3 border-b">
            <h2 class="text-base font-semibold text-gray-700">Daftar Jurnal</h2>
            <div class="flex items-center space-x-2">
                <button onclick="refreshTable()"
                    class="inline-flex items-center px-3 py-1.5 text-sm text-gray-700 border border-gray-300 rounded-md hover:bg-gray-100">
                    <i class="fas fa-sync-alt mr-1"></i> Refresh
                </button>
                <button onclick="exportData()"
                    class="inline-flex items-center px-3 py-1.5 text-sm text-gray-700 border border-gray-300 rounded-md hover:bg-gray-100">
                    <i class="fas fa-download mr-1"></i> Export
                </button>
            </div>
        </div> --}}

        <div class="p-4">
            <div class="overflow-x-auto rounded-md border border-gray-200">
                <x-table.datatable id="journals-table" :columns="['Tanggal', 'Deskripsi', 'Total Debit', 'Total Kredit', 'Status', 'Aksi']" />
            </div>
        </div>
    </div>

    {{-- Modal untuk Create/Edit/View --}}
    <x-modal name="journal-form-modal" title="Form Jurnal" max-width="2xl" @close="cleanupTomSelects()">
        <div class="px-2">

            <form @submit.prevent="submitForm()" id="journal-form" :action="formAction">
                @csrf
                <input type="hidden" name="_method" :value="formMethod">

                {{-- Basic Info --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Tanggal -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               name="date"
                               id="date"
                               x-model="formData.date"
                               :readonly="isViewMode"
                               :class="isViewMode ? 'bg-gray-100 cursor-not-allowed' : 'focus:ring-indigo-500 focus:border-indigo-500'"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm"
                               required>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                            Deskripsi <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="description"
                               id="description"
                               x-model="formData.description"
                               :readonly="isViewMode"
                               :class="isViewMode ? 'bg-gray-100 cursor-not-allowed' : 'focus:ring-indigo-500 focus:border-indigo-500'"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm"
                               placeholder="Masukkan deskripsi jurnal"
                               required>
                    </div>
                </div>

                {{-- Journal Entries --}}
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-md font-medium text-gray-900">Entri Jurnal</h4>
                        <button type="button" @click="addEntry()" x-show="!isViewMode"
                                class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-plus mr-1"></i>
                            Tambah Entri
                        </button>
                    </div>

                    <div class="overflow-x-auto bg-white border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akun</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Debit</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kredit</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" x-show="!isViewMode">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <template x-for="(entry, index) in formData.entries" :key="index">
                                    <tr>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <select :name="`entries[${index}][account_id]`"
                                                    x-model="entry.account_id"
                                                    :disabled="isViewMode"
                                                    :class="isViewMode ? 'bg-gray-100 cursor-not-allowed' : 'focus:ring-indigo-500 focus:border-indigo-500'"
                                                    class="coa-select block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm"
                                                    :data-entry-index="index"
                                                    @change="onAccountChange(index, $event.target.value)"
                                                    required>
                                                <option value="">Pilih Akun</option>
                                            </select>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <input type="text"
                                                   :name="`entries[${index}][description]`"
                                                   x-model="entry.description"
                                                   :readonly="isViewMode"
                                                   :class="isViewMode ? 'bg-gray-100 cursor-not-allowed' : 'focus:ring-indigo-500 focus:border-indigo-500'"
                                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm"
                                                   placeholder="Deskripsi entri">
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <input type="number"
                                                   :name="`entries[${index}][debit]`"
                                                   x-model="entry.debit"
                                                   :readonly="isViewMode"
                                                   :class="isViewMode ? 'bg-gray-100 cursor-not-allowed' : 'focus:ring-indigo-500 focus:border-indigo-500'"
                                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm"
                                                   placeholder="0.00"
                                                   step="0.01"
                                                   min="0"
                                                   @input="updateTotals()">
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <input type="number"
                                                   :name="`entries[${index}][credit]`"
                                                   x-model="entry.credit"
                                                   :readonly="isViewMode"
                                                   :class="isViewMode ? 'bg-gray-100 cursor-not-allowed' : 'focus:ring-indigo-500 focus:border-indigo-500'"
                                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm"
                                                   placeholder="0.00"
                                                   step="0.01"
                                                   min="0"
                                                   @input="updateTotals()">
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap" x-show="!isViewMode">
                                            <button type="button" @click="removeEntry(index)"
                                                    class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="2" class="px-4 py-3 text-sm font-medium text-gray-900">Total:</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900" x-text="formatCurrency(totalDebit)"></td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900" x-text="formatCurrency(totalCredit)"></td>
                                    <td x-show="!isViewMode"></td>
                                </tr>
                                <tr x-show="totalDebit !== totalCredit">
                                    <td colspan="5" class="px-4 py-2 text-sm text-red-600 text-center">
                                        ⚠️ Total Debit dan Kredit harus seimbang!
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex justify-end gap-3" x-show="!isViewMode">
                    <button @click.prevent="$dispatch('close-modal')"
                            type="button"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Batal
                    </button>
                    <button type="submit"
                            :disabled="isLoading || totalDebit !== totalCredit"
                            :class="(isLoading || totalDebit !== totalCredit) ? 'opacity-50 cursor-not-allowed' : 'hover:bg-indigo-500'"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <span x-show="!isLoading">Simpan</span>
                        <span x-show="isLoading" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>

                {{-- Close button for view mode --}}
                <div class="flex justify-end" x-show="isViewMode">
                    <button @click.prevent="$dispatch('close-modal')"
                            type="button"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Tutup
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</div>
@endsection

@push('scripts')
{{-- SweetAlert2 for confirmations --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}

<script>
let journalsTable;
let journalManagerInstance;

function refreshTable() {
    if (journalsTable) {
        journalsTable.ajax.reload(null, false);
    }
}

function exportData() {
    window.open('{{ route("accounting.journals.index") }}?export=excel', '_blank');
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    journalsTable = $('#journals-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: '{{ route("accounting.journals.index") }}',
            type: 'GET'
        },
        columns: [
            {
                data: 'date',
                name: 'date',
                render: function(data) {
                    return new Date(data).toLocaleDateString('id-ID');
                }
            },
            {
                data: 'description',
                name: 'description'
            },
            {
                data: 'total_debit',
                name: 'total_debit',
                className: 'text-right font-mono',
                render: function(data) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(data || 0);
                }
            },
            {
                data: 'total_credit',
                name: 'total_credit',
                className: 'text-right font-mono',
                render: function(data) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(data || 0);
                }
            },
            {
                data: 'status',
                name: 'status',
                className: 'text-center',
                render: function(data) {
                    const statusClass = data === 'posted' ? 'bg-green-500' : 'bg-yellow-500';
                    const statusText = data === 'posted' ? 'Posted' : 'Draft';
                    return `<span class="text-xs font-medium px-2 py-0.5 rounded text-white ${statusClass}">${statusText}</span>`;
                }
            },
            {
                data: 'action',
                name: 'action',
                className: 'text-center',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    const viewButton = `<button type="button" onclick="journalManagerInstance.openViewModal(${JSON.stringify(row).replace(/"/g, '&quot;')})" class="text-blue-500 hover:text-blue-700 mr-2" title="Lihat">
                        <i class="fas fa-eye"></i>
                    </button>`;

                    const editButton = `<button type="button" onclick="journalManagerInstance.openEditModal(${JSON.stringify(row).replace(/"/g, '&quot;')})" class="text-indigo-500 hover:text-indigo-700 mr-2" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>`;

                    let deleteButton = '';
                    if (row.status !== 'posted') {
                        deleteButton = `<form method="POST" action="/accounting/journals/${row.id}" class="delete-form inline">
                            <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="text-red-500 hover:text-red-700" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>`;
                    } else {
                        deleteButton = `<button class="text-gray-400 cursor-not-allowed" title="Tidak dapat dihapus (sudah di-post)">
                            <i class="fas fa-trash"></i>
                        </button>`;
                    }

                    return `<div class="flex items-center justify-center gap-2">
                        ${viewButton}
                        ${editButton}
                        ${deleteButton}
                    </div>`;
                }
            }
        ],
    });

    // Handle delete confirmation
    $(document).on('submit', '.delete-form', function(e) {
        e.preventDefault();
        const form = this;

        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: "Yakin ingin menghapus jurnal ini? Tindakan tidak dapat dibatalkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                container: 'font-sans',
                popup: 'rounded-lg',
                confirmButton: 'rounded-md',
                cancelButton: 'rounded-md'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: new FormData(form)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success || data.message) {
                        Swal.fire({
                            title: 'Terhapus!',
                            text: data.message || 'Jurnal berhasil dihapus.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false,
                            customClass: {
                                container: 'font-sans',
                                popup: 'rounded-lg'
                            }
                        });
                        journalsTable.ajax.reload();
                    } else {
                        throw new Error(data.error || 'Gagal menghapus jurnal');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Gagal!',
                        text: error.message || 'Terjadi kesalahan saat menghapus jurnal.',
                        icon: 'error',
                        customClass: {
                            container: 'font-sans',
                            popup: 'rounded-lg',
                            confirmButton: 'rounded-md'
                        }
                    });
                });
            }
        });
    });
});

// Alpine.js component for modal and form
function journalManager() {
    const instance = {
        isLoading: false,
        modalTitle: '',
        formAction: '',
        formMethod: 'POST',
        isViewMode: false,
        accounts: [],
        totalDebit: 0,
        totalCredit: 0,
        formData: {
            id: '',
            date: '',
            description: '',
            entries: []
        },

        async init() {
            // Load accounts for dropdown
            await this.loadAccounts();
            console.log('Accounts loaded:', this.accounts.length);
        },

        async loadAccounts() {
            try {
                const response = await fetch('{{ route("accounting.api.accounts") }}');
                this.accounts = await response.json();
            } catch (error) {
                console.error('Failed to load accounts:', error);
                this.accounts = [];
            }
        },

        initializeTomSelects() {
            if (typeof TomSelect === 'undefined') {
                console.warn('TomSelect library not loaded');
                return;
            }

            if (!this.accounts || this.accounts.length === 0) {
                console.warn('Accounts not loaded yet, skipping TomSelect initialization');
                return;
            }

            console.log('Initializing TomSelect for', document.querySelectorAll('.coa-select').length, 'selects');

            // Destroy existing instances first
            document.querySelectorAll('.coa-select').forEach(selectElement => {
                if (selectElement.tomSelect) {
                    selectElement.tomSelect.destroy();
                }
            });

            // Initialize TomSelect for all CoA select elements
            document.querySelectorAll('.coa-select').forEach((selectElement, index) => {
                if (!this.isViewMode && !selectElement.disabled) {
                    try {
                        // Clear existing options except the first one (placeholder)
                        while (selectElement.options.length > 1) {
                            selectElement.removeChild(selectElement.options[1]);
                        }

                        // Add account options manually
                        this.accounts.forEach(account => {
                            const option = document.createElement('option');
                            option.value = account.id;
                            option.textContent = `${account.account_code} - ${account.name}`;
                            selectElement.appendChild(option);
                        });

                        // Set current value if exists
                        const entryIndex = parseInt(selectElement.getAttribute('data-entry-index')) || index;
                        if (this.formData.entries[entryIndex] && this.formData.entries[entryIndex].account_id) {
                            selectElement.value = this.formData.entries[entryIndex].account_id;
                        }

                        const tomSelect = new TomSelect(selectElement, {
                            placeholder: 'Ketik untuk mencari akun...',
                            allowEmptyOption: true,
                            create: false,
                            searchField: ['text'],
                            maxOptions: 200,
                            dropdownParent: 'body',
                            render: {
                                no_results: function(data, escape) {
                                    return '<div class="text-gray-500 p-2">Tidak ada akun ditemukan</div>';
                                }
                            },
                            onChange: (value) => {
                                // Update Alpine.js model
                                const entryIndex = parseInt(selectElement.getAttribute('data-entry-index')) || index;
                                if (this.formData.entries[entryIndex]) {
                                    this.formData.entries[entryIndex].account_id = value;
                                    // Trigger Alpine reactivity
                                    this.$nextTick(() => {
                                        this.updateTotals();
                                    });
                                }
                            }
                        });

                        // Store reference for cleanup
                        selectElement.tomSelect = tomSelect;
                        console.log('TomSelect initialized for element', index);
                    } catch (error) {
                        console.error('Error initializing TomSelect:', error);
                    }
                }
            });
        },

        onAccountChange(index, value) {
            if (this.formData.entries[index]) {
                this.formData.entries[index].account_id = value;
            }
        },

        async openCreateModal() {
            this.isViewMode = false;
            this.modalTitle = 'Buat Jurnal Baru';
            this.formAction = '{{ route("accounting.journals.store") }}';
            this.formMethod = 'POST';
            this.formData = {
                id: '',
                date: new Date().toISOString().split('T')[0],
                description: '',
                entries: [
                    { account_id: '', description: '', debit: 0, credit: 0 },
                    { account_id: '', description: '', debit: 0, credit: 0 }
                ]
            };
            this.updateTotals();
            this.$dispatch('open-modal', { name: 'journal-form-modal' });

            // Ensure accounts are loaded before initializing TomSelect
            if (!this.accounts || this.accounts.length === 0) {
                await this.loadAccounts();
            }

            // Initialize TomSelect after modal opens
            this.$nextTick(() => {
                setTimeout(() => {
                    this.updateEntryIndexes();
                    this.initializeTomSelects();
                }, 200);
            });
        },

        openViewModal(rowData) {
            this.isViewMode = true;
            this.modalTitle = `Detail Jurnal: ${rowData.description}`;
            this.formData = {
                id: rowData.id,
                date: rowData.date,
                description: rowData.description,
                entries: rowData.entries || []
            };
            this.updateTotals();
            this.$dispatch('open-modal', { name: 'journal-form-modal' });
        },

        async openEditModal(rowData) {
            this.isViewMode = false;
            this.modalTitle = `Edit Jurnal: ${rowData.description}`;
            this.formAction = `/accounting/journals/${rowData.id}`;
            this.formMethod = 'PUT';
            this.formData = {
                id: rowData.id,
                date: rowData.date,
                description: rowData.description,
                entries: rowData.entries || []
            };
            this.updateTotals();
            this.$dispatch('open-modal', { name: 'journal-form-modal' });

            // Ensure accounts are loaded before initializing TomSelect
            if (!this.accounts || this.accounts.length === 0) {
                await this.loadAccounts();
            }

            // Initialize TomSelect after modal opens
            this.$nextTick(() => {
                setTimeout(() => {
                    this.updateEntryIndexes();
                    this.initializeTomSelects();
                }, 200);
            });
        },

        updateEntryIndexes() {
            // Update data-entry-index for all select elements
            this.$nextTick(() => {
                document.querySelectorAll('.coa-select').forEach((selectElement, index) => {
                    selectElement.setAttribute('data-entry-index', index);
                });
            });
        },

        addEntry() {
            this.formData.entries.push({
                account_id: '',
                description: '',
                debit: 0,
                credit: 0
            });

            // Update indexes and initialize TomSelect for new entry
            this.$nextTick(() => {
                this.updateEntryIndexes();
                setTimeout(() => {
                    this.initializeTomSelects();
                }, 50);
            });
        },

        removeEntry(index) {
            // Destroy TomSelect instance before removing entry
            const entryRow = document.querySelectorAll('.coa-select')[index];
            if (entryRow && entryRow.tomSelect) {
                entryRow.tomSelect.destroy();
            }

            this.formData.entries.splice(index, 1);
            this.updateTotals();

            // Update indexes and re-initialize remaining TomSelects
            this.$nextTick(() => {
                this.updateEntryIndexes();
                setTimeout(() => {
                    this.initializeTomSelects();
                }, 50);
            });
        },

        updateTotals() {
            this.totalDebit = this.formData.entries.reduce((sum, entry) => sum + parseFloat(entry.debit || 0), 0);
            this.totalCredit = this.formData.entries.reduce((sum, entry) => sum + parseFloat(entry.credit || 0), 0);
        },

        formatCurrency(amount) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount || 0);
        },

        cleanupTomSelects() {
            // Clean up TomSelect instances when modal closes
            document.querySelectorAll('.coa-select').forEach(selectElement => {
                if (selectElement.tomSelect) {
                    selectElement.tomSelect.destroy();
                    selectElement.tomSelect = null;
                }
            });
        },

        async submitForm() {
            if (this.isViewMode) {
                this.$dispatch('close-modal');
                return;
            }

            this.isLoading = true;

            try {
                const formData = new FormData(document.getElementById('journal-form'));

                // Add method for PUT/PATCH requests
                if (this.formMethod !== 'POST') {
                    formData.append('_method', this.formMethod);
                }

                const response = await fetch(this.formAction, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const result = await response.json();

                if (!response.ok) {
                    const errors = result.errors
                        ? Object.values(result.errors).flat().map(e => `• ${e}`).join('<br>')
                        : 'Terjadi kesalahan validasi.';

                    Swal.fire({
                        title: 'Gagal!',
                        html: `<div class="text-left">${errors}</div>`,
                        icon: 'error',
                        customClass: {
                            container: 'font-sans',
                            popup: 'rounded-lg',
                            confirmButton: 'rounded-md'
                        }
                    });
                    return;
                }

                this.$dispatch('close-modal');

                await Swal.fire({
                    title: 'Berhasil!',
                    text: result.message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false,
                    customClass: {
                        container: 'font-sans',
                        popup: 'rounded-lg'
                    }
                });

                // Reload DataTable
                if (typeof journalsTable !== 'undefined') {
                    journalsTable.ajax.reload();
                } else {
                    window.location.reload();
                }

            } catch (error) {
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat menyimpan data.',
                    icon: 'error',
                    customClass: {
                        container: 'font-sans',
                        popup: 'rounded-lg',
                        confirmButton: 'rounded-md'
                    }
                });
            } finally {
                this.isLoading = false;
            }
        },
    };

    // Set global reference
    journalManagerInstance = instance;
    return instance;
}
</script>
@endpush

@push('styles')
<style>
    /* TomSelect customization untuk CoA dropdown */
    .ts-control {
        min-height: 42px !important;
        border: 1px solid #d1d5db !important;
        border-radius: 0.375rem !important;
        padding: 0.5rem 0.75rem !important;
        font-size: 0.875rem !important;
        line-height: 1.25rem !important;
    }

    .ts-control.focus {
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 1px #6366f1 !important;
    }

    .ts-dropdown {
        border: 1px solid #d1d5db !important;
        border-radius: 0.375rem !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
        z-index: 9999 !important;
    }

    .ts-dropdown .option {
        padding: 0.5rem 0.75rem !important;
        font-size: 0.875rem !important;
    }

    .ts-dropdown .option.active {
        background-color: #6366f1 !important;
        color: white !important;
    }

    .ts-dropdown .option:hover {
        background-color: #f3f4f6 !important;
    }

    .ts-control.disabled {
        background-color: #f9fafb !important;
        cursor: not-allowed !important;
        opacity: 0.7 !important;
    }
</style>
@endpush
