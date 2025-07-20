@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    {{-- Judul --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Laporan Posisi Keuangan (Neraca)</h1>
        <p class="text-sm text-gray-600">Per Tanggal: {{ $endDate }}</p>
    </div>

    {{-- Konten 2 Kolom: Aset | Liabilitas & Ekuitas --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        {{-- Aset --}}
        <div>
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Aset</h2>
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <tbody class="divide-y divide-gray-100">
                        @foreach($aset as $akun)
                        <tr>
                            <td class="px-4 py-2 text-gray-800">{{ $akun->name }}</td>
                            <td class="px-4 py-2 text-right text-gray-800">Rp {{ number_format($akun->balance, 2) }}</td>
                        </tr>
                        @endforeach
                        <tr class="bg-gray-50 font-semibold text-gray-900">
                            <td class="px-4 py-2">Total Aset</td>
                            <td class="px-4 py-2 text-right">Rp {{ number_format($aset->sum('balance'), 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Liabilitas dan Ekuitas --}}
        <div>
            {{-- Liabilitas --}}
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Liabilitas</h2>
            <div class="overflow-x-auto bg-white rounded-lg shadow mb-6">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <tbody class="divide-y divide-gray-100">
                        @foreach($liabilitas as $akun)
                        <tr>
                            <td class="px-4 py-2 text-gray-800">{{ $akun->name }}</td>
                            <td class="px-4 py-2 text-right text-gray-800">Rp {{ number_format($akun->balance, 2) }}</td>
                        </tr>
                        @endforeach
                        <tr class="bg-gray-50 font-semibold text-gray-900">
                            <td class="px-4 py-2">Total Liabilitas</td>
                            <td class="px-4 py-2 text-right">Rp {{ number_format($liabilitas->sum('balance'), 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Ekuitas --}}
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Ekuitas</h2>
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <tbody class="divide-y divide-gray-100">
                        @foreach($ekuitas as $akun)
                        <tr>
                            <td class="px-4 py-2 text-gray-800">{{ $akun->name }}</td>
                            <td class="px-4 py-2 text-right text-gray-800">Rp {{ number_format($akun->balance, 2) }}</td>
                        </tr>
                        @endforeach
                        <tr class="bg-gray-50 font-semibold text-gray-900">
                            <td class="px-4 py-2">Total Ekuitas</td>
                            <td class="px-4 py-2 text-right">Rp {{ number_format($ekuitas->sum('balance'), 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Total Liabilitas + Ekuitas --}}
            <div class="border-t border-gray-200 pt-4 mt-6">
                <h3 class="text-base font-bold text-gray-800 flex justify-between">
                    <span>Total Liabilitas & Ekuitas</span>
                    <span class="text-blue-600">Rp {{ number_format($liabilitas->sum('balance') + $ekuitas->sum('balance'), 2) }}</span>
                </h3>
            </div>
        </div>
    </div>
</div>
@endsection
