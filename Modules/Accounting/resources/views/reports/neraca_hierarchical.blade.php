@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800">Laporan Posisi Keuangan (Neraca)</h2>
        <p class="text-sm text-gray-500 mt-1">
            Per Tanggal: {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMMM YYYY') }}
        </p>
        <div class="mt-6">
             @include('accounting::reports._filter', ['actionUrl' => route('accounting.reports.neraca')])
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Kolom ASET --}}
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 bg-blue-500 text-white">
                <h3 class="text-lg font-bold">Aset</h3>
            </div>
            <table class="w-full text-sm">
                <tbody class="divide-y divide-gray-200">
                     @include('accounting::reports._report_hierarchy_row', ['items' => $asetData, 'level' => 0])
                </tbody>
            </table>
            <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                <span class="font-bold text-gray-800">Total Aset</span>
                <span class="font-bold text-gray-800">Rp {{ number_format($totalAset, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Kolom LIABILITAS & EKUITAS --}}
        <div class="space-y-6">
            {{-- Card Liabilitas --}}
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-4 bg-red-500 text-white">
                    <h3 class="text-lg font-bold">Liabilitas</h3>
                </div>
                <table class="w-full text-sm">
                     <tbody class="divide-y divide-gray-200">
                        @include('accounting::reports._report_hierarchy_row', ['items' => $liabilitasData, 'level' => 0])
                     </tbody>
                </table>
                <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                    <span class="font-semibold text-gray-700">Total Liabilitas</span>
                    <span class="font-semibold text-gray-700">Rp {{ number_format($totalLiabilitas, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Card Ekuitas --}}
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-4 bg-green-500 text-white">
                    <h3 class="text-lg font-bold">Ekuitas</h3>
                </div>
                <table class="w-full text-sm">
                     <tbody class="divide-y divide-gray-200">
                        @include('accounting::reports._report_hierarchy_row', ['items' => $ekuitasData, 'level' => 0])
                        <tr class="border-b border-gray-200">
                            <td class="py-2 px-4 pl-8 text-gray-600">Laba (Rugi) Tahun Berjalan</td>
                            <td class="py-2 px-4 text-right text-gray-700">{{ number_format($labaRugiTahunBerjalan, 0, ',', '.') }}</td>
                        </tr>
                     </tbody>
                </table>
                <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                    <span class="font-semibold text-gray-700">Total Ekuitas</span>
                    <span class="font-semibold text-gray-700">Rp {{ number_format($totalEkuitas, 0, ',', '.') }}</span>
                </div>
            </div>

             {{-- Total Liabilitas & Ekuitas --}}
            <div class="bg-gray-200 rounded-lg p-4 flex justify-between items-center">
                <span class="font-bold text-gray-800">Total Liabilitas & Ekuitas</span>
                <span class="font-bold text-gray-800">Rp {{ number_format($totalLiabilitas + $totalEkuitas, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
