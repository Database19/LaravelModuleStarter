@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    {{-- Card Wrapper --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        {{-- Card Header --}}
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800">Laporan Laba Rugi</h2>
            <p class="text-sm text-gray-500 mt-1">
                Periode: {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMM YYYY') }} s/d {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMM YYYY') }}
            </p>
        </div>

        {{-- Filter Section --}}
        <div class="p-6">
            @include('accounting::reports._filter', ['actionUrl' => route('reports.laba_rugi')])
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                {{-- Bagian Pendapatan --}}
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pendapatan</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah (Rp)</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @include('accounting::reports._report_hierarchy_row', ['items' => $pendapatanData, 'level' => 0])
                    <tr class="bg-gray-100">
                        <td class="px-4 py-3 font-bold text-gray-800">Total Pendapatan</td>
                        <td class="px-4 py-3 text-right font-bold text-gray-800">{{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                    </tr>
                </tbody>

                {{-- Bagian Beban --}}
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Beban Pokok & Operasional</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah (Rp)</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @include('accounting::reports._report_hierarchy_row', ['items' => $bebanData, 'level' => 0])
                    <tr class="bg-gray-100">
                        <td class="px-4 py-3 font-bold text-gray-800">Total Beban</td>
                        <td class="px-4 py-3 text-right font-bold text-gray-800">{{ number_format($totalBeban, 0, ',', '.') }}</td>
                    </tr>
                </tbody>

                {{-- Grand Total --}}
                <tfoot>
                    <tr class="{{ $labaRugi < 0 ? 'bg-red-600' : 'bg-blue-600' }} text-white">
                        <td class="px-4 py-4 text-lg font-bold">LABA (RUGI) BERSIH</td>
                        <td class="px-4 py-4 text-right text-lg font-bold">{{ number_format($labaRugi, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
