@props([
    'id',
    'columns' => [],
    'ajaxUrl' => '',
    'columnsConfig' => [],
    'exportUrl' => '',
    'exportButtonText' => 'Export',
    'searchable' => true,
    'sortable' => true,
    'showExport' => false,
    'showRefresh' => true,
    'showAdd' => true,
    'addUrl' => '',
    'addButtonText' => 'Add New',
    'tableClass' => 'min-w-full divide-y divide-gray-200',
    'modalTitle' => 'Data Details',
    'showModal' => true
])

<div x-data="tableManager()" class="w-full">
    @if($showExport || $showRefresh || $showAdd)
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-2">
            @if($showAdd && $addUrl)
            <button type="button" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white text-sm font-medium rounded-lg hover:from-emerald-700 hover:to-emerald-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200 transform hover:scale-105 shadow-md" @click="openAddModal()">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                {{ $addButtonText }}
            </button>
            @endif
        </div>

        <div class="flex items-center space-x-2">
            @if($showRefresh)
            <button type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 shadow-sm" onclick="refreshTable('{{ $id }}')">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Refresh
            </button>
            @endif

            @if($showExport && $exportUrl)
            <a href="{{ $exportUrl }}" class="inline-flex items-center px-3 py-2 border border-green-300 text-green-700 text-sm font-medium rounded-lg bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ $exportButtonText }}
            </a>
            @endif
        </div>
    </div>
    @endif

    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-xl">
        <div class="overflow-x-auto">
            <table id="{{ $id }}" class="{{ $tableClass }}">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        @foreach($columns as $column)
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ $column }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Data will be loaded via AJAX -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    @if($showModal)
    <x-modal name="data-form-modal" max-width="4xl">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900" x-text="modalTitle">{{ $modalTitle }}</h3>
                <button @click="$dispatch('close-modal')" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div x-show="isLoading" class="flex items-center justify-center py-12">
                <div class="flex flex-col items-center space-y-3">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <p class="text-sm text-gray-500">Loading...</p>
                </div>
            </div>

            <div x-show="!isLoading" x-html="modalContent" class="modal-content-area"></div>

            <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-200" x-show="!isViewMode && !isLoading">
                <button @click="$dispatch('close-modal')" type="button"
                        class="px-6 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    Cancel
                </button>
                <button @click="submitForm()" type="button"
                        class="px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105 shadow-md">
                    <span x-show="!isLoading">Save</span>
                    <span x-show="isLoading" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Saving...
                    </span>
                </button>
            </div>

            <div class="flex justify-center mt-8 pt-6 border-t border-gray-200" x-show="isViewMode && !isLoading">
                <button @click="$dispatch('close-modal')" type="button"
                        class="px-8 py-2 text-sm font-medium text-white bg-gradient-to-r from-gray-600 to-gray-700 rounded-lg hover:from-gray-700 hover:to-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                    Close
                </button>
            </div>
        </div>
    </x-modal>
    @endif
</div>

