@extends('layouts.app')
@section('content')
<div class="container mx-auto p-6">
    <div class=" mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Gudang</h1>
        <form action="{{ route('warehouse.warehouses.update', $warehouse) }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
            @csrf
            @method('PUT')
            @include('warehouse::form')
        </form>
    </div>
</div>
@endsection
