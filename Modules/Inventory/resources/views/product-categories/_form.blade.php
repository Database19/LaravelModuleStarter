<div class="space-y-4">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
        <input type="text" name="name" x-model="formData.name" class="mt-1 block w-full rounded-md..." required>
    </div>
    <div>
        <label for="parent_id" class="block text-sm font-medium text-gray-700">Parent Kategori (Opsional)</label>
        <select name="parent_id" x-model="formData.parent_id" class="mt-1 block w-full rounded-md...">
            <option value="">-- Tidak Ada Parent --</option>
            @foreach($parentCategories as $parent)
                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
        <textarea name="description" x-model="formData.description" rows="3" class="mt-1 block w-full rounded-md..."></textarea>
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
