<x-crud.index
    title="Brands Management"
    table-id="brands-table"
    :ajax-url="route('master-data.brands.index')"
    :create-url="route('master-data.brands.create')"
    :export-url="route('master-data.brands.index', ['export' => 'excel'])"
    :columns="['#', 'Name', 'Logo', 'Status', 'Actions']"
    :show-add="true"
    :show-export="true"
    add-button-text="Add Brand"
    export-button-text="Export Excel"
    modal-title="Brand"
    :breadcrumbs="[
        ['title' => 'Dashboard', 'url' => route('home')],
        ['title' => 'Brand']
    ]"
>
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof $ !== 'undefined' && $.fn.DataTable) {
                const table = $('#brands-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: {
                        url: '{{ route("master-data.brands.index") }}',
                        type: 'GET'
                    },
                    columns: [
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            className: 'text-center',
                            width: '5%'
                        },
                        {
                            data: 'name',
                            name: 'name',
                            title: 'Name'
                        },
                        {
                            data: 'logo_url',
                            name: 'logo_url',
                            title: 'Logo',
                            orderable: false,
                            render: function(data, type, row) {
                                if (data) {
                                    return `<img src="${data}" alt="Logo" class="h-8 w-8 object-cover rounded" onerror="this.style.display='none'">`;
                                }
                                return '<span class="text-gray-400">No logo</span>';
                            }
                        },
                        {
                            data: 'is_active',
                            name: 'is_active',
                            title: 'Status',
                            className: 'text-center',
                            render: function(data, type, row) {
                                if (data) {
                                    return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>';
                                } else {
                                    return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>';
                                }
                            }
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-center',
                            width: '15%',
                            render: function(data, type, row) {
                                return `
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-info me-1 view-btn" data-id="${row.id}" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning me-1 edit-btn" data-id="${row.id}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="${row.id}" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                `;
                            }
                        }
                    ],
                });

                // Store table instance globally for refresh function
                window.dataTablesInstances = window.dataTablesInstances || {};
                window.dataTablesInstances['brands-table'] = table;

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

                // Handle delete button with SweetAlert2 if available
                $(document).on('click', '.delete-btn', function() {
                    const id = $(this).data('id');
                    const itemName = $(this).closest('tr').find('td:eq(1)').text();

                    const deleteConfirm = function() {
                        $.ajax({
                            url: '{{ route("master-data.brands.index") }}/' + id,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                'Accept': 'application/json'
                            },
                            success: function(response) {
                                if (response.success) {
                                    table.ajax.reload(null, false);

                                    // Show success message
                                    if (typeof Swal !== 'undefined') {
                                        Swal.fire({
                                            title: 'Deleted!',
                                            text: response.message,
                                            icon: 'success',
                                            timer: 2000,
                                            showConfirmButton: false
                                        });
                                    } else {
                                        alert(response.message);
                                    }
                                }
                            },
                            error: function(xhr) {
                                const response = xhr.responseJSON;
                                const message = response ? response.message : 'Error deleting item';

                                if (typeof Swal !== 'undefined') {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: message,
                                        icon: 'error'
                                    });
                                } else {
                                    alert(message);
                                }
                            }
                        });
                    };

                    // Use SweetAlert2 if available, otherwise use confirm
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Are you sure?',
                            text: `Delete item "${itemName}"? This action cannot be undone!`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Yes, delete it!',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                deleteConfirm();
                            }
                        });
                    } else {
                        if (confirm(`Are you sure you want to delete "${itemName}"?`)) {
                            deleteConfirm();
                        }
                    }
                });
            } else {
                console.error('jQuery or DataTables not loaded');
            }
        });
    </script>
    @endpush
</x-crud.index>
