@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800">Laporan Mutasi Saldo Akun</h2>
            <p class="text-sm text-gray-500 mt-1">Lihat rincian transaksi untuk akun spesifik.</p>
        </div>
        <div class="p-6">
            {{-- Filter Form Khusus --}}
             <form method="GET" action="{{ route('accounting.reports.mutasi_saldo') }}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <label for="account_id" class="block text-sm font-medium text-gray-700">Pilih Akun</label>
                        <select name="account_id" id="account_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" required>
                            <option value="">-- Silakan Pilih --</option>
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}" {{ $selectedAccount && $selectedAccount->id == $account->id ? 'selected' : '' }}>
                                    ({{ $account->account_code }}) {{ $account->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                        <input type="date" name="end_date" id="end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ request('end_date', now()->endOfMonth()->format('Y-m-d')) }}">
                    </div>
                </div>
                <div class="mt-4">
                     <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Tampilkan Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($reportData)
        @include('accounting::reports._buku_besar_table', ['data' => $reportData])
    @else
    <div class="bg-white rounded-lg shadow-md p-6 text-center text-gray-500">
        <p>Silakan pilih akun dan periode untuk menampilkan laporan.</p>
    </div>
    @endif
</div>
@endsection

@section('scripts')
{{-- Jika menggunakan Select2 atau sejenisnya, inisialisasi di sini --}}
@endsection
