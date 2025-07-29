@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Product List</h2>
    </div>

<x-crud.index
    title="Product Management"
    table-id="products-table"
    :ajax-url="route('master-data.products.index')"
    :create-url="route('master-data.products.create')"
    :export-url="route('master-data.products.index', ['export' => 'excel'])"
    :columns="['#', 'Name', 'SKU', 'Brand', 'Unit', 'Category', 'Price', 'Actions']"
    :show-add="true"
    :show-export="true"
    add-button-text="Add Product"
    export-button-text="Export Excel"
    modal-title="Product"
>
    @push('scripts')
    <script>
        // DataTables Modern Configuration
        function initializeModernDataTable(tableId, config = {}) {
            // Check if DataTable is already initialized and destroy it
            if ($.fn.DataTable.isDataTable('#' + tableId)) {
                $('#' + tableId).DataTable().destroy();
            }

            const defaultConfig = {
                processing: true,
                serverSide: true,
                responsive: true,
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                dom: '<"flex flex-col lg:flex-row lg:items-center lg:justify-between mb-4"<"mb-2 lg:mb-0"l><"flex items-center space-x-2"f>>rtip',
                language: {
                    search: '',
                    searchPlaceholder: 'Search records...',
                    lengthMenu: 'Show _MENU_ entries',
                    info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                    infoEmpty: 'Showing 0 to 0 of 0 entries',
                    infoFiltered: '(filtered from _MAX_ total entries)',
                    paginate: {
                        first: 'First',
                        last: 'Last',
                        next: 'Next',
                        previous: 'Previous'
                    },
                    processing: '<div class="flex items-center justify-center py-8"><div class="flex flex-col items-center space-y-3"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div><p class="text-sm text-gray-500">Loading data...</p></div></div>',
                    emptyTable: '<div class="flex flex-col items-center justify-center py-12"><svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg><p class="text-lg font-medium text-gray-700 mb-2">No data available</p><p class="text-sm text-gray-500">There are no records to display at the moment.</p></div>',
                    zeroRecords: '<div class="flex flex-col items-center justify-center py-12"><svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg><p class="text-lg font-medium text-gray-700 mb-2">No matching records found</p><p class="text-sm text-gray-500">Try adjusting your search criteria.</p></div>'
                },
                drawCallback: function() {
                    // Apply TailwindCSS classes after each draw
                    $(this.api().table().container()).find('input[type="search"]').addClass('block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200');
                    $(this.api().table().container()).find('select').addClass('block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200');

                    // Style pagination
                    $(this.api().table().container()).find('.paginate_button').addClass('relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 hover:bg-gray-50 hover:text-gray-700 focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200');
                    $(this.api().table().container()).find('.paginate_button.current').addClass('z-10 bg-blue-50 border-blue-500 text-blue-600').removeClass('text-gray-500');
                    $(this.api().table().container()).find('.paginate_button.disabled').addClass('text-gray-300 cursor-not-allowed').removeClass('hover:bg-gray-50 hover:text-gray-700');
                }
            };

            const finalConfig = $.extend(true, {}, defaultConfig, config);
            return $('#' + tableId).DataTable(finalConfig);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTable with modern configuration
            const table = initializeModernDataTable('products-table', {
                ajax: {
                    url: '{{ route("master-data.products.index") }}',
                    type: 'GET'
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        title: '#',
                        orderable: false,
                        searchable: false,
                        className: 'text-center font-medium text-gray-600',
                        width: '50px'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        title: 'Product Name',
                        className: 'font-medium text-gray-900'
                    },
                    {
                        data: 'sku',
                        name: 'sku',
                        title: 'SKU',
                        className: 'font-mono text-sm text-gray-700'
                    },
                    {
                        data: 'brand_name',
                        name: 'brand.name',
                        title: 'Brand',
                        defaultContent: '<span class="text-gray-400 italic">No brand</span>',
                        className: 'text-gray-700'
                    },
                    {
                        data: 'unit_name',
                        name: 'unit.name',
                        title: 'Unit',
                        defaultContent: '<span class="text-gray-400 italic">No unit</span>',
                        className: 'text-gray-700'
                    },
                    {
                        data: 'category_name',
                        name: 'category.name',
                        title: 'Category',
                        defaultContent: '<span class="text-gray-400 italic">No category</span>',
                        className: 'text-gray-700'
                    },
                    {
                        data: 'price',
                        name: 'price',
                        title: 'Price',
                        className: 'text-right font-semibold text-gray-900',
                        render: function(data, type, row) {
                            if (type === 'display' && data) {
                                return '<span class="text-emerald-600">Rp ' + new Intl.NumberFormat('id-ID').format(data) + '</span>';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        title: 'Actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '120px',
                        render: function(data, type, row) {
                            return `
                                <div class="flex justify-center items-center space-x-1">
                                    <button type="button"
                                            class="inline-flex items-center p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-all duration-200 transform hover:scale-110 view-btn"
                                            data-id="${row.id}"
                                            title="View Product Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                    <button type="button"
                                            class="inline-flex items-center p-1.5 text-emerald-600 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg transition-all duration-200 transform hover:scale-110 edit-btn"
                                            data-id="${row.id}"
                                            title="Edit Product">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button type="button"
                                            class="inline-flex items-center p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-all duration-200 transform hover:scale-110 delete-btn"
                                            data-id="${row.id}"
                                            title="Delete Product">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            `;
                        }
                    }
                ],
                order: [[1, 'asc']]
            });

            // Store table instance globally
            window.dataTablesInstances = window.dataTablesInstances || {};
            window.dataTablesInstances['products-table'] = table;

            // Global functions for table manager compatibility
            window.tableManager = {
                openAddModal: function() {
                    window.location.href = '{{ route("master-data.products.create") }}';
                },
                openViewModal: function(id) {
                    window.location.href = '{{ route("master-data.products.index") }}/' + id;
                },
                openEditModal: function(id) {
                    window.location.href = '{{ route("master-data.products.index") }}/' + id + '/edit';
                }
            };

            // Get Alpine component instance
            const getAlpineComponent = () => {
                return Alpine.$data(document.querySelector('[x-data*="tableManager"]'));
            };

            // Handle view button
            $(document).on('click', '.view-btn', function() {
                const id = $(this).data('id');
                const alpine = getAlpineComponent();
                if (alpine) {
                    alpine.openViewModal(id);
                }
            });

            // Handle edit button
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                const alpine = getAlpineComponent();
                if (alpine) {
                    alpine.openEditModal(id);
                }
            });

            // Handle delete button with modern SweetAlert2 styling
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                const productName = $(this).closest('tr').find('td:eq(1)').text();

                const deleteConfirm = function() {
                    $.ajax({
                        url: '{{ route("master-data.products.index") }}/' + id,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Accept': 'application/json'
                        },
                        success: function(response) {
                            if (response.success) {
                                table.ajax.reload(null, false);

                                Swal.fire({
                                    title: 'Successfully Deleted!',
                                    text: response.message || 'Product has been deleted successfully.',
                                    icon: 'success',
                                    timer: 3000,
                                    showConfirmButton: false,
                                    toast: true,
                                    position: 'top-end',
                                    background: '#f0fdf4',
                                    color: '#166534',
                                    iconColor: '#22c55e'
                                });
                            }
                        },
                        error: function(xhr) {
                            const response = xhr.responseJSON;
                            const message = response ? response.message : 'Error deleting product';

                            Swal.fire({
                                title: 'Delete Failed!',
                                text: message,
                                icon: 'error',
                                confirmButtonColor: '#ef4444',
                                background: '#fef2f2',
                                color: '#dc2626'
                            });
                        }
                    });
                };

                // Modern SweetAlert2 confirmation
                Swal.fire({
                    title: 'Delete Product?',
                    html: `Are you sure you want to delete <strong>"${productName}"</strong>?<br><small class="text-gray-500">This action cannot be undone.</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<i class="fas fa-trash mr-2"></i>Yes, Delete',
                    cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
                    reverseButtons: true,
                    focusCancel: true,
                    background: '#ffffff',
                    customClass: {
                        confirmButton: 'btn-delete-confirm',
                        cancelButton: 'btn-delete-cancel',
                        popup: 'swal-delete-popup'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteConfirm();
                    }
                });
            });
        });
    </script>

    <style>
        /* Custom SweetAlert2 styles */
        .btn-delete-confirm {
            @apply px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200 font-medium mr-2;
        }
        .btn-delete-cancel {
            @apply px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200 font-medium;
        }
        .swal-delete-popup {
            @apply shadow-2xl border border-gray-200;
        }

        /* Enhanced DataTable styling */
        .dataTables_wrapper {
            @apply font-sans;
        }

        .dataTables_wrapper .dataTables_length select {
            @apply appearance-none bg-white border border-gray-300 rounded-lg px-3 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500;
        }

        .dataTables_wrapper .dataTables_filter input {
            @apply appearance-none bg-white border border-gray-300 rounded-lg px-4 py-2 text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            @apply relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 hover:bg-gray-50 hover:text-gray-700 focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 cursor-pointer;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            @apply z-10 bg-blue-50 border-blue-500 text-blue-600;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            @apply text-gray-300 cursor-not-allowed hover:bg-white hover:text-gray-300;
        }
    </style>
    @endpush
</x-crud.index>
</div>
@endsection
