@extends('layouts.app')
@section('content')
<div class="mb-4">
    <a href="{{ route('admin.roles.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Buat Peran Baru
    </a>
</div>
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Peran</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Hak Akses</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
            <tr>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $role->name }}</td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    @foreach($role->permissions->take(5) as $permission)
                        <span class="bg-green-200 text-green-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">{{ $permission->name }}</span>
                    @endforeach
                    @if($role->permissions->count() > 5)
                        <span class="text-gray-500 text-xs">+{{ $role->permissions->count() - 5 }} lainnya</span>
                    @endif
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <a href="{{ route('admin.roles.edit', $role->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                     <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('Apakah Anda yakin ingin menghapus peran ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-4">
        {{ $roles->links() }}
    </div>
</div>
@endsection
