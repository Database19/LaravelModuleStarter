<div class="space-y-4">
    <div>
        <label for="account_code" class="block text-sm font-medium text-gray-700">Kode Akun</label>
        <input type="text" name="account_code" x-model="formData.account_code" class="mt-1 block w-full p-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
    </div>
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nama Akun</label>
        <input type="text" name="name" x-model="formData.name" class="mt-1 block w-full p-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
    </div>
    <div>
        <label for="type" class="block text-sm font-medium text-gray-700">Tipe Akun</label>
        <select name="type" x-model="formData.type" class="mt-1 block w-full p-2 rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" required>
            <option value="">Pilih Tipe</option>
            <option value="asset">Asset</option>
            <option value="liability">Liability</option>
            <option value="equity">Equity</option>
            <option value="revenue">Revenue</option>
            <option value="expense">Expense</option>
        </select>
    </div>
    <div class="flex items-start">
        <div class="flex h-5 items-center">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" x-model="formData.is_active" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
        </div>
        <div class="ml-3 text-sm">
            <label class="font-medium text-gray-700">Aktif</label>
        </div>
    </div>
</div>
