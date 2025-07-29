@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus-circle text-primary"></i> Tambah Budget Baru
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('accounting.budget.index') }}">Budget</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Baru</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <!-- Form Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-edit"></i> Form Budget Baru
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('accounting.budget.store') }}" method="POST" id="budgetForm">
                        @csrf

                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label required">
                                        <i class="fas fa-tag text-primary"></i> Nama Budget
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name') }}"
                                           placeholder="Masukkan nama budget" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description" class="form-label">
                                        <i class="fas fa-align-left text-primary"></i> Deskripsi
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description" rows="3"
                                              placeholder="Deskripsi budget (opsional)">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="amount" class="form-label required">
                                        <i class="fas fa-dollar-sign text-success"></i> Jumlah Budget
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" class="form-control @error('amount') is-invalid @enderror"
                                               id="amount" name="amount" value="{{ old('amount') }}"
                                               placeholder="0" min="0" step="0.01" required>
                                        @error('amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="start_date" class="form-label required">
                                                <i class="fas fa-calendar-alt text-info"></i> Tanggal Mulai
                                            </label>
                                            <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                                   id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="end_date" class="form-label required">
                                                <i class="fas fa-calendar-check text-info"></i> Tanggal Berakhir
                                            </label>
                                            <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                                   id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                            @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="account_id" class="form-label">
                                        <i class="fas fa-chart-of-accounts text-warning"></i> Akun Terkait
                                    </label>
                                    <select class="form-control @error('account_id') is-invalid @enderror"
                                            id="account_id" name="account_id">
                                        <option value="">Pilih Akun (Opsional)</option>
                                        @if(isset($accounts))
                                            @foreach($accounts as $account)
                                                <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                                                    {{ $account->account_code }} - {{ $account->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('account_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="status" class="form-label required">
                                        <i class="fas fa-toggle-on text-success"></i> Status
                                    </label>
                                    <select class="form-control @error('status') is-invalid @enderror"
                                            id="status" name="status" required>
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="form-group text-right">
                                    <button type="button" class="btn btn-secondary mr-2" onclick="window.history.back()">
                                        <i class="fas fa-times"></i> Batal
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Simpan Budget
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.required::after {
    content: " *";
    color: red;
}

.form-control:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
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
    // Initialize TomSelect for account selection if available
    if (typeof TomSelect !== 'undefined') {
        new TomSelect('#account_id', {
            placeholder: 'Pilih Akun (Opsional)',
            allowEmptyOption: true,
            create: false
        });
    }

    // Form validation
    $('#budgetForm').on('submit', function(e) {
        var startDate = new Date($('#start_date').val());
        var endDate = new Date($('#end_date').val());

        if (endDate <= startDate) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Validasi Error',
                text: 'Tanggal berakhir harus lebih besar dari tanggal mulai!',
                confirmButtonColor: '#4e73df'
            });
            return false;
        }
    });

    // Auto format currency input
    $('#amount').on('input', function() {
        let value = this.value.replace(/,/g, '');
        if (!isNaN(value) && value !== '') {
            this.value = parseFloat(value).toLocaleString('id-ID');
        }
    });
});
</script>
@endpush
@endsection
