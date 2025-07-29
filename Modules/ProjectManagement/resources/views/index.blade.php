@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-project-diagram text-primary"></i> Manajemen Proyek
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Proyek</li>
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
                                Total Proyek
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $projects->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder fa-2x text-gray-300"></i>
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
                                Proyek Aktif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $projects->where('status', 'in_progress')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-play fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Proyek Selesai
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $projects->where('status', 'completed')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
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
                                Proyek Tertunda
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $projects->where('status', 'on_hold')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-pause fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Projects Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list"></i> Daftar Proyek
            </h6>
            @can('create projects')
            <a href="{{ route('project.management.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Buat Proyek Baru
            </a>
            @endcan
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="projectsTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="20%">Nama Proyek</th>
                            <th width="15%">Kode</th>
                            <th width="15%">Pelanggan</th>
                            <th width="15%">Manajer Proyek</th>
                            <th width="10%">Status</th>
                            <th width="10%">Progress</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $index => $project)
                        <tr>
                            <td>{{ $projects->firstItem() + $index }}</td>
                            <td>
                                <div class="font-weight-bold text-primary">{{ $project->name }}</div>
                                @if($project->description)
                                <small class="text-muted">{{ Str::limit($project->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-outline-info">{{ $project->code }}</span>
                            </td>
                            <td>
                                @if($project->customer)
                                    <i class="fas fa-building text-info"></i> {{ $project->customer->name }}
                                @else
                                    <i class="fas fa-home text-muted"></i> <span class="text-muted">Internal</span>
                                @endif
                            </td>
                            <td>
                                @if($project->manager)
                                    <i class="fas fa-user text-success"></i> {{ $project->manager->name }}
                                @else
                                    <span class="text-muted">Belum ditentukan</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusClasses = [
                                        'planning' => 'secondary',
                                        'in_progress' => 'success',
                                        'on_hold' => 'warning',
                                        'completed' => 'info',
                                        'cancelled' => 'danger'
                                    ];
                                    $statusLabels = [
                                        'planning' => 'Perencanaan',
                                        'in_progress' => 'Berlangsung',
                                        'on_hold' => 'Tertunda',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan'
                                    ];
                                @endphp
                                <span class="badge badge-{{ $statusClasses[$project->status] ?? 'secondary' }}">
                                    {{ $statusLabels[$project->status] ?? ucfirst($project->status) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $progress = $project->progress ?? 0;
                                    $progressClass = $progress < 30 ? 'danger' : ($progress < 70 ? 'warning' : 'success');
                                @endphp
                                <div class="progress" style="height: 15px;">
                                    <div class="progress-bar bg-{{ $progressClass }}" role="progressbar"
                                         style="width: {{ $progress }}%">
                                        {{ $progress }}%
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    @can('view projects')
                                    <a href="{{ route('project.management.show', $project) }}"
                                       class="btn btn-info btn-sm" title="Lihat Papan Tugas">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endcan
                                    @can('edit projects')
                                    <a href="{{ route('project.management.edit', $project) }}"
                                       class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan
                                    @can('delete projects')
                                    <button type="button" class="btn btn-danger btn-sm"
                                            onclick="deleteProject({{ $project->id }})" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-folder-open fa-3x text-gray-300 mb-3"></i>
                                <div>Belum ada proyek yang dibuat.</div>
                                @can('create projects')
                                <a href="{{ route('project.management.create') }}" class="btn btn-primary btn-sm mt-2">
                                    <i class="fas fa-plus"></i> Buat Proyek Pertama
                                </a>
                                @endcan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($projects->hasPages())
            <div class="row mt-3">
                <div class="col-sm-12 col-md-5">
                    <div class="dataTables_info">
                        Menampilkan {{ $projects->firstItem() }} sampai {{ $projects->lastItem() }}
                        dari {{ $projects->total() }} proyek
                    </div>
                </div>
                <div class="col-sm-12 col-md-7">
                    {{ $projects->links() }}
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

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.progress {
    border-radius: 0.5rem;
}

.card {
    border: 0;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable if there are projects
    @if($projects->count() > 0)
    $('#projectsTable').DataTable({
        "pageLength": 15,
        "responsive": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "order": [[0, 'asc']],
        "columnDefs": [
            { "orderable": false, "targets": [7] }, // Action column
            { "searchable": false, "targets": [0, 7] } // No and Action columns
        ]
    });
    @endif
});

function deleteProject(id) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: 'Apakah Anda yakin ingin menghapus proyek ini? Semua data tugas dan progress akan ikut terhapus!',
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
            form.action = '{{ route("project.management.destroy", "") }}/' + id;

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
        <div class="p-4 bg-gray-50 border-t">
            {{ $projects->links() }}
        </div>
    </div>
</div>
@endsection
