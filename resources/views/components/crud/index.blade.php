@props([
    'title' => 'Data Management',
    'tableId' => 'data-table',
    'ajaxUrl' => '',
    'createUrl' => '',
    'exportUrl' => '',
    'columns' => [],
    'showAdd' => true,
    'showExport' => false,
    'addButtonText' => 'Add New',
    'exportButtonText' => 'Export',
    'modalTitle' => 'Data',
    'breadcrumbs' => []
])

@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-6" x-data="crudManager()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(!empty($breadcrumbs))
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                @foreach($breadcrumbs as $breadcrumb)
                    @if($loop->last)
                        <li class="inline-flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-700 md:ml-2">{{ $breadcrumb['title'] }}</span>
                        </li>
                    @else
                        <li class="inline-flex items-center">
                            @if($loop->first)
                                <a href="{{ $breadcrumb['url'] ?? '#' }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
                                    <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                                    </svg>
                                    {{ $breadcrumb['title'] }}
                                </a>
                            @else
                                <a href="{{ $breadcrumb['url'] ?? '#' }}" class="ml-1 text-sm font-medium text-blue-600 hover:text-blue-800 md:ml-2">{{ $breadcrumb['title'] }}</a>
                            @endif
                        </li>
                    @endif
                @endforeach
            </ol>
        </nav>
        @endif

        <!-- Header Section -->
        <div class="mb-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h1 class="text-3xl font-bold text-gray-900 sm:text-4xl sm:truncate">
                        {{ $title }}
                    </h1>
                    <p class="mt-2 text-sm text-gray-600">
                        Manage your {{ strtolower($modalTitle ?? 'data') }} efficiently with our modern interface
                    </p>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                    @if($showExport && $exportUrl)
                    <a href="{{ $exportUrl }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        {{ $exportButtonText }}
                    </a>
                    @endif

                    @if($showAdd && $createUrl)
                    <button type="button" @click="openAddModal()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm bg-gradient-to-r from-blue-600 to-blue-700 text-sm font-medium text-white hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        {{ $addButtonText }}
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white shadow-xl rounded-2xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg leading-6 font-semibold text-gray-900">
                        {{ $modalTitle }} List
                    </h3>
                    <button onclick="refreshTable('{{ $tableId }}')" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Refresh
                    </button>
                </div>
            </div>

            <div class="p-6">
                <x-table.datatable
                    :id="$tableId"
                    :columns="$columns"
                    :show-export="false"
                    :show-refresh="false"
                    :show-add="false"
                    :add-url="$createUrl"
                    :add-button-text="$addButtonText"
                    :export-url="$exportUrl"
                    :export-button-text="$exportButtonText"
                    :modal-title="$modalTitle"
                    table-class="table-auto w-full divide-y divide-gray-200"
                />
            </div>
        </div>
    </div>
</div>

<style>
/* Custom DataTables styling with Tailwind */
.dataTables_wrapper {
    @apply w-full;
}

.dataTables_length select {
    @apply px-3 py-1 text-sm border border-gray-300 rounded-md bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500;
}

.dataTables_filter input {
    @apply px-4 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm;
}

.dataTables_paginate .paginate_button {
    @apply px-3 py-1 mx-1 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200;
}

.dataTables_paginate .paginate_button.current {
    @apply bg-blue-600 text-white border-blue-600 hover:bg-blue-700 hover:text-white;
}

.dataTables_paginate .paginate_button.disabled {
    @apply text-gray-400 bg-gray-100 cursor-not-allowed hover:bg-gray-100 hover:text-gray-400;
}

table.dataTable thead th {
    @apply px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50 border-b border-gray-200;
}

table.dataTable tbody td {
    @apply px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b border-gray-100;
}

table.dataTable tbody tr:hover {
    @apply bg-blue-50;
}

.dataTables_info {
    @apply text-sm text-gray-600;
}

.dataTables_processing {
    @apply fixed inset-0 flex items-center justify-center bg-black bg-opacity-25 z-50;
}

.dataTables_processing::after {
    content: '';
    @apply w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full animate-spin;
}
</style>

@endsection

{{ $slot ?? '' }}

@push('scripts')
<script>
    // Alpine.js CRUD Manager
    function crudManager() {
        return {
            modalTitle: '{{ $modalTitle }}',
            modalContent: '',
            isLoading: false,
            isViewMode: false,
            currentId: null,

            openAddModal() {
                this.modalTitle = 'Add New {{ $modalTitle }}';
                this.isViewMode = false;
                this.currentId = null;

                // Find the table manager in the DataTable component
                const tableComponent = document.querySelector('[x-data*="tableManager"]');
                if (tableComponent) {
                    const tableManager = Alpine.$data(tableComponent);
                    if (tableManager && typeof tableManager.openAddModal === 'function') {
                        tableManager.openAddModal();
                    }
                } else {
                    // Fallback: redirect to create URL
                    window.location.href = '{{ $createUrl }}';
                }
            },

            openViewModal(id) {
                this.currentId = id;
                const tableComponent = document.querySelector('[x-data*="tableManager"]');
                if (tableComponent) {
                    const tableManager = Alpine.$data(tableComponent);
                    if (tableManager && typeof tableManager.openViewModal === 'function') {
                        tableManager.openViewModal(id);
                    }
                }
            },

            openEditModal(id) {
                this.currentId = id;
                const tableComponent = document.querySelector('[x-data*="tableManager"]');
                if (tableComponent) {
                    const tableManager = Alpine.$data(tableComponent);
                    if (tableManager && typeof tableManager.openEditModal === 'function') {
                        tableManager.openEditModal(id);
                    }
                }
            }
        }
    }
</script>
@endpush
