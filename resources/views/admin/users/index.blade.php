@extends('layouts.app')
@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold text-gray-700">Manajemen Pengguna</h1>
    <a href="{{ route('admin.users.create') }}"
       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow">
        + Buat Pengguna Baru
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-x-auto">
    <table id="users-table" class="w-full text-sm text-left text-gray-700">
        <thead class="bg-gray-100 text-xs uppercase text-gray-600 border-b">
            <tr>
                <th class="px-4 py-3">Nama</th>
                <th class="px-4 py-3">Email</th>
                <th class="px-4 py-3">Peran</th>
                <th class="px-4 py-3">Aksi</th>
            </tr>
        </thead>
    </table>
</div>
@endsection
@push('scripts')
    <script type="module">
        $(function() {
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("admin.users.index") }}',
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'roles', name: 'roles', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                responsive: true
            });

            $(document).on('submit', '.delete-form', function(e) {
                e.preventDefault(); // Mencegah form dikirim secara langsung
                var form = this;

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Jika dikonfirmasi, kirim form
                    }
                });
            });
        });
    </script>
@endpush
