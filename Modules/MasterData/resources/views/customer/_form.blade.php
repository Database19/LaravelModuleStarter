<div class="bg-gray shadow-md rounded-lg overflow-hidden">
    <div class="px-4 py-5 sm:p-6">
        <div class="grid grid-cols-6 gap-6">

            {{-- Nama Customer --}}
            <div class="col-span-6 sm:col-span-3">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Customer <span class="bg-red-300">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $customer->name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>

            {{-- Email --}}
            <div class="col-span-6 sm:col-span-3">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $customer->email ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

            {{-- Telepon --}}
            <div class="col-span-6 sm:col-span-3">
                <label for="phone" class="block text-sm font-medium text-gray-700">Telepon</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $customer->phone ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

            {{-- Nama Perusahaan --}}
            <div class="col-span-6 sm:col-span-3">
                <label for="company_name" class="block text-sm font-medium text-gray-700">Nama Perusahaan</label>
                <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $customer->company_name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

            {{-- Alamat --}}
            <div class="col-span-6">
                <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                <textarea name="address" id="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('address', $customer->address ?? '') }}</textarea>
            </div>

            {{-- Tipe Customer --}}
            <div class="col-span-6 sm:col-span-2">
                <label for="type" class="block text-sm font-medium text-gray-700">Tipe *</label>
                <select id="type" name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    <option value="individual" {{ old('type', $customer->type ?? 'individual') == 'individual' ? 'selected' : '' }}>Individual</option>
                    <option value="company" {{ old('type', $customer->type ?? '') == 'company' ? 'selected' : '' }}>Company</option>
                </select>
            </div>

            {{-- NPWP --}}
            <div class="col-span-6 sm:col-span-2">
                <label for="tax_id" class="block text-sm font-medium text-gray-700">NPWP (Tax ID)</label>
                <input type="text" name="tax_id" id="tax_id" value="{{ old('tax_id', $customer->tax_id ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

            {{-- Status Aktif --}}
            <div class="col-span-6 sm:col-span-2">
                 <label for="is_active" class="block text-sm font-medium text-gray-700">Status*</label>
                <select id="is_active" name="is_active" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    <option value="1" {{ old('is_active', $customer->is_active ?? 1) == 1 ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_active', $customer->is_active ?? 1) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
        </div>
    </div>
    <div class="flex items-center justify-end gap-x-3 bg-gray-50 px-4 py-3 text-right sm:px-6">
        <a href="{{ route('master-data.customer.index') }}" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
            Batal
        </a>
        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
            Simpan
        </button>
    </div>
</div>
