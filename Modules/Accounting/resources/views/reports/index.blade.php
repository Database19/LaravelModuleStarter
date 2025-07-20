@extends('layouts.app') {{-- Sesuaikan dengan layout utama Anda --}}

@section('content')
<div class="container mx-auto p-4 md:p-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Pusat Laporan</h1>
        <p class="text-lg text-gray-500 mt-1">Pilih laporan keuangan yang ingin Anda lihat.</p>
    </div>

    {{-- Grid untuk menampilkan kartu laporan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($reports as $report)
            <a href="{{ $report['url'] }}"
               class="block bg-white rounded-lg shadow-md p-6 group hover:shadow-xl hover:-translate-y-1 transition-all duration-300">

                <div class="flex items-center">
                    {{-- Ikon --}}
                    <div class="bg-blue-100 text-blue-600 rounded-full p-3">
                        <i class="{{ $report['icon'] }} fa-lg"></i>
                    </div>

                    {{-- Judul Laporan --}}
                    <h3 class="ml-4 text-xl font-bold text-gray-800 group-hover:text-blue-600 transition-colors">
                        {{ $report['name'] }}
                    </h3>
                </div>

                {{-- Deskripsi Laporan --}}
                <p class="text-gray-600 mt-4">
                    {{ $report['description'] }}
                </p>
            </a>
        @endforeach
    </div>
</div>
@endsection

@section('styles')
{{-- Pastikan Anda sudah memuat Font Awesome di layout utama Anda --}}
{{-- Contoh: <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" /> --}}
@endsection
