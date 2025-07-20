@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 md:p-6">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Tambah Karyawan Baru</h1>

        <form action="{{ route('humanresource.employees.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
            @csrf

            {{-- Informasi Akun Login --}}
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Informasi Akun Login</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap*</label>
                        <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('name') }}" required>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email*</label>
                        <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('email') }}" required>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password*</label>
                        <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                     <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password*</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                </div>
            </div>

            {{-- Informasi Pekerjaan --}}
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Informasi Pekerjaan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="job_title" class="block text-sm font-medium text-gray-700">Jabatan*</label>
                        <input type="text" name="job_title" id="job_title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('job_title') }}" required>
                    </div>
                    <div>
                        <label for="department_id" class="block text-sm font-medium text-gray-700">Departemen</label>
                        <select name="department_id" id="department_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">Pilih Departemen</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="hire_date" class="block text-sm font-medium text-gray-700">Tanggal Masuk*</label>
                        <input type="date" name="hire_date" id="hire_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('hire_date', now()->format('Y-m-d')) }}" required>
                    </div>
                    <div>
                        <label for="basic_salary" class="block text-sm font-medium text-gray-700">Gaji Pokok*</label>
                        <input type="number" name="basic_salary" id="basic_salary" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('basic_salary') }}" required min="0">
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('humanresource.employees.index') }}" class="px-6 py-2 text-sm font-medium text-gray-700 rounded-md mr-2">Batal</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700">Simpan Data Karyawan</button>
            </div>
        </form>
    </div>
</div>
@endsection
