@extends('layouts.app')

@section('content')
<div class="px-6 py-4" x-data="accountManager()">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 mb-1">Chart of Accounts</h1>
            <p class="text-sm text-gray-500">Kelola daftar akun dalam sistem akuntansi</p>
        </div>
        <div class="mt-3 sm:mt-0">
            <button @click="openCreateModal()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-500 focus:outline-none">
                <i class="fas fa-plus"></i> Tambah Akun
            </button>
        </div>
    </div>

    {{-- Main Card --}}
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="flex justify-between items-center px-4 py-3 border-b">
            <h2 class="text-base font-semibold text-gray-700">Daftar Akun</h2>
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
        </div>

        <div class="p-4">
            <div class="overflow-x-auto rounded-md border border-gray-200">
                <x-table.datatable id="coa-table" :columns="['Kode Akun', 'Nama Akun', 'Tipe', 'Sub Tipe', 'Parent', 'Sub Akun', 'Status', 'Aksi']" />
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <x-modal name="account-form-modal" max-width="lg">
        <div class="px-6 py-4">
            <h3 class="text-lg font-medium text-gray-900 mb-4" x-text="modalTitle">Modal Title</h3>

            <form @submit.prevent="submitForm()" id="account-form" :action="formAction" data-ajax-form>
                @csrf
                <input type="hidden" name="_method" :value="formMethod">

                <div class="space-y-4">
                    <!-- Kode Akun -->
                    <div>
                        <label for="account_code" class="block text-sm font-medium text-gray-700 mb-1">
                            Kode Akun <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="account_code"
                               id="formData"
                               x-model="formData.account_code"
                               :readonly="isViewMode"
                               :class="isViewMode ? 'bg-gray-100 cursor-not-allowed' : 'focus:ring-indigo-500 focus:border-indigo-500'"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm"
                               placeholder="Masukkan kode akun"
                               required>
                    </div>

                    <!-- Nama Akun -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Akun <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               id="name"
                               x-model="formData.name"
                               :readonly="isViewMode"
                               :class="isViewMode ? 'bg-gray-100 cursor-not-allowed' : 'focus:ring-indigo-500 focus:border-indigo-500'"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm"
                               placeholder="Masukkan nama akun"
                               required>
                    </div>

                    <!-- Tipe Akun -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                            Tipe Akun <span class="text-red-500">*</span>
                        </label>
                        <select name="type"
                                id="type"
                                x-model="formData.type"
                                :disabled="isViewMode"
                                :class="isViewMode ? 'bg-gray-100 cursor-not-allowed' : 'focus:ring-indigo-500 focus:border-indigo-500'"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm"
                                required>
                            <option value="">Pilih Tipe Akun</option>
                            <option value="asset">Asset</option>
                            <option value="liability">Liability</option>
                            <option value="equity">Equity</option>
                            <option value="revenue">Revenue</option>
                            <option value="expense">Expense</option>
                        </select>
                    </div>

                    <!-- Status Aktif -->
                    <div class="flex items-center">
                        <input type="checkbox"
                               name="is_active"
                               id="is_active"
                               x-model="formData.is_active"
                               :disabled="isViewMode"
                               value="1"
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Status Aktif
                        </label>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="mt-6 flex justify-end gap-3" x-show="!isViewMode">
                    <button @click.prevent="$dispatch('close-modal')"
                            type="button"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Batal
                    </button>
                    <button type="submit"
                            :disabled="isLoading"
                            :class="isLoading ? 'opacity-50 cursor-not-allowed' : 'hover:bg-indigo-500'"
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

                <!-- Close button for view mode -->
                <div class="mt-6 flex justify-end" x-show="isViewMode">
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

<script>
let coaTable;

function refreshTable() {
    coaTable.ajax.reload(null, false);
}

function exportData() {
    window.open('{{ route("accounting.coa.index") }}?export=excel', '_blank');
}

