@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 md:p-6">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 md:flex">
            <div class="md:flex-shrink-0 text-center md:text-left">
                <img class="h-24 w-24 rounded-full mx-auto md:mx-0" src="https://ui-avatars.com/api/?name={{ urlencode($employee->user->name) }}&size=128&color=7F9CF5&background=EBF4FF" alt="Foto profil">
            </div>
            <div class="mt-4 md:mt-0 md:ml-6">
                <div class="uppercase tracking-wide text-sm text-indigo-600 font-bold">{{ $employee->job_title }}</div>
                <h1 class="block mt-1 text-3xl leading-tight font-bold text-black">{{ $employee->user->name }}</h1>
                <p class="mt-2 text-gray-600">{{ $employee->department->name ?? 'Belum ada departemen' }}</p>
            </div>
        </div>

        <div class="border-t border-gray-200 px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Detail Informasi</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 text-sm">
                <div class="text-gray-600"><strong class="font-medium text-gray-800">Email:</strong> {{ $employee->user->email }}</div>
                <div class="text-gray-600"><strong class="font-medium text-gray-800">Nomor Induk:</strong> {{ $employee->employee_id_number ?? '-' }}</div>
                <div class="text-gray-600"><strong class="font-medium text-gray-800">Tanggal Masuk:</strong> {{ $employee->hire_date->format('d F Y') }}</div>
                <div class="text-gray-600"><strong class="font-medium text-gray-800">Gaji Pokok:</strong> Rp {{ number_format($employee->basic_salary, 0, ',', '.') }}</div>
            </div>
        </div>

         <div class="p-6 bg-gray-50 text-right rounded-b-lg border-t">
            <a href="#" class="px-4 py-2 bg-gray-600 text-white font-semibold rounded-md hover:bg-gray-700 text-sm">Edit Data</a>
        </div>
    </div>
</div>
@endsection
