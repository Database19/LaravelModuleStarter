@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="mt-5 md:col-span-2 md:mt-0">
        <form action="{{ route('coas.update', $accounting->id) }}" method="POST">
            <div class="px-4 sm:px-0 mb-4">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Edit Akun</h3>
                <p class="mt-1 text-sm text-gray-600">Perbarui detail untuk akun: {{ $accounting->name }}.</p>
            </div>
            @csrf
            @method('PUT')
            @include('accounting::coas._form', ['account' => $accounting])
        </form>
    </div>
</div>
@endsection
