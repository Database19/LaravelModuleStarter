<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-4 py-5 sm:p-6">
        <div class="grid grid-cols-6 gap-6">
            {{-- Data Karyawan --}}
            <div class="col-span-6 sm:col-span-3">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name', $employee->user->name ?? '') }}" class="mt-1 block w-full rounded-md..." required>
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="employee_id_number" class="block text-sm font-medium text-gray-700">Nomor Induk Karyawan (NIK)</label>
                <input type="text" name="employee_id_number" id="employee_id_number" value="{{ old('employee_id_number', $employee->employee_id_number ?? '') }}" class="mt-1 block w-full rounded-md..." required>
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="job_title" class="block text-sm font-medium text-gray-700">Jabatan</label>
                <input type="text" name="job_title" id="job_title" value="{{ old('job_title', $employee->job_title ?? '') }}" class="mt-1 block w-full rounded-md..." required>
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="join_date" class="block text-sm font-medium text-gray-700">Tanggal Bergabung</label>
                <input type="date" name="join_date" id="join_date" value="{{ old('join_date', $employee->join_date ?? '') }}" class="mt-1 block w-full rounded-md..." required>
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="phone_number" class="block text-sm font-medium text-gray-700">Telepon</label>
                <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $employee->phone_number ?? '') }}" class="mt-1 block w-full rounded-md...">
            </div>

            {{-- Data Akun Login --}}
            <div class="col-span-6"><hr class="my-4"></div>
            <div class="col-span-6 sm:col-span-3">
                <label for="email" class="block text-sm font-medium text-gray-700">Email (untuk login)</label>
                <input type="email" name="email" id="email" value="{{ old('email', $employee->user->email ?? '') }}" class="mt-1 block w-full rounded-md..." required>
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="role" class="block text-sm font-medium text-gray-700">Role / Peran</label>
                <select id="role" name="role" class="mt-1 block w-full rounded-md..." required>
                    <option value="">Pilih Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role }}" @selected(old('role', $employee->user->roles->first()->name ?? '') == $role)>
                            {{ $role }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md..." {{ isset($employee) ? '' : 'required' }}>
                @if(isset($employee))<p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password.</p>@endif
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-md...">
            </div>
        </div>
    </div>
    <div class="flex items-center justify-end gap-x-3 bg-gray-50 px-4 py-3 text-right sm:px-6">
        <a href="{{ route('master-data.employees.index') }}" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
            Batal
        </a>
        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
            Simpan
        </button>
    </div>
</div>
