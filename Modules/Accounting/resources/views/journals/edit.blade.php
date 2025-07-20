@extends('layouts.app')

@section('content')
{{-- SESUDAH --}}
<div class="max-w-4xl mx-auto ..." x-data='journalForm(@json($journal->items->map->only(['account_id', 'debit', 'credit'])))'>
    <form action="{{ route('journals.update', $journal) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- File ini sekarang akan otomatis memuat JavaScript-nya sendiri --}}
        @include('accounting::journals._form', ['accounts' => $accounts, 'journal' => $journal])
    </form>
</div>
@endsection
