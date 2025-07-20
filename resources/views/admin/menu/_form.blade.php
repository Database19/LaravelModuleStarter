<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-4 py-5 sm:p-6">
        <div class="grid grid-cols-6 gap-6">
            <div class="col-span-6 sm:col-span-3">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Menu</label>
                <input type="text" name="name" id="name" value="{{ old('name', $menu->name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="parent_id" class="block text-sm font-medium text-gray-700">Parent Menu (Opsional)</label>
                <select id="parent_id" name="parent_id" class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base">
                    <option value="">-- Tidak Ada Parent --</option>
                    @foreach($parentMenus as $parent)
                        <option value="{{ $parent->id }}" @selected(old('parent_id', $menu->parent_id ?? '') == $parent->id)>
                            {{ $parent->name }} ({{ $parent->group }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="route" class="block text-sm font-medium text-gray-700">Route (Kosongkan untuk Parent)</label>
                <input type="text" name="route" id="route" value="{{ old('route', $menu->route ?? '') }}" placeholder="cth: products.index" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="permission_name" class="block text-sm font-medium text-gray-700">Izin yang Dibutuhkan</label>
                <select id="permission_name" name="permission_name" class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base" required>
                    @foreach($permissions as $permission)
                        <option value="{{ $permission }}" @selected(old('permission_name', $menu->permission_name ?? '') == $permission)>{{ $permission }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="group" class="block text-sm font-medium text-gray-700">Grup Menu</label>
                <input type="text" name="group" id="group" value="{{ old('group', $menu->group ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="order" class="block text-sm font-medium text-gray-700">Urutan</label>
                <input type="number" name="order" id="order" value="{{ old('order', $menu->order ?? 0) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>
            <div class="col-span-6">
                <label for="icon_svg" class="block text-sm font-medium text-gray-700">Kode Ikon SVG</label>
                <textarea name="icon_svg" id="icon_svg" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm font-mono">{{ old('icon_svg', $menu->icon_svg ?? '') }}</textarea>
            </div>
        </div>
    </div>
    <div class="flex items-center justify-end gap-x-3 bg-gray-50 px-4 py-3 text-right sm:px-6">
        <a href="{{ route('admin.menu.index') }}" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Batal</a>
        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">Simpan</button>
    </div>
</div>
