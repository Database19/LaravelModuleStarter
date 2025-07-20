@if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="space-y-4">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
        <input type="text" name="name" id="name" value="{{ old('name', $user->name ?? '') }}" class="mt-1 p-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
    </div>
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}" class="mt-1 p-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
    </div>
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" name="password" id="password" class="mt-1 p-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" {{ isset($user) ? '' : 'required' }}>
        @if(isset($user))<small class="text-gray-500">Kosongkan jika tidak ingin mengubah password.</small>@endif
    </div>
    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 p-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Peran</label>
        <div class="mt-2 space-y-2">
            @foreach($roles as $role)
            <div class="flex items-center">
                <input id="role_{{ $role }}" name="roles[]" type="checkbox" value="{{ $role }}" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                {{ in_array($role, old('roles', $userRoles ?? [])) ? 'checked' : '' }}>
                <label for="role_{{ $role }}" class="ml-3 block text-sm text-gray-900">{{ $role }}</label>
            </div>
            @endforeach
        </div>
    </div>
</div>
<div class="mt-6">
    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Simpan</button>
    <a href="{{ route('admin.users.index') }}" class="ml-4 text-gray-600">Batal</a>
</div>
