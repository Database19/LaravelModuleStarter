@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-10">
            <h1 class="fw-bold text-primary">Edit Module</h1>
        </div>
        <div class="col-md-2 text-end">
            <a href="{{ route('configuration.module-setting.index') }}" class="btn btn-secondary w-100">Kembali</a>
        </div>
    </div>

    <form action="{{ route('configuration.module-setting.update', $menu->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Edit Module</h4>
            </div>
            <div class="card-body">
                <!-- Nama Module -->
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           id="name" placeholder="Masukkan nama module" value="{{ old('name', $menu->name) }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" name="description" class="form-control @error('description') is-invalid @enderror"
                           id="description" placeholder="Masukkan deskripsi module" value="{{ old('description', $menu->description) }}">
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submodules -->
                <div class="mb-3">
                    <label class="form-label">Submodules</label>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Deskripsi</th>
                                <th class="text-center">Aktif ?</th>
                                <th class="text-center"><button type="button" id="add-submodule" class="btn btn-secondary btn-sm">Tambah</button></th>
                            </tr>
                        </thead>
                        <tbody id="submodules-table">
                            @foreach ($menu->submenus as $index => $submenu)
                                <tr>
                                    <td>
                                        <input type="text" name="submodules[{{ $index }}][name]"
                                               class="form-control" value="{{ old("submodules.$index.name", $submenu->name) }}">
                                    </td>
                                    <td>
                                        <input type="text" name="submodules[{{ $index }}][description]"
                                               class="form-control" value="{{ old("submodules.$index.description", $submenu->description) }}">
                                    </td>
                                    <td class="d-flex justify-content-center align-items-center">
                                        <div class="form-check form-switch">
                                            <input 
                                                type="checkbox" 
                                                name="submodules[{{ $index }}][is_active]" 
                                                class="form-check-input" 
                                                value="1" 
                                                {{ old("submodules.$index.is_active", $submenu->is_active) ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm remove-submodule">Hapus</button>
                                    </td>
                                </tr>
                            @endforeach
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

<script>
    let submoduleCount = {{ $menu->submenus->count() }};

    // Tambah Submodule
    document.getElementById('add-submodule').addEventListener('click', function () {
        const table = document.getElementById('submodules-table');
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>
                <input type="text" name="submodules[${submoduleCount}][name]" class="form-control">
            </td>
            <td>
                <input type="text" name="submodules[${submoduleCount}][description]" class="form-control">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm remove-submodule">Hapus</button>
            </td>
        `;
        table.appendChild(newRow);
        submoduleCount++;
    });

    // Hapus Submodule
    document.getElementById('submodules-table').addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-submodule')) {
            e.target.closest('tr').remove();
        }
    });
</script>
@endsection
