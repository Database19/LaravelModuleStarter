<div class="grid grid-cols-1 gap-6">
    {{-- Nama --}}
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
        <input
            type="text"
            id="name"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            x-model="formData.name"
            placeholder="Contoh: Kilogram, Liter"
        >
    </div>

    {{-- Kode Singkat --}}
    <div>
        <label for="short_code" class="block text-sm font-medium text-gray-700">Kode Singkat</label>
        <input
            type="text"
            id="short_code"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            x-model="formData.short_code"
            placeholder="Contoh: KG, LTR"
        >
    </div>

    {{-- Checkbox Aktif --}}
    <div class="flex items-center space-x-2">
        <input
            id="is_active"
            type="checkbox"
            class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
            x-model="formData.is_active"
        >
        <label for="is_active" class="text-sm text-gray-700 select-none">Aktif</label>
    </div>
</div>
