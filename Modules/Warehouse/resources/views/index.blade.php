@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-warehouse text-primary"></i> Manajemen Gudang
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Gudang</li>
            </ol>
        </nav>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Gudang
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $warehouses->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-warehouse fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Gudang Aktif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $warehouses->where('is_active', true)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Gudang Tidak Aktif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $warehouses->where('is_active', false)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Warehouses Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list"></i> Daftar Gudang
            </h6>
            @can('create warehouses')
            <a href="{{ route('warehouse.warehouses.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Gudang
            </a>
            @endcan
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="warehousesTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Kode</th>
                            <th width="25%">Nama Gudang</th>
                            <th width="20%">Lokasi</th>
                            <th width="15%">Manager</th>
                            <th width="10%">Status</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($warehouses as $index => $warehouse)
                        <tr>
                            <td>{{ $warehouses->firstItem() + $index }}</td>
                            <td>
                                <span class="badge badge-outline-info font-weight-bold">{{ $warehouse->code }}</span>
                            </td>
                            <td>
                                <div class="font-weight-bold text-primary">{{ $warehouse->name }}</div>
                            </td>
                            <td>
                                <i class="fas fa-map-marker-alt text-danger"></i> {{ $warehouse->location }}
                            </td>
                            <td>
                                @if($warehouse->manager)
                                    <i class="fas fa-user text-success"></i> {{ $warehouse->manager->name }}
                                @else
                                    <span class="text-muted">Belum ditentukan</span>
                                @endif
                            </td>
                            <td>
                                @if($warehouse->is_active)
                                    <span class="badge badge-success">
                                        <i class="fas fa-check"></i> Aktif
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        <i class="fas fa-times"></i> Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    @can('view warehouses')
                                    <a href="{{ route('warehouse.warehouses.show', $warehouse) }}"
                                       class="btn btn-info btn-sm" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endcan
                                    @can('edit warehouses')
                                    <a href="{{ route('warehouse.warehouses.edit', $warehouse) }}"
                                       class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan
                                    @can('delete warehouses')
                                    <button type="button" class="btn btn-danger btn-sm"
                                            onclick="deleteWarehouse({{ $warehouse->id }})" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-warehouse fa-3x text-gray-300 mb-3"></i>
                                <div>Belum ada gudang yang terdaftar.</div>
                                @can('create warehouses')
                                <a href="{{ route('warehouse.warehouses.create') }}" class="btn btn-primary btn-sm mt-2">
                                    <i class="fas fa-plus"></i> Tambah Gudang Pertama
                                </a>
                                @endcan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($warehouses->hasPages())
            <div class="row mt-3">
                <div class="col-sm-12 col-md-5">
                    <div class="dataTables_info">
                        Menampilkan {{ $warehouses->firstItem() }} sampai {{ $warehouses->lastItem() }}
                        dari {{ $warehouses->total() }} gudang
                    </div>
                </div>
                <div class="col-sm-12 col-md-7">
                    {{ $warehouses->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.badge-outline-info {
    color: #17a2b8;
    border: 1px solid #17a2b8;
    background-color: transparent;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.card {
    border: 0;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.table th {
    border-top: none;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable if there are warehouses
    @if($warehouses->count() > 0)
    $('#warehousesTable').DataTable({
        "pageLength": 15,
        "responsive": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "order": [[1, 'asc']], // Order by code
        "columnDefs": [
            { "orderable": false, "targets": [6] }, // Action column
            { "searchable": false, "targets": [0, 6] } // No and Action columns
        ]
    });
    @endif
});

function deleteWarehouse(id) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: 'Apakah Anda yakin ingin menghapus gudang ini? Data yang terkait dengan gudang ini mungkin akan terpengaruh!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create and submit form
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("warehouse.warehouses.destroy", "") }}/' + id;

            let csrfField = document.createElement('input');
            csrfField.type = 'hidden';
            csrfField.name = '_token';
            csrfField.value = '{{ csrf_token() }}';

            let methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';

            form.appendChild(csrfField);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush
@endsection
