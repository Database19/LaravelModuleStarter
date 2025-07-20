@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 md:p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Riwayat Penggajian</h1>
        <a href="{{ route('humanresource.payrolls.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Buat Payroll Baru
        </a>
    </div>

    @forelse($payrolls as $period => $items)
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="p-4 flex justify-between items-center bg-gray-50 border-b">
                <h2 class="text-xl font-bold text-gray-800">Periode: {{ $period }}</h2>

                {{-- Tombol Proses hanya muncul jika ada slip gaji 'Pending' --}}
                @if($items->contains('status', 'Pending'))
                <form action="{{ route('humanresource.payrolls.process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="period" value="{{ $items->first()->pay_period_end_date->format('Y-m-d') }}">
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700">
                        Proses Gaji Periode Ini
                    </button>
                </form>
                @endif
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left text-xs font-semibold text-gray-600 uppercase">Karyawan</th>
                            <th class="p-3 text-right text-xs font-semibold text-gray-600 uppercase">Gaji Pokok</th>
                            <th class="p-3 text-right text-xs font-semibold text-gray-600 uppercase">Gaji Bersih</th>
                            <th class="p-3 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($items as $payroll)
                        <tr>
                            <td class="p-3 whitespace-nowrap font-medium text-gray-800">{{ $payroll->employee->user->name ?? 'Karyawan Dihapus' }}</td>
                            <td class="p-3 text-right text-gray-600">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
                            <td class="p-3 text-right font-semibold text-gray-800">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</td>
                            <td class="p-3 text-center">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $payroll->status == 'Paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $payroll->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-lg shadow-md p-6 text-center text-gray-500">
            <p>Belum ada data penggajian yang dibuat.</p>
        </div>
    @endforelse
</div>
@endsection
