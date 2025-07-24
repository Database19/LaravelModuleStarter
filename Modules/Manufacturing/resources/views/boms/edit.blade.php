@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 md:p-6">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Bill of Materials</h1>
        <form action="{{ route('manufacturing.boms.update', $bom) }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
            @csrf
            @method('PUT')
            @include('manufacturing::boms._form')
            <div class="mt-6 pt-6 border-t flex justify-end">
                <a href="{{ route('manufacturing.boms.show', $bom) }}" class="px-6 py-2 text-sm font-medium text-gray-700 rounded-md mr-2">Batal</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700">Update BOM</button>
            </div>
        </form>
    </div>
</div>
@endsection
