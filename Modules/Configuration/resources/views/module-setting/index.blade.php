@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row align-items-center mb-4">
        <div class="col-md-10">
            <h1 class="fw-bold text-primary">Daftar Module</h1>
        </div>
        <div class="col-md-2 text-end">
            <a href="{{ route('configuration.module-setting.create') }}" class="btn btn-success w-100">+ Tambah Module</a>
        </div>
    </div>

    <!-- Form Pencarian -->
    <div class="row mb-3">
        <div class="col-md-12">
            <form action="{{ route('configuration.module-setting.index') }}" method="GET" class="d-flex flex-wrap gap-2">
                <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan nama" value="{{ $keyword ?? '' }}">
                <button type="submit" class="btn btn-primary">Cari</button>
            </form>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No</th>
                            <th style="width: 505px;">Nama</th>
                            <th>Route</th>
                            <th>Permission</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $item)
                            <tr>
                                <!-- Tambahkan ikon atau tombol yang memicu collapse -->
                                <td class="text-center">
                                    {{ $loop->iteration }}
                                </td>
                                <td>
                                    <div class="d-flex justify-content-between align-items-center">
                                        {{ $item->name }}
                                        <button 
                                            class="btn btn-sm btn-info text-decoration-none" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#submenu-{{ $item->id }}" 
                                            aria-expanded="false"
                                            aria-controls="submenu-{{ $item->id }}">
                                            Show
                                        </button>
                                    </div>
                                    <div class="collapse mt-1" id="submenu-{{ $item->id }}">
                                        @if ($item->submenus->isNotEmpty())
                                            <ul class="list-group">
                                                @foreach ($item->submenus as $submenu)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        {{ '- '.$submenu->name }}
                                                        <span class="text-muted small"><b>Route</b>: {{ $submenu->route }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-muted small mb-0">Tidak ada submenu terkait.</p>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $item->route }}</td>
                                <td>{{ $item->permission }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $item->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('configuration.module-setting.edit', $item->id) }}" 
                                           class="btn btn-warning btn-sm">Edit</a>
                                        {{-- <form action="#" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                        </form> --}}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Tidak ada data ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>                    
                </table>
            </div>
        </div>
    </div>

    <!-- Paginasi -->
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-center">
                {{ $data->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
