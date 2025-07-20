@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Laporan Laba Rugi</h1>
        <p class="text-sm text-gray-600">Periode: {{ $startDate }} s/d {{ $endDate }}</p>
    </div>

    {{-- Filter --}}
    <div class="mb-6">
        @include('accounting::reports._filter', ['actionUrl' => route('reports.laba_rugi')])
    </div>

    {{-- Pendapatan --}}
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-gray-700 mb-2">Pendapatan</h2>
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <tbody class="divide-y divide-gray-100">
                    @foreach($pendapatan as $akun)
                    <tr>
                        <td class="px-4 py-2 text-gray-800">{{ $akun->name }}</td>
                        <td class="px-4 py-2 text-right text-gray-800">Rp {{ number_format($akun->journal_items_sum_credit, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="bg-gray-50 font-semibold text-gray-900">
                        <td class="px-4 py-2">Total Pendapatan</td>
                        <td class="px-4 py-2 text-right">Rp {{ number_format($totalPendapatan, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Beban --}}
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-gray-700 mb-2">Beban</h2>
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <tbody class="divide-y divide-gray-100">
                    @foreach($beban as $akun)
                    <tr>
                        <td class="px-4 py-2 text-gray-800">{{ $akun->name }}</td>
                        <td class="px-4 py-2 text-right text-gray-800">Rp {{ number_format($akun->journal_items_sum_debit, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="bg-gray-50 font-semibold text-gray-900">
                        <td class="px-4 py-2">Total Beban</td>
                        <td class="px-4 py-2 text-right">Rp {{ number_format($totalBeban, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Laba / Rugi Bersih --}}
    <div class="border-t border-gray-200 pt-6 mt-6">
        <h2 class="text-xl font-bold text-gray-800 flex justify-between">
            <span>Laba (Rugi) Bersih</span>
            <span class="text-blue-600">Rp {{ number_format($labaRugi, 2) }}</span>
        </h2>
    </div>
</div>
@endsection
