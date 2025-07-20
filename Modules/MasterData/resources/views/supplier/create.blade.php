@extends('layouts.app')

@section('content')
<div class="mx-auto py-10 sm:px-6 lg:px-8">
    <div class="px-4 sm:px-0 mb-4">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Tambah Supplier Baru</h3>
    </div>
    <form action="{{ route('master-data.supplier.store') }}" method="POST">
        @csrf
        @include('masterdata::supplier._form')
    </form>
</div>
@endsection
