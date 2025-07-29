@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-eye text-info"></i> Detail Budget
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('accounting.budget.index') }}">Budget</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $budget->name }}</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <!-- Left Column - Budget Details -->
        <div class="col-lg-8">
            <!-- Budget Information Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Informasi Budget
                    </h6>
                    <div class="btn-group" role="group">
                        @can('edit budgets')
                        <a href="{{ route('accounting.budget.edit', $budget->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        @endcan
                        @can('delete budgets')
                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteBudget({{ $budget->id }})">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="font-weight-bold">
                                        <i class="fas fa-tag text-primary"></i> Nama Budget:
                                    </td>
                                    <td>{{ $budget->name }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">
                                        <i class="fas fa-dollar-sign text-success"></i> Jumlah Budget:
                                    </td>
                                    <td>
                                        <span class="text-success font-weight-bold">
                                            Rp {{ number_format($budget->amount, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">
                                        <i class="fas fa-credit-card text-danger"></i> Terpakai:
                                    </td>
                                    <td>
                                        <span class="text-danger">
                                            Rp {{ number_format($budget->used_amount, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">
                                        <i class="fas fa-wallet text-info"></i> Sisa:
                                    </td>
                                    <td>
                                        <span class="text-info font-weight-bold">
                                            Rp {{ number_format($budget->amount - $budget->used_amount, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="font-weight-bold">
                                        <i class="fas fa-calendar-alt text-info"></i> Tanggal Mulai:
                                    </td>
                                    <td>{{ $budget->start_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">
                                        <i class="fas fa-calendar-check text-info"></i> Tanggal Berakhir:
                                    </td>
                                    <td>{{ $budget->end_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">
                                        <i class="fas fa-clock text-warning"></i> Durasi:
                                    </td>
                                    <td>{{ $budget->start_date->diffInDays($budget->end_date) }} hari</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">
                                        <i class="fas fa-toggle-on text-success"></i> Status:
                                    </td>
                                    <td>
                                        @php
                                            $statusClasses = [
                                                'draft' => 'secondary',
                                                'active' => 'success',
                                                'inactive' => 'warning',
                                                'expired' => 'danger'
                                            ];
                                        @endphp
                                        <span class="badge badge-{{ $statusClasses[$budget->status] ?? 'secondary' }}">
                                            {{ ucfirst($budget->status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($budget->description)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="font-weight-bold">
                                <i class="fas fa-align-left text-primary"></i> Deskripsi:
                            </h6>
                            <p class="text-muted">{{ $budget->description }}</p>
                        </div>
                    </div>
                    @endif

                    @if($budget->account)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="font-weight-bold">
                                <i class="fas fa-chart-of-accounts text-warning"></i> Akun Terkait:
                            </h6>
                            <span class="badge badge-outline-warning">
                                {{ $budget->account->account_code }} - {{ $budget->account->name }}
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Budget Transactions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list"></i> Transaksi Budget
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="transactionsTable" width="100%">
                            <thead class="thead-light">
                                <tr>
                                    <th width="15%">Tanggal</th>
                                    <th width="25%">Deskripsi</th>
                                    <th width="20%">Referensi</th>
                                    <th width="15%" class="text-right">Debit</th>
                                    <th width="15%" class="text-right">Kredit</th>
                                    <th width="10%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- This will be populated by DataTables -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Statistics -->
        <div class="col-lg-4">
            <!-- Budget Progress Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie"></i> Progress Budget
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $percentage = $budget->amount > 0 ? ($budget->used_amount / $budget->amount) * 100 : 0;
                        $progressClass = $percentage < 50 ? 'success' : ($percentage < 80 ? 'warning' : 'danger');
                    @endphp

                    <div class="text-center mb-3">
                        <h4 class="text-{{ $progressClass }}">{{ number_format($percentage, 1) }}%</h4>
                        <small class="text-muted">Budget Terpakai</small>
                    </div>

                    <div class="progress mb-3" style="height: 20px;">
                        <div class="progress-bar bg-{{ $progressClass }}" role="progressbar"
                             style="width: {{ $percentage }}%">
                        </div>
                    </div>

                    <div class="row text-center">
                        <div class="col-12 mb-2">
                            <small class="text-muted">Budget Total</small>
                            <div class="h6 mb-0 font-weight-bold text-primary">
                                Rp {{ number_format($budget->amount, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Terpakai</small>
                            <div class="h6 mb-0 font-weight-bold text-danger">
                                Rp {{ number_format($budget->used_amount, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Sisa</small>
                            <div class="h6 mb-0 font-weight-bold text-success">
                                Rp {{ number_format($budget->amount - $budget->used_amount, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar"></i> Statistik Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 text-center border-right">
                            <div class="h6 mb-0 font-weight-bold text-warning">
                                {{ $budget->start_date->diffInDays(now(), false) < 0 ? 'Belum Dimulai' : $budget->start_date->diffInDays(now()) }}
                            </div>
                            <small class="text-muted">Hari Berjalan</small>
                        </div>
                        <div class="col-6 text-center">
                            <div class="h6 mb-0 font-weight-bold text-info">
                                {{ now()->diffInDays($budget->end_date, false) < 0 ? 'Sudah Berakhir' : now()->diffInDays($budget->end_date) }}
                            </div>
                            <small class="text-muted">Hari Tersisa</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cogs"></i> Aksi Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @can('create transactions')
                        <button class="btn btn-success btn-block" onclick="addTransaction()">
                            <i class="fas fa-plus"></i> Tambah Transaksi
                        </button>
                        @endcan

                        <a href="{{ route('accounting.budget.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                        </a>

                        <button class="btn btn-info btn-block" onclick="generateReport()">
                            <i class="fas fa-file-pdf"></i> Cetak Laporan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus budget ini?</p>
                <p class="text-danger"><strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form method="POST" id="deleteForm" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.badge-outline-warning {
    color: #f6c23e;
    border: 1px solid #f6c23e;
    background-color: transparent;
}

.progress {
    border-radius: 10px;
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
    // Initialize DataTable for transactions
    $('#transactionsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('accounting.budget.transactions', $budget->id) }}",
            type: 'GET'
        },
        columns: [
            { data: 'date', name: 'date' },
            { data: 'description', name: 'description' },
            { data: 'reference', name: 'reference' },
            {
                data: 'debit',
                name: 'debit',
                className: 'text-right',
                render: function(data) {
                    return data ? 'Rp ' + new Intl.NumberFormat('id-ID').format(data) : '-';
                }
            },
            {
                data: 'credit',
                name: 'credit',
                className: 'text-right',
                render: function(data) {
                    return data ? 'Rp ' + new Intl.NumberFormat('id-ID').format(data) : '-';
                }
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                className: 'text-center'
            }
        ],
        order: [[0, 'desc']],
        pageLength: 10,
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
        }
    });
});

function deleteBudget(id) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: 'Apakah Anda yakin ingin menghapus budget ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#deleteForm').attr('action', '{{ route("accounting.budget.destroy", "") }}/' + id);
            $('#deleteForm').submit();
        }
    });
}

function addTransaction() {
    // This would open a modal or redirect to add transaction page
    Swal.fire({
        title: 'Tambah Transaksi',
        text: 'Fitur ini akan mengarahkan ke form tambah transaksi',
        icon: 'info',
        confirmButtonText: 'OK'
    });
}

function generateReport() {
    // This would generate a PDF report
    Swal.fire({
        title: 'Generate Laporan',
        text: 'Laporan PDF sedang diproses...',
        icon: 'info',
        timer: 2000,
        showConfirmButton: false
    });
}
</script>
@endpush
@endsection
