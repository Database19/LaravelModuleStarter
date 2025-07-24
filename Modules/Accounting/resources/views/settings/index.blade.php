@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 md:p-6">
    <div class="max-w-4xl mx-auto">
        {{-- Header Halaman --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Pengaturan Akuntansi</h1>
            <p class="text-lg text-gray-500 mt-1">
                Petakan akun default untuk otomatisasi jurnal di seluruh sistem.
            </p>
        </div>

        <form action="{{ route('accounting.settings.store') }}" method="POST">
            @csrf
            <div class="space-y-8">
                {{-- Loop melalui setiap grup pengaturan --}}
                @foreach($settingKeys as $groupName => $groupSettings)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-xl font-semibold text-gray-800">{{ $groupName }}</h3>
                        </div>
                        <div class="p-6 space-y-6">
                            {{-- Loop melalui setiap item di dalam grup --}}
                            @foreach($groupSettings as $key => $label)
                                <div>
                                    <label for="{{ $key }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
                                    <select name="settings[{{ $key }}]" id="{{ $key }}" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                        <option value="">-- Tidak Diatur --</option>
                                        @foreach($accounts as $account)
                                            {{-- Tampilkan hanya akun yang relevan jika memungkinkan, atau semua akun --}}
                                            <option value="{{ $account->id }}" {{ ($settings[$key] ?? null) == $account->id ? 'selected' : '' }}>
                                                ({{ $account->account_code }}) {{ $account->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Tombol Simpan --}}
            <div class="mt-8 flex justify-end">
                <button type="submit" class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
