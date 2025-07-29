// DataTables Configuration with TailwindCSS
export function initializeDataTable(tableId, config = {}) {
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
        dom: '<"flex flex-col lg:flex-row lg:items-center lg:justify-between mb-4"<"mb-2 lg:mb-0"l><"flex items-center space-x-2"fB>>rtip',
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
        buttons: {
            dom: {
                button: {
                    className: 'inline-flex items-center px-3 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 shadow-sm mr-2'
                }
            },
            buttons: [
                {
                    extend: 'copy',
                    text: '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>Copy',
                    className: 'inline-flex items-center px-3 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 shadow-sm mr-2'
                },
                {
                    extend: 'excel',
                    text: '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>Excel',
                    className: 'inline-flex items-center px-3 py-2 border border-green-300 text-green-700 text-sm font-medium rounded-lg bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 shadow-sm mr-2'
                },
                {
                    extend: 'pdf',
                    text: '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>PDF',
                    className: 'inline-flex items-center px-3 py-2 border border-red-300 text-red-700 text-sm font-medium rounded-lg bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200 shadow-sm mr-2'
                }
            ]
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

// Refresh table function
window.refreshTable = function(tableId) {
    const table = $('#' + tableId).DataTable();
    table.ajax.reload(null, false); // false = keep current page
}

// Global table manager for Alpine.js
window.tableManager = function() {
    return {
        modalTitle: 'Data Details',
        modalContent: '',
        isLoading: false,
        isViewMode: false,
        formAction: '',

        init() {
            // Initialize any table-specific settings
        },

        openAddModal() {
            this.modalTitle = 'Add New Record';
            this.isViewMode = false;
            this.loadModalContent('add');
        },

        openEditModal(id) {
            this.modalTitle = 'Edit Record';
            this.isViewMode = false;
            this.loadModalContent('edit', id);
        },

        openViewModal(id) {
            this.modalTitle = 'View Record';
            this.isViewMode = true;
            this.loadModalContent('view', id);
        },

        loadModalContent(action, id = null) {
            this.isLoading = true;
            let url = '';

            switch(action) {
                case 'add':
                    url = window.location.pathname + '/create';
                    break;
                case 'edit':
                    url = window.location.pathname + '/' + id + '/edit';
                    break;
                case 'view':
                    url = window.location.pathname + '/' + id;
                    break;
            }

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            })
            .then(response => response.text())
            .then(html => {
                this.modalContent = html;
                this.isLoading = false;
                this.$dispatch('open-modal', 'data-form-modal');
            })
            .catch(error => {
                console.error('Error loading modal content:', error);
                this.isLoading = false;
                Swal.fire('Error', 'Failed to load content', 'error');
            });
        },

        submitForm() {
            const form = document.querySelector('#data-form-modal form');
            if (!form) {
                Swal.fire('Error', 'Form not found', 'error');
                return;
            }

            this.isLoading = true;
            const formData = new FormData(form);

            fetch(form.action, {
                method: form.method || 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                this.isLoading = false;
                if (data.success) {
                    Swal.fire('Success', data.message || 'Operation completed successfully', 'success');
                    this.$dispatch('close-modal');
                    // Refresh the table
                    if (window.dataTable) {
                        window.dataTable.ajax.reload(null, false);
                    }
                } else {
                    Swal.fire('Error', data.message || 'Operation failed', 'error');
                }
            })
            .catch(error => {
                this.isLoading = false;
                console.error('Error submitting form:', error);
                Swal.fire('Error', 'An error occurred while submitting the form', 'error');
            });
        }
    }
}
