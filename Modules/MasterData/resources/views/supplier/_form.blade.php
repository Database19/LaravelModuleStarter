<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-4 py-5 sm:p-6">
        <div class="grid grid-cols-6 gap-6">

            {{-- Nama Supplier --}}
            <div class="col-span-6 sm:col-span-3">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Supplier*</label>
                <input type="text" name="name" id="name" value="{{ old('name', $supplier->name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>

            {{-- Contact Person --}}
            <div class="col-span-6 sm:col-span-3">
                <label for="contact_person" class="block text-sm font-medium text-gray-700">Contact Person</label>
                <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person', $supplier->contact_person ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

            {{-- Email --}}
            <div class="col-span-6 sm:col-span-3">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $supplier->email ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

            {{-- Telepon --}}
            <div class="col-span-6 sm:col-span-3">
                <label for="phone" class="block text-sm font-medium text-gray-700">Telepon</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $supplier->phone ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

            {{-- Alamat --}}
            <div class="col-span-6">
                <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                <textarea name="address" id="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('address', $supplier->address ?? '') }}</textarea>
            </div>

            {{-- NPWP --}}
            <div class="col-span-6 sm:col-span-3">
                <label for="tax_id" class="block text-sm font-medium text-gray-700">NPWP (Tax ID)</label>
                <input type="text" name="tax_id" id="tax_id" value="{{ old('tax_id', $supplier->tax_id ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

            {{-- Status Aktif --}}
            <div class="col-span-6 sm:col-span-3">
                 <label for="is_active" class="block text-sm font-medium text-gray-700">Status*</label>
                <select id="is_active" name="is_active" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    <option value="1" {{ old('is_active', $supplier->is_active ?? 1) == 1 ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_active', $supplier->is_active ?? 1) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>

            {{-- Informasi Bank --}}
            <div class="col-span-6 sm:col-span-3">
                <label for="bank_name" class="block text-sm font-medium text-gray-700">Nama Bank</label>
                <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', $supplier->bank_name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

            <div class="col-span-6 sm:col-span-3">
                <label for="bank_account" class="block text-sm font-medium text-gray-700">Nomor Rekening</label>
                <input type="text" name="bank_account" id="bank_account" value="{{ old('bank_account', $supplier->bank_account ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
        </div>
    </div>
    <div class="flex items-center justify-end gap-x-3 bg-gray-50 px-4 py-3 text-right sm:px-6">
        <a href="{{ route('master-data.supplier.index') }}" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
            Batal
        </a>
        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
            Simpan
        </button>
    </div>
</div>
