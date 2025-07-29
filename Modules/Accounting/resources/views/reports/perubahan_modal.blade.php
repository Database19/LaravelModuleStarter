@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow-md max-w-2xl mx-auto">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800">Laporan Perubahan Modal</h2>
            <p class="text-sm text-gray-500 mt-1">
                Periode: {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMM YYYY') }} s/d {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMM YYYY') }}
            </p>
        </div>

        <div class="p-6">
            @include('accounting::reports._filter', ['actionUrl' => route('accounting.reports.perubahan_modal')])
        </div>

        <div class="p-6">
            <div class="space-y-4">
                <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-gray-600">Modal Awal Periode</span>
                    <span class="font-semibold text-gray-800">{{ number_format($modalAwal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-gray-600">Laba (Rugi) Bersih Periode Ini</span>
                    <span class="font-semibold text-gray-800">{{ number_format($labaRugiPeriodeIni, 0, ',', '.') }}</span>
                </div>
                 <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-gray-600">Setoran Modal</span>
                    <span class="font-semibold text-green-600">(+) {{ number_format($setoranModal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-gray-600">Penarikan Modal (Prive)</span>
                    <span class="font-semibold text-red-600">(-) {{ number_format($prive, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-4 bg-gray-50 rounded-lg px-4 mt-4">
                    <span class="font-bold text-lg text-gray-800">Modal Akhir Periode</span>
                    <span class="font-bold text-lg text-blue-600">Rp {{ number_format($modalAkhir, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
