@extends('layouts.app')

@section('header')
    Module: {!! config('inventory.name', 'Inventory') !!}
@endsection

@section('content')
    @if (session('status'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
            <p>{{ session('status') }}</p>
        </div>
    @endif

    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Welcome, {{ Auth::user()->name ?? 'Guest' }}!</h2>
    <p class="text-gray-600">
        Ini adalah area konten utama untuk modul. Anda dapat menambahkan tabel, formulir, dan komponen lainnya di sini, dan mereka akan ditampilkan di dalam wadah putih.
    </p>
@endsection
