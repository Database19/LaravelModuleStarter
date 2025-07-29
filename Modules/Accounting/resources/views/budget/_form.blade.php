{{-- Budget Form Component for Modal --}}
<form @submit.prevent="submitForm()" x-data="budgetForm()">

    <div class="space-y-6">
        {{-- Form Fields --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Nama Budget --}}
        <div class="md:col-span-2">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                Nama Budget <span class="text-red-500">*</span>
            </label>
            <input type="text" id="name" name="name" x-model="formData.name"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Masukkan nama budget" required>
            <p class="mt-1 text-xs text-gray-500">Contoh: Budget Marketing Q1 2025</p>
        </div>

        {{-- Kategori Budget --}}
        <div>
            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">
                Kategori Budget <span class="text-red-500">*</span>
            </label>
            <select id="category" name="category" x-model="formData.category"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                <option value="">Pilih Kategori</option>
                <option value="operational">Operational</option>
                <option value="marketing">Marketing</option>
                <option value="hr">Human Resource</option>
                <option value="it">Information Technology</option>
                <option value="finance">Finance</option>
                <option value="other">Lainnya</option>
            </select>
        </div>

        {{-- Department --}}
        <div>
            <label for="department" class="block text-sm font-medium text-gray-700 mb-1">
                Department
            </label>
            <input type="text" id="department" name="department" x-model="formData.department"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Nama department">
        </div>

        {{-- Budget Amount --}}
        <div>
            <label for="total_amount" class="block text-sm font-medium text-gray-700 mb-1">
                Jumlah Budget <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                <input type="number" id="total_amount" name="total_amount" x-model="formData.total_amount"
                       class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="0" min="0" step="1000" required>
            </div>
            <p class="mt-1 text-xs text-gray-500" x-text="formatCurrency(formData.total_amount)"></p>
        </div>

        {{-- Period --}}
        <div>
            <label for="period" class="block text-sm font-medium text-gray-700 mb-1">
                Periode <span class="text-red-500">*</span>
            </label>
            <input type="text" id="period" name="period" x-model="formData.period"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Contoh: 2025-Q1" required>
        </div>

        {{-- Start Date --}}
        <div>
            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">
                Tanggal Mulai <span class="text-red-500">*</span>
            </label>
            <input type="date" id="start_date" name="start_date" x-model="formData.start_date"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
        </div>

        {{-- End Date --}}
        <div>
            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">
                Tanggal Selesai <span class="text-red-500">*</span>
            </label>
            <input type="date" id="end_date" name="end_date" x-model="formData.end_date"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
        </div>

        {{-- Description --}}
        <div class="md:col-span-2">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                Deskripsi Budget
            </label>
            <textarea id="description" name="description" x-model="formData.description" rows="3"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Deskripsi detail tentang budget ini..."></textarea>
        </div>

        {{-- Status (hanya untuk edit) --}}
        <div x-show="isEditMode">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                Status Budget
            </label>
            <select id="status" name="status" x-model="formData.status"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1 mt-2">
                Catatan Perubahan
            </label>
            <textarea id="notes" name="notes" x-model="formData.notes" rows="2"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Catatan tentang perubahan yang dilakukan..."></textarea>
        </div>

    </div>

    {{-- Form Actions --}}
    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
        <div class="text-sm text-gray-500">
            <span x-show="!isEditMode">Semua field dengan (*) wajib diisi</span>
            <span x-show="isEditMode">Mengubah budget: <span x-text="formData.name" class="font-medium"></span></span>
        </div>

        <div class="flex space-x-3">
            <button type="button" @click="cancelForm()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                <i class="fas fa-times mr-1"></i> Batal
            </button>

            <button type="submit" :disabled="isSubmitting"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                <span x-show="!isSubmitting">
                    <i class="fas fa-save mr-1"></i>
                    <span x-text="isEditMode ? 'Update Budget' : 'Simpan Budget'"></span>
                </span>
                <span x-show="isSubmitting" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Menyimpan...
                </span>
            </button>
        </div>
    </div>
</form>

    </div>
</form>

<script>
function budgetForm() {
    return {
        isEditMode: false,
        isSubmitting: false,
        formData: {
            name: '',
            category: '',
            department: '',
            total_amount: '',
            period: '',
            start_date: '',
            end_date: '',
            description: '',
            status: 'pending',
            notes: ''
        },

        // Initialize form (dipanggil dari parent)
        initForm(data = null, editMode = false) {
            this.isEditMode = editMode;

            if (editMode && data) {
                // Edit mode - populate with existing data
                this.formData = {
                    name: data.name || '',
                    category: data.category || '',
                    department: data.department || '',
                    total_amount: data.total_amount || '',
                    period: data.period || '',
                    start_date: data.start_date || '',
                    end_date: data.end_date || '',
                    description: data.description || '',
                    status: data.status || 'pending',
                    notes: ''
                };
            } else {
                // Create mode - reset form
                this.resetForm();
            }
        },

        resetForm() {
            this.formData = {
                name: '',
                category: '',
                department: '',
                total_amount: '',
                period: '',
                start_date: '',
                end_date: '',
                description: '',
                status: 'pending',
                notes: ''
            };
            this.isSubmitting = false;
        },

        formatCurrency(amount) {
            if (!amount) return '';
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(Number(amount));
        },

        cancelForm() {
            this.resetForm();
            this.$dispatch('close-modal');
        },

        // Submit form - komunikasi dengan parent component
        submitForm() {
            this.isSubmitting = true;
            this.$dispatch('submit-budget-form', {
                formData: this.formData,
                isEditMode: this.isEditMode
            });
        }
    }
}
</script>
