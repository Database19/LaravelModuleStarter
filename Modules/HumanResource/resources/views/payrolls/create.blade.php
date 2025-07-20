@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 md:p-6">
    <div class="max-w-lg mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Buat Proses Gaji Baru</h1>

        <form action="{{ route('humanresource.payrolls.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
            @csrf

            <div class="mb-6">
                <label for="payroll_period" class="block text-sm font-medium text-gray-700">Nama Periode*</label>
                <input type="text" name="payroll_period" id="payroll_period" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Contoh: Gaji Juli 2025" value="{{ old('payroll_period') }}" required>
                <p class="text-xs text-gray-500 mt-1">Nama ini harus unik untuk setiap periode.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                 <div class="mb-6">
                    <label for="pay_period_end_date" class="block text-sm font-medium text-gray-700">Tanggal Akhir Periode*</label>
                    <input type="date" name="pay_period_end_date" id="pay_period_end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('pay_period_end_date', now()->endOfMonth()->format('Y-m-d')) }}" required>
                    <p class="text-xs text-gray-500 mt-1">Tanggal awal akan dihitung otomatis dari awal bulan.</p>
                </div>

                 <div class="mb-6">
                    <label for="payment_date" class="block text-sm font-medium text-gray-700">Tanggal Pembayaran Gaji*</label>
                    <input type="date" name="payment_date" id="payment_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('payment_date', now()->endOfMonth()->addDays(5)->format('Y-m-d')) }}" required>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('humanresource.payrolls.index') }}" class="px-6 py-2 text-sm font-medium text-gray-700 rounded-md mr-2">Batal</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700">
                    Generate Slip Gaji
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
