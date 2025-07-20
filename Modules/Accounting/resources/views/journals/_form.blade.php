<div class="px-4 sm:px-0 mb-4">
    <h3 class="text-lg font-medium leading-6 text-gray-900">{{ isset($journal) ? 'Edit Jurnal Umum #'.$journal->id : 'Buat Jurnal Umum' }}</h3>
    <p class="mt-1 text-sm text-gray-600">Pastikan total Debit dan Kredit seimbang.</p>
</div>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    {{-- Header Form --}}
    <div class="px-6 py-4 space-y-4">
        <div>
            <label for="date" class="block text-sm font-medium text-gray-700">Tanggal</label>
            <input type="date" id="date_picker" name="date" value="{{ old('date', $journal->date ?? date('Y-m-d')) }}" class="mt-1 block w-full sm:w-1/3 rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <input type="text" name="description" value="{{ old('description', $journal->description ?? '') }}" placeholder="cth: Biaya operasional bulan Juli" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
    </div>
    @if ($errors->has('items'))
        <div class="px-6 text-sm text-red-600">{{ $errors->first('items') }}</div>
    @endif
    {{-- Tabel Item Jurnal --}}
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Akun</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Debit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kredit</th>
                    <th class="w-10"></th>
                </tr>
            </thead>
            <tbody>
                <template x-for="(item, index) in items" :key="index">
                    <tr class="border-t border-gray-200">
                        <td class="px-6 py-2">
                            <select :name="`items[${index}][account_id]`" x-model="item.account_id" class="w-full rounded-md border-gray-300">
                                <option value="">Pilih Akun</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->account_code }} - {{ $account->name }}</option>
                                @endforeach
                            </select>
                        </td>
                       <td class="px-6 py-2">
                            <input
                                type="text"
                                inputmode="decimal"
                                x-mask:dynamic="$money($input, '.', ',')"
                                :name="`items[${index}][debit]`"
                                x-model.number="item.debit"
                                @input="item.credit = null"
                                class="w-full rounded-md border-gray-300 text-right"
                                placeholder="0,00">
                        </td>
                        <td class="px-6 py-2">
                            <input
                                type="text"
                                inputmode="decimal"
                                x-mask:dynamic="$money($input, '.', ',')"
                                :name="`items[${index}][credit]`"
                                x-model.number="item.credit"
                                @input="item.debit = null"
                                class="w-full rounded-md border-gray-300 text-right"
                                placeholder="0,00">
                        </td>
                        <td class="px-6 py-2">
                            <button type="button" @click="removeItem(index)" x-show="items.length > 2" class="text-red-500 hover:text-red-700">&times;</button>
                        </td>
                    </tr>
                </template>
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <td class="px-6 py-3 text-right font-bold">Total</td>
                    <td class="px-6 py-3"><span x-text="formatCurrency(totalDebit)" class="font-bold"></span></td>
                    <td class="px-6 py-3"><span x-text="formatCurrency(totalCredit)" class="font-bold"></span></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Tombol Aksi --}}
    <div class="px-6 py-4 flex items-center justify-between">
        <button type="button" @click="addItem()" class="rounded-md bg-green-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-400">Tambah Baris</button>
        <div>
            <span x-show="!isBalanced()" class="text-red-500 text-sm mr-4">Jurnal tidak seimbang!</span>
            <button type="submit" :disabled="!isBalanced()" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 disabled:opacity-50">Simpan Jurnal</button>
        </div>
    </div>
</div>
@push('scripts')
<script>
function journalForm(initialItems = []) {
    const sanitizedItems = initialItems.map(item => ({
        account_id: item.account_id || '',
        debit: item.debit || null,
        credit: item.credit || null,
    }));

    return {
        items: sanitizedItems.length > 0 ? sanitizedItems : [
            { account_id: '', debit: null, credit: null },
            { account_id: '', debit: null, credit: null },
        ],
        addItem() { this.items.push({ account_id: '', debit: null, credit: null }); },
        removeItem(index) {
            if (this.items.length > 2) {
                this.items.splice(index, 1);
            }
        },
        get totalDebit() { return this.items.reduce((sum, item) => sum + (parseFloat(item.debit) || 0), 0); },
        get totalCredit() { return this.items.reduce((sum, item) => sum + (parseFloat(item.credit) || 0), 0); },
        isBalanced() { return Math.round(this.totalDebit * 100) === Math.round(this.totalCredit * 100) && this.totalDebit > 0; },
        formatCurrency(value) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 2 }).format(value); }
    }
}
</script>
@endpush