document.addEventListener('DOMContentLoaded', function() {
    coaTable = $('#coa-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: '{{ route("accounting.coa.index") }}',
            type: 'GET'
        },
        columns: [
            {
                data: 'account_code',
                name: 'account_code',
                className: 'font-mono font-semibold'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'type',
                name: 'type',
                className: 'text-center',
                render: function (data) {
                    const colors = {
                        asset: 'bg-blue-500',
                        liability: 'bg-yellow-300 text-black',
                        equity: 'bg-cyan-500',
                        revenue: 'bg-green-500',
                        expense: 'bg-red-500'
                    };
                    const color = colors[data] || 'bg-gray-300';
                    return `<span class="text-white text-xs font-semibold px-2 py-0.5 rounded ${color}">
                                ${data?.charAt(0).toUpperCase() + data?.slice(1)}
                            </span>`;
                }
            },
            {
                data: 'sub_type',
                name: 'sub_type',
                className: 'text-center',
                render: function (data) {
                    return data
                        ? `<span class="text-gray-800 text-xs bg-gray-100 px-2 py-0.5 rounded">${data}</span>`
                        : '-';
                }
            },
            {
                data: 'parent_name',
                name: 'parent_name',
                defaultContent: '-'
            },
            {
                data: 'sub_accounts_count',
                name: 'sub_accounts_count',
                className: 'text-center',
                orderable: false,
                render: function (data) {
                    return `<span class="text-xs bg-gray-200 px-2 py-0.5 rounded">${data}</span>`;
                }
            },
            {
                data: 'status',
                name: 'status',
                className: 'text-center',
                orderable: false,
                render: function (data) {
                    const active = data === 'aktif';
                    return `<span class="text-xs font-medium px-2 py-0.5 rounded ${active ? 'bg-green-500 text-white' : 'bg-gray-400 text-white'}">
                                ${active ? 'Aktif' : 'Tidak Aktif'}
                            </span>`;
                }
            },
            {
                data: 'action',
                name: 'action',
                className: 'text-center',
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    const editButton = `<button type="button" onclick="accountManagerInstance.openEditModal(${JSON.stringify(row).replace(/"/g, '&quot;')})" class="text-indigo-500 hover:text-indigo-700 mr-2" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>`;

                    const viewButton = `<button type="button" onclick="accountManagerInstance.openViewModal(${JSON.stringify(row).replace(/"/g, '&quot;')})" class="text-blue-500 hover:text-blue-700 mr-2" title="Lihat">
                        <i class="fas fa-eye"></i>
                    </button>`;

                    let deleteButton = '';
                    if (row.sub_accounts_count == 0) {
                        deleteButton = `<x-delete-button action="/accounting/coa/${row.id}" class="text-red-500 hover:text-red-700" />`;
                    } else {
                        deleteButton = `<button class="text-gray-400 cursor-not-allowed" title="Tidak dapat dihapus (memiliki sub-akun)">
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
            text: "Yakin ingin menghapus akun ini? Tindakan tidak dapat dibatalkan!",
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
                            text: data.message || 'Akun berhasil dihapus.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false,
                            customClass: {
                                container: 'font-sans',
                                popup: 'rounded-lg'
                            }
                        });
                        coaTable.ajax.reload();
                    } else {
                        throw new Error(data.error || 'Gagal menghapus akun');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Gagal!',
                        text: error.message || 'Terjadi kesalahan saat menghapus akun.',
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
let accountManagerInstance;

function accountManager() {
  const instance = {
    isLoading: false,
    modalTitle: '',
    formAction: '',
    formMethod: 'POST',
    isViewMode: false,
    formData: {
      id: '',
      account_code: '',
      name: '',
      type: '',
      sub_type: '',
      parent_id: '',
      is_active: true,
    },

    openCreateModal() {
      this.isViewMode = false;
      this.modalTitle = 'Tambah Akun Baru';
      this.formAction = '{{ route("accounting.coa.store") }}';
      this.formMethod = 'POST';
      this.formData = {
        id: '',
        account_code: '',
        name: '',
        type: '',
        sub_type: '',
        parent_id: '',
        is_active: true
      };
      this.$dispatch('open-modal', { name: 'account-form-modal' });
    },

    openViewModal(rowData) {
      this.isViewMode = true;
      this.modalTitle = `Detail Akun: ${rowData.name}`;
      this.formData = {
        id: rowData.id,
        account_code: rowData.account_code,
        name: rowData.name,
        type: rowData.type,
        sub_type: rowData.sub_type || '',
        parent_id: rowData.parent_id || '',
        is_active: rowData.is_active
      };
      this.$dispatch('open-modal', { name: 'account-form-modal', 'viewMode': true, 'formData': this.formData });
    },

    openEditModal(rowData) {
      this.isViewMode = false;
      this.modalTitle = `Edit Akun: ${rowData.name}`;
      this.formAction = `/accounting/coa/${rowData.id}`;
      this.formMethod = 'PUT';
      this.formData = {
        id: rowData.id,
        account_code: rowData.account_code,
        name: rowData.name,
        type: rowData.type,
        sub_type: rowData.sub_type || '',
        parent_id: rowData.parent_id || '',
        is_active: rowData.is_active
      };
      this.$dispatch('open-modal', { name: 'account-form-modal' });
    },

    async submitForm() {
      if (this.isViewMode) {
        this.$dispatch('close-modal');
        return;
      }

      this.isLoading = true;

      try {
        const formData = new FormData(document.getElementById('account-form'));

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
        if (typeof coaTable !== 'undefined') {
          coaTable.ajax.reload();
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
  accountManagerInstance = instance;
  return instance;
}
</script>
@endpush
