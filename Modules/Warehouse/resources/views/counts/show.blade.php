@extends('layouts.app')

@push('styles')
{{-- Menambahkan sedikit CSS kustom untuk badge yang lebih modern --}}
<style>
    .badge-light-success {
        color: #198754;
        background-color: rgba(25, 135, 84, 0.1);
        border: 1px solid rgba(25, 135, 84, 0.2);
    }
    .badge-light-danger {
        color: #dc3545;
        background-color: rgba(220, 53, 69, 0.1);
        border: 1px solid rgba(220, 53, 69, 0.2);
    }
    .badge-light-secondary {
        color: #6c757d;
        background-color: rgba(108, 117, 125, 0.1);
        border: 1px solid rgba(108, 117, 125, 0.2);
    }
    .table-responsive {
        overflow-x: auto;
    }
    .card-body .table thead th {
        background-color: #f8f9fc;
        border-bottom-width: 1px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-clipboard-check me-2 text-primary"></i>
            Detail Penghitungan Stok
        </h1>
        <a href="{{ route('warehouse.counts.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left fa-sm me-2"></i>Kembali
        </a>
    </div>

    <div class="card shadow-sm border-light mb-4">
        <div class="card-header py-3 bg-light border-bottom">
            <h6 class="m-0 font-weight-bold text-primary">
                Informasi Dokumen #{{ $count->id }}
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3 mb-md-0">
                    <h6 class="text-muted small text-uppercase">Tanggal Dibuat</h6>
                    <p class="font-weight-bold mb-0">{{ $count->created_at->format('d F Y, H:i') }}</p>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <h6 class="text-muted small text-uppercase">Status</h6>
                    <p class="mb-0">
                        @if($count->status == 'completed')
                            <span class="badge rounded-pill badge-success">
                                <i class="fas fa-check-circle me-1"></i>Selesai
                            </span>
                        @elseif($count->status == 'pending')
                            <span class="badge rounded-pill badge-warning text-dark">
                                <i class="fas fa-hourglass-half me-1"></i>Dalam Proses
                            </span>
                        @else
                            <span class="badge rounded-pill badge-secondary">{{ ucfirst($count->status) }}</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-4">
                    <h6 class="text-muted small text-uppercase">Catatan</h6>
                    <p class="mb-0 fst-italic">{{ $count->notes ?? 'Tidak ada catatan.' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-light mb-4">
        <div class="card-header py-3 bg-light border-bottom">
            <h6 class="m-0 font-weight-bold text-primary">Rincian Item Dihitung</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" style="width: 5%;">No.</th>
                            <th>Nama Produk</th>
                            <th class="text-center">Stok Sistem</th>
                            <th class="text-center">Stok Dihitung</th>
                            <th class="text-center">Selisih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($count->items as $item)
                            @php
                                $variance = ($item->counted_quantity ?? 0) - ($item->expected_quantity ?? 0);
                            @endphp
                            <tr>
                                <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="font-weight-bold text-dark">
                                        {{ $item->product->name ?? 'Produk tidak ditemukan' }}
                                    </div>
                                    <div class="small text-muted">SKU: {{ $item->product->sku ?? '-' }}</div>
                                </td>
                                <td class="text-center">{{ $item->expected_quantity ?? 0 }}</td>
                                <td class="text-center font-weight-bold">{{ $item->counted_quantity ?? 0 }}</td>
                                <td class="text-center font-weight-bold">
                                    @if ($variance == 0)
                                        <span class="badge rounded-pill badge-light-secondary px-3 py-2">
                                            <i class="fas fa-check me-1"></i> 0 (Sesuai)
                                        </span>
                                    @elseif ($variance > 0)
                                        <span class="badge rounded-pill badge-light-success px-3 py-2">
                                            <i class="fas fa-arrow-up me-1"></i> +{{ $variance }} (Lebih)
                                        </span>
                                    @else
                                        <span class="badge rounded-pill badge-light-danger px-3 py-2">
                                            <i class="fas fa-arrow-down me-1"></i> {{ $variance }} (Kurang)
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="fas fa-box-open fa-3x text-gray-400 mb-3"></i>
                                    <h5 class="text-muted">Tidak Ada Item</h5>
                                    <p>Belum ada rincian item untuk penghitungan stok ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
