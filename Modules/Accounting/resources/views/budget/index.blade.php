@extends('layouts.app')

@section('content')
<div class="px-4 py-6" x-data="budgetManager()" @submit-budget-form="handleFormSubmit($event.detail)">

    <!-- Header Page -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-primary flex items-center gap-2">
                <i class="fas fa-money-bill-wave"></i> Budget Management
            </h1>
            <p class="text-gray-500 mt-1">Kelola dan pantau anggaran perusahaan Anda secara efisien dan akurat.</p>
        </div>
        <button @click="openCreateModal()"
                class="inline-flex items-center px-4 py-2 bg-primary text-white rounded shadow hover:bg-primary-dark transition">
            <i class="fas fa-plus mr-2"></i> Tambah Budget
        </button>
    </div>

    <!-- Data Card -->
    <div class="bg-white rounded shadow mb-6">
        {{-- <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-primary to-blue-500 rounded-t">
            <h6 class="text-white font-semibold flex items-center gap-2">
                <i class="fas fa-table"></i> Daftar Budget
            </h6>
            <div class="relative">
                <button type="button" class="text-white focus:outline-none" id="dropdownMenuLink"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <ul class="hidden absolute right-0 mt-2 w-40 bg-white rounded shadow z-10 dropdown-menu"
                    aria-labelledby="dropdownMenuLink">
                    <li>
                        <h6 class="px-4 py-2 text-xs text-gray-400 font-semibold">Export Options</h6>
                    </li>
                    <li>
                        <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                           href="#"><i class="fas fa-file-pdf mr-2 text-red-500"></i>Export PDF</a>
                    </li>
                    <li>
                        <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                           href="#"><i class="fas fa-file-excel mr-2 text-green-600"></i>Export Excel</a>
                    </li>
                </ul>
            </div>
        </div> --}}
        <div class="p-6 bg-gray-50 rounded-b">
            <div class="overflow-x-auto">
                <table class="table table-bordered table-hover align-middle custom-datatable" id="budgetsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama Budget</th>
                            <th>Periode</th>
                            <th class="text-end">Budget Amount</th>
                            <th class="text-end">Used Amount</th>
                            <th class="text-end">Remaining</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Budget Modal -->
    <x-modal name="budget-modal" max-width="2xl">
        <div x-ref="budgetFormContainer">
            @include('accounting::budget._form')
        </div>
    </x-modal>

</div>
@endsection

