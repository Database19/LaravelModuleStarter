@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800">Neraca Saldo</h2>
            <p class="text-sm text-gray-500 mt-1">
                Periode: {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMM YYYY') }} s/d {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMM YYYY') }}
            </p>
        </div>

        <div class="p-6">
            @include('accounting::reports._filter', ['actionUrl' => route('reports.neraca_saldo')])
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kode Akun</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Akun</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Debit (Rp)</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Kredit (Rp)</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($reportData as $data)
                    <tr>
                        <td class="px-4 py-2 whitespace-nowrap text-gray-500">{{ $data['code'] }}</td>
                        <td class="px-4 py-2 text-gray-700">{{ $data['name'] }}</td>
                        <td class="px-4 py-2 text-right text-gray-700">{{ number_format($data['debit'], 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-right text-gray-700">{{ number_format($data['credit'], 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-500">Tidak ada data untuk ditampilkan.</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-100">
                    <tr class="font-bold text-gray-800">
                        <td colspan="2" class="px-4 py-3">Total</td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($totalDebit, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($totalCredit, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
