@if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="space-y-6">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nama Peran</label>
        <input type="text" name="name" id="name" value="{{ old('name', $role->name ?? '') }}" class="mt-1 p-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Hak Akses</label>
        <div class="mt-2 space-y-4">
            @foreach($permissions as $group => $permissionList)
            <fieldset class="border rounded-md p-4">
                <legend class="px-2 font-semibold text-gray-800 capitalize">{{ $group }}</legend>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-2">
                    @foreach($permissionList as $permission)
                    <div class="flex items-start">
                        <div class="flex h-5 items-center">
                            <input id="perm_{{ $permission->id }}" name="permissions[]" type="checkbox" value="{{ $permission->name }}" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                            {{ in_array($permission->name, old('permissions', $rolePermissions ?? [])) ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="perm_{{ $permission->id }}" class="font-medium text-gray-700">{{ $permission->name }}</label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </fieldset>
            @endforeach
        </div>
    </div>
</div>
<div class="mt-6">
    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Simpan</button>
    <a href="{{ route('admin.roles.index') }}" class="ml-4 text-gray-600">Batal</a>
</div>
