<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nama Gudang*</label>
        <input type="text" name="name" id="name" value="{{ old('name', $warehouse->name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
    </div>
    <div>
        <label for="code" class="block text-sm font-medium text-gray-700">Kode Gudang*</label>
        <input type="text" name="code" id="code" value="{{ old('code', $warehouse->code ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
    </div>
    <div class="md:col-span-2">
        <label for="location" class="block text-sm font-medium text-gray-700">Alamat / Lokasi*</label>
        <textarea name="location" id="location" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('location', $warehouse->location ?? '') }}</textarea>
    </div>
    <div>
        <label for="manager_id" class="block text-sm font-medium text-gray-700">Manager</label>
        <select name="manager_id" id="manager_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="">Pilih Manager</option>
            @foreach($users as $user)
            <option value="{{ $user->id }}" {{ (old('manager_id', $warehouse->manager_id ?? '')) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="is_active" class="block text-sm font-medium text-gray-700">Status*</label>
        <select name="is_active" id="is_active" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            <option value="1" {{ (old('is_active', $warehouse->is_active ?? 1)) == 1 ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ (old('is_active', $warehouse->is_active ?? 1)) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
        </select>
    </div>
</div>
<div class="mt-6 flex justify-end">
    <a href="{{ route('warehouse.warehouses.index') }}" class="px-6 py-2 text-sm font-medium text-gray-700 rounded-md mr-2">Batal</a>
    <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700">Simpan</button>
</div>
