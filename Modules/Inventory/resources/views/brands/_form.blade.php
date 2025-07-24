<div class="space-y-4">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nama Merek</label>
        <input type="text" name="name" x-model="formData.name" class="mt-1 block w-full rounded-md..." required>
    </div>

    <div>
        <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
        <input type="file" name="logo" id="logo" class="mt-1 block w-full text-sm text-gray-500
            file:mr-4 file:py-2 file:px-4
            file:rounded-full file:border-0
            file:text-sm file:font-semibold
            file:bg-indigo-50 file:text-indigo-700
            hover:file:bg-indigo-100"
        >
        {{-- Tampilkan logo yang ada saat edit --}}
        <div x-show="formMethod === 'PUT' && formData.logo_url" class="mt-2">
            <img :src="`/storage/${formData.logo_url}`" alt="Current Logo" class="h-16 w-16 object-cover rounded-md">
            <p class="text-xs text-gray-500 mt-1">Logo saat ini. Unggah file baru untuk mengganti.</p>
        </div>
    </div>

    <div class="flex items-start">
        <div class="flex h-5 items-center">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" x-model="formData.is_active" class="h-4 w-4 rounded...">
        </div>
        <div class="ml-3 text-sm">
            <label class="font-medium text-gray-700">Aktif</label>
        </div>
    </div>
</div>
