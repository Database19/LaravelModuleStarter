<div class="space-y-8 divide-y divide-gray-200">
    {{-- BAGIAN 1: INFORMASI PROFIL --}}
    <div>
        <div>
            <h3 class="text-lg font-semibold leading-6 text-gray-900">Informasi Profil</h3>
            <p class="mt-1 text-sm text-gray-500">Perbarui nama dan alamat email Anda.</p>
        </div>
        <div class="mt-6 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-6">
            {{-- Nama --}}
            <div class="sm:col-span-1">
                <label for="profile_name" class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" name="name" id="profile_name" x-model="formData.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
            </div>

            {{-- Email --}}
            <div class="sm:col-span-1">
                <label for="profile_email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                <input type="email" name="email" id="profile_email" x-model="formData.email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
            </div>
        </div>
    </div>

    {{-- BAGIAN 2: UBAH PASSWORD --}}
    <div class="pt-8">
        <div>
            <h3 class="text-lg font-semibold leading-6 text-gray-900">Ubah Password</h3>
            <p class="mt-1 text-sm text-gray-500">Kosongkan jika Anda tidak ingin mengubah password.</p>
        </div>
        <div class="mt-6 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-6">
            {{-- Password Baru --}}
            <div class="sm:col-span-1">
                <label for="profile_password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                <input type="password" name="password" id="profile_password" value="" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            {{-- Konfirmasi Password --}}
            <div class="sm:col-span-1">
                <label for="profile_password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" id="profile_password_confirmation" value="" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
        </div>
    </div>
</div>