@push('scripts')
<script>
// Budget Manager - Main controller for budget operations
function budgetManager() {
    return {
        modalTitle: 'Tambah Budget Baru',
        budgetTable: null,
        currentBudget: null,
        isEditMode: false,

        init() {
            // Expose to window for DataTable callbacks
            window.budgetManagerInstance = this;

            // Initialize DataTable after DOM is ready
            setTimeout(() => {
                this.initializeBudgetTable();
            }, 100);
        },

        // Initialize DataTable
        initializeBudgetTable() {
            if (typeof $ === 'undefined' || typeof $.fn.DataTable === 'undefined') {
                setTimeout(() => {
                    this.initializeBudgetTable();
                }, 500);
                return;
            }

            this.budgetTable = $('#budgetsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('accounting.budget.index') }}",
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'period_display', name: 'period_display', orderable: false, searchable: false },
                    { data: 'amount_formatted', name: 'amount', className: 'text-right' },
                    { data: 'used_formatted', name: 'used_amount', className: 'text-right' },
                    { data: 'remaining_formatted', name: 'remaining', className: 'text-right' },
                    { data: 'status_badge', name: 'status', orderable: false, searchable: false, className: 'text-center' },
                    {
                        data: null,
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: (data, type, row) => {
                            return `
                                <div class="flex justify-center space-x-2">
                                    <button onclick="window.budgetManagerInstance.openEditModal(${JSON.stringify(row).replace(/"/g, '&quot;')})"
                                            class="inline-flex items-center px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 transition">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </button>
                                    <button onclick="window.budgetManagerInstance.deleteBudget(${row.id}, '${row.name}')"
                                            class="inline-flex items-center px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600 transition">
                                        <i class="fas fa-trash mr-1"></i> Hapus
                                    </button>
                                </div>
                            `;
                        }
                    }
                ],
                // order: [[0, 'asc']],
                // pageLength: 25,
                // responsive: true,
                // dom: 'Bfrtip',
                // buttons: [
                //     {
                //         extend: 'excel',
                //         text: '<i class="fas fa-file-excel"></i> Excel',
                //         className: 'btn btn-sm btn-success'
                //     },
                //     {
                //         extend: 'pdf',
                //         text: '<i class="fas fa-file-pdf"></i> PDF',
                //         className: 'btn btn-sm btn-danger'
                //     },
                //     {
                //         extend: 'print',
                //         text: '<i class="fas fa-print"></i> Print',
                //         className: 'btn btn-sm btn-info'
                //     }
                // ],
                // language: {
                //     processing: '<div class="flex justify-center"><div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary"></div></div>',
                //     lengthMenu: "Tampilkan _MENU_ data",
                //     zeroRecords: "Tidak ada data ditemukan",
                //     info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                //     infoEmpty: "Tidak ada data",
                //     infoFiltered: "(disaring dari _MAX_ total data)",
                //     search: "Cari:",
                //     paginate: {
                //         first: "Awal",
                //         last: "Akhir",
                //         next: "Berikutnya",
                //         previous: "Sebelumnya"
                //     }
                // },
                initComplete: () => {
                    console.log('Budget DataTable initialized successfully');
                }
            });
        },

        // Handle form submission dari child component
        async handleFormSubmit(eventData) {
            const formData = eventData.formData;
            const isEditMode = eventData.isEditMode;

            if (!this.validateForm(formData)) {
                return;
            }

            try {
                // Set loading state pada form
                if (this.$refs.budgetFormContainer && this.$refs.budgetFormContainer.__x) {
                    this.$refs.budgetFormContainer.__x.$data.isSubmitting = true;
                }

                const url = isEditMode
                    ? `{{ route('accounting.budget.index') }}/${this.currentBudget.id}`
                    : '{{ route('accounting.budget.store') }}';

                const method = isEditMode ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (response.ok) {
                    // Success
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: isEditMode ? 'Budget berhasil diupdate!' : 'Budget berhasil dibuat!',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Close modal and refresh table
                    this.$dispatch('close-modal');
                    this.budgetTable.ajax.reload();
                } else {
                    // Handle validation errors
                    if (result.errors) {
                        let errorMessage = 'Terjadi kesalahan validasi:\n';
                        Object.keys(result.errors).forEach(key => {
                            errorMessage += `• ${result.errors[key][0]}\n`;
                        });

                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            text: errorMessage,
                            confirmButtonText: 'OK'
                        });
                    } else {
                        throw new Error(result.message || 'Terjadi kesalahan');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat menyimpan data: ' + error.message,
                    confirmButtonText: 'OK'
                });
            } finally {
                // Reset loading state
                if (this.$refs.budgetFormContainer && this.$refs.budgetFormContainer.__x) {
                    this.$refs.budgetFormContainer.__x.$data.isSubmitting = false;
                }
            }
        },

        // Open create modal
        openCreateModal() {
            this.isEditMode = false;
            this.currentBudget = null;
            this.modalTitle = 'Tambah Budget Baru';

            // Reset form in _form.blade.php
            setTimeout(() => {
                if (this.$refs.budgetFormContainer && this.$refs.budgetFormContainer.__x) {
                    this.$refs.budgetFormContainer.__x.$data.initForm(null, false);
                }
            }, 50);

            this.$dispatch('open-modal', { name: 'budget-modal' });
        },

        // Open edit modal
        openEditModal(budgetData) {
            this.isEditMode = true;
            this.currentBudget = budgetData;
            this.modalTitle = 'Edit Budget: ' + budgetData.name;

            // Populate form with existing data
            setTimeout(() => {
                if (this.$refs.budgetFormContainer && this.$refs.budgetFormContainer.__x) {
                    this.$refs.budgetFormContainer.__x.$data.initForm(budgetData, true);
                }
            }, 50);

            this.$dispatch('open-modal', { name: 'budget-modal' });
        },

        // Form validation
        validateForm(formData) {
            if (!formData.name.trim()) {
                Swal.fire('Error', 'Nama budget harus diisi!', 'error');
                return false;
            }
            if (!formData.category) {
                Swal.fire('Error', 'Kategori budget harus dipilih!', 'error');
                return false;
            }
            if (!formData.total_amount || formData.total_amount <= 0) {
                Swal.fire('Error', 'Jumlah budget harus lebih dari 0!', 'error');
                return false;
            }
            if (!formData.period) {
                Swal.fire('Error', 'Periode harus diisi!', 'error');
                return false;
            }
            if (!formData.start_date || !formData.end_date) {
                Swal.fire('Error', 'Tanggal mulai dan selesai harus diisi!', 'error');
                return false;
            }
            if (new Date(formData.start_date) >= new Date(formData.end_date)) {
                Swal.fire('Error', 'Tanggal selesai harus lebih besar dari tanggal mulai!', 'error');
                return false;
            }
            return true;
        },

        // Delete budget
        async deleteBudget(budgetId, budgetName) {
            const result = await Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus budget "${budgetName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`{{ route('accounting.budget.index') }}/${budgetId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });

                    if (response.ok) {
                        Swal.fire('Terhapus!', 'Budget berhasil dihapus.', 'success');
                        this.budgetTable.ajax.reload();
                    } else {
                        throw new Error('Gagal menghapus budget');
                    }
                } catch (error) {
                    Swal.fire('Error!', 'Terjadi kesalahan saat menghapus budget.', 'error');
                }
            }
        }
    }
}

// Initialize dropdown toggle when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Dropdown toggle (Tailwind style)
    document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const menu = btn.parentElement.querySelector('.dropdown-menu');
            menu.classList.toggle('hidden');
        });
    });
});
</script>
@endpush
