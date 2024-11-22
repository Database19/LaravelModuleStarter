@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-10">
            <h1 class="fw-bold text-primary">Tambah Module</h1>
        </div>
        <div class="col-md-2 text-end">
            <a href="{{ route('configuration.module-setting.index') }}" class="btn btn-secondary w-100">Kembali</a>
        </div>
    </div>

    <!-- Form Tambah Module dan Submodule -->
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form action="{{ route('configuration.module-setting.store') }}" method="POST">
                @csrf
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="mb-0">Tambah Module</h4>
                    </div>
                    <div class="card-body">
                        <!-- Nama Module -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Masukkan nama module" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" id="description" placeholder="Masukkan deskripsi module" value="{{ old('description') }}" required>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Route -->
                        {{-- <div class="mb-3">
                            <label for="route" class="form-label">Route</label>
                            <input type="text" name="route" class="form-control @error('route') is-invalid @enderror" id="route" placeholder="Masukkan route module" value="{{ old('route') }}" required>
                            @error('route')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> --}}

                        <!-- Permissions -->
                        <div class="mb-3">
                            <label for="permissions" class="form-label">Permissions</label>
                            <select 
                                name="permissions[]" 
                                id="permissions" 
                                class="form-control @error('permissions') is-invalid @enderror" 
                                multiple required>
                                @foreach ($permission as $permission)
                                    <option 
                                        value="{{ $permission->name }}" 
                                        {{ in_array($permission->name, old('permissions', [])) ? 'selected' : '' }}
                                    >
                                        {{ $permission->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('permissions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submodule Table -->
                        <div class="mb-3">
                            <label class="form-label">Submodule (Opsional)</label>
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Submodule</th>
                                        <th>Deskripsi</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="submodules-table">
                                    <tr>
                                        <td>
                                            <input type="text" name="submodules[0][name]" class="form-control form-control-sm" placeholder="Nama Submodule" required>
                                        </td>
                                        <td>
                                            <input type="text" name="submodules[0][description]" class="form-control form-control-sm" placeholder="Deskripsi Submodule" required>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm remove-submodule" style="display: none;">-</button>
                                            <button type="button" id="add-submodule" class="btn btn-secondary btn-sm">+</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let submoduleCount = 1;

    // Menambahkan Submodule Baru
    document.getElementById('add-submodule').addEventListener('click', function () {
        let tableRow = document.createElement('tr');
        tableRow.innerHTML = `
            <td>
                <input type="text" name="submodules[${submoduleCount}][name]" class="form-control form-control-sm" placeholder="Nama Submodule" required>
            </td>
            <td>
                <input type="text" name="submodules[${submoduleCount}][description]" class="form-control form-control-sm" placeholder="Deskripsi Submodule" required>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm remove-submodule">-</button>
            </td>
        `;
        document.getElementById('submodules-table').appendChild(tableRow);
        submoduleCount++;
    });

    // Menghapus Submodule
    document.getElementById('submodules-table').addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-submodule')) {
            e.target.closest('tr').remove();
        }
    });
</script>
@endsection