@push('scripts')
<script>
    // Alpine.js component for table management
    function tableManager() {
        return {
            modalTitle: 'Data Details',
            modalContent: '',
            isLoading: false,
            isViewMode: false,
            currentId: null,

            async openAddModal() {
                console.log('Opening add modal...');
                this.modalTitle = 'Add New {{ $modalTitle ?? "Item" }}';
                this.isViewMode = false;
                this.isLoading = true;
                this.currentId = null;

                this.$dispatch('open-modal', { name: 'data-form-modal' });

                try {
                    const url = '{{ $addUrl ?? "" }}';
                    console.log('Fetching URL:', url);

                    const response = await fetch(url, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    console.log('Response status:', response.status);

                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }

                    const contentType = response.headers.get('content-type');
                    console.log('Content type:', contentType);

                    if (contentType && contentType.includes('application/json')) {
                        const result = await response.json();
                        this.modalContent = result.html || '<div class="alert alert-danger">No content received</div>';
                    } else {
                        this.modalContent = await response.text();
                    }

                    console.log('Modal content loaded:', this.modalContent.length, 'characters');
                } catch (error) {
                    console.error('Error loading form:', error);
                    this.modalContent = `<div class="alert alert-danger p-4 mb-4 text-red-800 bg-red-100 border border-red-300 rounded-lg">
                        <strong>Error loading form:</strong><br>
                        ${error.message}
                    </div>`;
                } finally {
                    this.isLoading = false;
                }
            },

            async openViewModal(id) {
                this.modalTitle = 'View Details';
                this.isViewMode = true;
                this.isLoading = true;
                this.currentId = id;

                this.$dispatch('open-modal', { name: 'data-form-modal' });

                try {
                    const response = await fetch(`{{ $addUrl ?? "" }}/../${id}`);
                    this.modalContent = await response.text();
                } catch (error) {
                    this.modalContent = '<div class="alert alert-danger">Error loading details</div>';
                } finally {
                    this.isLoading = false;
                }
            },

            async openEditModal(id) {
                this.modalTitle = 'Edit {{ $modalTitle ?? "Item" }}';
                this.isViewMode = false;
                this.isLoading = true;
                this.currentId = id;

                this.$dispatch('open-modal', { name: 'data-form-modal' });

                try {
                    const response = await fetch(`{{ $addUrl ?? "" }}/../${id}/edit`);
                    this.modalContent = await response.text();
                } catch (error) {
                    this.modalContent = '<div class="alert alert-danger">Error loading form</div>';
                } finally {
                    this.isLoading = false;
                }
            },

            async submitForm() {
                // Cari form dalam modal dengan berbagai kemungkinan selector
                const form = document.querySelector('#data-form-modal form') ||
                           document.querySelector('.modal-content-area form') ||
                           document.querySelector('#brand-form');

                if (!form) {
                    console.error('Form not found in modal');
                    return;
                }

                this.isLoading = true;

                try {
                    const formData = new FormData(form);

                    // Tentukan URL berdasarkan currentId dan addUrl
                    let url;
                    let method = 'POST';

                    const baseUrl = '{{ $addUrl ?? "" }}';
                    console.log('Base addUrl:', baseUrl);

                    if (this.currentId) {
                        // Edit mode: /master-data/brands/1
                        url = baseUrl.replace('/create', '/' + this.currentId);
                        method = 'PUT';
                        formData.append('_method', 'PUT');
                    } else {
                        // Create mode: /master-data/brands
                        url = baseUrl.replace('/create', '');
                    }

                    console.log('Submitting form to:', url, 'Method:', method);

                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: formData
                    });

                    console.log('Response status:', response.status);

                    if (response.ok) {
                        const result = await response.json();

                        this.$dispatch('close-modal');

                        // Refresh table
                        if (window.dataTablesInstances && window.dataTablesInstances['{{ $id }}']) {
                            window.dataTablesInstances['{{ $id }}'].ajax.reload();
                        }

                        // Show success message
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Success!',
                                text: result.message || 'Operation completed successfully',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            alert(result.message || 'Operation completed successfully');
                        }
                    } else {
                        const result = await response.json();

                        // Handle validation errors
                        if (response.status === 422 && result.errors) {
                            this.showValidationErrors(result.errors);
                        } else {
                            throw new Error(result.message || 'Operation failed');
                        }
                    }
                } catch (error) {
                    console.error('Form submission error:', error);

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Error!',
                            text: error.message,
                            icon: 'error'
                        });
                    } else {
                        alert(error.message);
                    }
                } finally {
                    this.isLoading = false;
                }
            },

            showValidationErrors(errors) {
                // Clear previous errors
                const form = document.querySelector('#data-form-modal form');
                if (form) {
                    const errorElements = form.querySelectorAll('.invalid-feedback');
                    errorElements.forEach(el => el.remove());

                    const invalidInputs = form.querySelectorAll('.is-invalid');
                    invalidInputs.forEach(input => input.classList.remove('is-invalid'));
                }

                // Show new errors
                Object.keys(errors).forEach(fieldName => {
                    const field = form.querySelector(`[name="${fieldName}"]`);
                    if (field) {
                        field.classList.add('is-invalid');

                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = errors[fieldName][0];
                        field.parentNode.appendChild(errorDiv);
                    }
                });
            }
        }
    }

    // Global function to refresh any table
    function refreshTable(tableId) {
        if (window.dataTablesInstances && window.dataTablesInstances[tableId]) {
            window.dataTablesInstances[tableId].ajax.reload(null, false);
        }
    }

    // Initialize DataTables instances storage
    if (!window.dataTablesInstances) {
        window.dataTablesInstances = {};
    }

    // Currency formatter function
    function formatCurrency(amount) {
        if (amount === null || amount === undefined) return 'Rp 0';
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    }

    // Date formatter function
    function formatDate(dateString) {
        if (!dateString) return '-';
        return new Date(dateString).toLocaleDateString('id-ID');
    }

    // Badge status formatter
    function formatStatus(status, type = 'default') {
        const statusClasses = {
            'active': 'badge bg-success',
            'inactive': 'badge bg-secondary',
            'pending': 'badge bg-warning',
            'approved': 'badge bg-success',
            'rejected': 'badge bg-danger',
            'draft': 'badge bg-secondary',
            'published': 'badge bg-success'
        };

        const className = statusClasses[status] || 'badge bg-secondary';
        return `<span class="${className}">${status.charAt(0).toUpperCase() + status.slice(1)}</span>`;
    }

    // Generic action buttons generator for modal usage
    function generateModalActionButtons(row, tableManagerInstance, options = {}) {
        const {
            showView = true,
            showEdit = true,
            showDelete = true,
            deleteClass = 'delete-btn',
            customButtons = []
        } = options;

        let buttons = '';

        if (showView) {
            buttons += `<button type="button" class="btn btn-sm btn-info me-1" onclick="Alpine.store('${tableManagerInstance}').openViewModal(${row.id})" title="View">
                <i class="fas fa-eye"></i>
            </button>`;
        }

        if (showEdit) {
            buttons += `<button type="button" class="btn btn-sm btn-warning me-1" onclick="Alpine.store('${tableManagerInstance}').openEditModal(${row.id})" title="Edit">
                <i class="fas fa-edit"></i>
            </button>`;
        }

        if (showDelete) {
            buttons += `<button type="button" class="btn btn-sm btn-danger ${deleteClass}" data-id="${row.id}" title="Delete">
                <i class="fas fa-trash"></i>
            </button>`;
        }

        // Add custom buttons
        customButtons.forEach(button => {
            buttons += button.replace(':id', row.id);
        });

        return `<div class="btn-group" role="group">${buttons}</div>`;
    }

    // Original action buttons generator (for backward compatibility)
    function generateActionButtons(row, options = {}) {
        const {
            showView = true,
            showEdit = true,
            showDelete = true,
            viewUrl = '',
            editUrl = '',
            deleteClass = 'delete-btn',
            customButtons = []
        } = options;

        let buttons = '';

        if (showView && viewUrl) {
            const url = viewUrl.replace(':id', row.id);
            buttons += `<a href="${url}" class="btn btn-sm btn-info me-1" title="View">
                <i class="fas fa-eye"></i>
            </a>`;
        }

        if (showEdit && editUrl) {
            const url = editUrl.replace(':id', row.id);
            buttons += `<a href="${url}" class="btn btn-sm btn-warning me-1" title="Edit">
                <i class="fas fa-edit"></i>
            </a>`;
        }

        if (showDelete) {
            buttons += `<button type="button" class="btn btn-sm btn-danger ${deleteClass}" data-id="${row.id}" title="Delete">
                <i class="fas fa-trash"></i>
            </button>`;
        }

        // Add custom buttons
        customButtons.forEach(button => {
            buttons += button.replace(':id', row.id);
        });

        return `<div class="btn-group" role="group">${buttons}</div>`;
    }
</script>
@endpush
