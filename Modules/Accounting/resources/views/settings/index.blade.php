@extends('layouts.app') {{-- Sesuaikan dengan layout utama Anda --}}

@section('content')
<div class="container mx-auto p-4 md:p-6">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800">Pengaturan Akuntansi</h2>
                <p class="text-sm text-gray-500 mt-1">
                    Petakan akun default untuk transaksi otomatis di seluruh sistem.
                </p>
            </div>

            <form action="{{ route('accounting.settings.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-6">
                    @foreach($settingKeys as $key => $label)
                        <div>
                            <label for="{{ $key }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
                            <select name="settings[{{ $key }}]" id="{{ $key }}" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="">-- Tidak Diatur --</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" {{ ($settings[$key] ?? null) == $account->id ? 'selected' : '' }}>
                                        ({{ $account->account_code }}) {{ $account->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>

                <div class="p-6 bg-gray-50 text-right rounded-b-lg">
                    <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
