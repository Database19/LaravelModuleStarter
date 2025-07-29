@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-clipboard-check text-primary"></i> Quality Control - Pemeriksaan Kualitas
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Pemeriksaan Kualitas</li>
            </ol>
        </nav>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Pemeriksaan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $qualityChecks->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Lulus QC
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $qualityChecks->where('status', 'passed')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Perlu Review
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $qualityChecks->where('status', 'pending')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Tidak Lulus
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $qualityChecks->where('status', 'failed')->count() }}
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

    <!-- Quality Checks Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list"></i> Daftar Pemeriksaan Kualitas
            </h6>
            @can('create quality-checks')
            <a href="{{ route('qualitycontrol.checks.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Pemeriksaan
            </a>
            @endcan
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="qualityChecksTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Check Number</th>
                            <th width="10%">Tipe</th>
                            <th width="15%">Produk</th>
                            <th width="12%">Inspector</th>
                            <th width="10%">Tanggal</th>
                            <th width="8%">Status</th>
                            <th width="10%">Score</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($qualityChecks as $index => $check)
                        <tr>
                            <td>{{ $qualityChecks->firstItem() + $index }}</td>
                            <td>
                                <span class="font-weight-bold text-primary">{{ $check->check_number }}</span>
                            </td>
                            <td>
                                @php
                                    $typeClasses = [
                                        'incoming' => 'info',
                                        'in_process' => 'warning',
                                        'final' => 'success',
                                        'customer_return' => 'danger'
                                    ];
                                    $typeLabels = [
                                        'incoming' => 'Incoming',
                                        'in_process' => 'In Process',
                                        'final' => 'Final',
                                        'customer_return' => 'Return'
                                    ];
                                @endphp
                                <span class="badge badge-{{ $typeClasses[$check->type] ?? 'secondary' }}">
                                    {{ $typeLabels[$check->type] ?? ucfirst($check->type) }}
                                </span>
                            </td>
                            <td>
                                <div class="font-weight-bold">{{ $check->product->name ?? 'N/A' }}</div>
                                @if($check->product && $check->product->code)
                                <small class="text-muted">{{ $check->product->code }}</small>
                                @endif
                            </td>
                            <td>
                                <i class="fas fa-user text-info"></i> {{ $check->inspector->name ?? 'N/A' }}
                            </td>
                            <td>
                                {{ $check->inspection_date ? $check->inspection_date->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td>
                                @php
                                    $statusClasses = [
                                        'pending' => 'warning',
                                        'passed' => 'success',
                                        'failed' => 'danger',
                                        'conditional' => 'info'
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Pending',
                                        'passed' => 'Lulus',
                                        'failed' => 'Gagal',
                                        'conditional' => 'Bersyarat'
                                    ];
                                @endphp
                                <span class="badge badge-{{ $statusClasses[$check->status] ?? 'secondary' }}">
                                    {{ $statusLabels[$check->status] ?? ucfirst($check->status) }}
                                </span>
                            </td>
                            <td>
                                @if($check->overall_score !== null)
                                    @php
                                        $scoreClass = $check->overall_score >= 80 ? 'success' : ($check->overall_score >= 60 ? 'warning' : 'danger');
                                    @endphp
                                    <span class="badge badge-{{ $scoreClass }}">
                                        {{ number_format($check->overall_score, 1) }}%
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    @can('view quality-checks')
                                    <a href="{{ route('qualitycontrol.checks.show', $check) }}"
                                       class="btn btn-info btn-sm" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endcan
                                    @can('edit quality-checks')
                                    <a href="{{ route('qualitycontrol.checks.edit', $check) }}"
                                       class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan
                                    @can('delete quality-checks')
                                    <button type="button" class="btn btn-danger btn-sm"
                                            onclick="deleteCheck({{ $check->id }})" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="fas fa-clipboard-check fa-3x text-gray-300 mb-3"></i>
                                <div>Belum ada pemeriksaan kualitas yang dilakukan.</div>
                                @can('create quality-checks')
                                <a href="{{ route('qualitycontrol.checks.create') }}" class="btn btn-primary btn-sm mt-2">
                                    <i class="fas fa-plus"></i> Tambah Pemeriksaan Pertama
                                </a>
                                @endcan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($qualityChecks->hasPages())
            <div class="row mt-3">
                <div class="col-sm-12 col-md-5">
                    <div class="dataTables_info">
                        Menampilkan {{ $qualityChecks->firstItem() }} sampai {{ $qualityChecks->lastItem() }}
                        dari {{ $qualityChecks->total() }} pemeriksaan
                    </div>
                </div>
                <div class="col-sm-12 col-md-7">
                    {{ $qualityChecks->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
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
    // Initialize DataTable if there are quality checks
    @if($qualityChecks->count() > 0)
    $('#qualityChecksTable').DataTable({
        "pageLength": 15,
        "responsive": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "order": [[5, 'desc']], // Order by date desc
        "columnDefs": [
            { "orderable": false, "targets": [8] }, // Action column
            { "searchable": false, "targets": [0, 8] } // No and Action columns
        ]
    });
    @endif
});

function deleteCheck(id) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: 'Apakah Anda yakin ingin menghapus pemeriksaan kualitas ini?',
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
            form.action = '{{ route("qualitycontrol.checks.destroy", "") }}/' + id;

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
