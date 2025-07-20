@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8" x-data="journalForm()">
    <form action="{{ route('journals.store') }}" method="POST">
        @csrf
        {{-- Konten form sama seperti jawaban sebelumnya --}}
        @include('accounting::journals._form', ['accounts' => $accounts])
    </form>
</div>
@endsection

@push('js')
<script>
function journalForm(initialItems = []) {
    return {
        items: initialItems.length > 0 ? initialItems : [
            { account_id: '', debit: null, credit: null },
            { account_id: '', debit: null, credit: null },
        ],
        addItem() { this.items.push({ account_id: '', debit: null, credit: null }); },
        removeItem(index) { this.items.splice(index, 1); },
        get totalDebit() { return this.items.reduce((sum, item) => sum + (parseFloat(item.debit) || 0), 0); },
        get totalCredit() { return this.items.reduce((sum, item) => sum + (parseFloat(item.credit) || 0), 0); },
        isBalanced() { return this.totalDebit === this.totalCredit && this.totalDebit > 0; },
        formatCurrency(value) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value); }
    }
}
</script>
@endpush
