@extends('layouts.app')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- Header -->
    <div class="bg-white shadow rounded-2xl p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center">
        <div>
            <h1 class="text-2xl font-semibold text-indigo-700 flex items-center gap-2">
                <i class="fas fa-cogs text-indigo-500"></i>
                Pengaturan Akuntansi
            </h1>
            <p class="text-gray-500 mt-1 text-sm">Pilih bidang usaha untuk menampilkan Chart of Accounts yang sesuai</p>
        </div>
    </div>

    <!-- Bidang Usaha Filter -->
    <div class="bg-white shadow rounded-2xl p-6">
        <h2 class="text-lg font-semibold text-indigo-700 flex items-center gap-2 mb-4">
            <i class="fas fa-building"></i>
            Pemilihan Bidang Usaha
        </h2>
        <form id="businessTypeForm" action="{{ route('accounting.settings.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="business_type" class="block text-sm font-medium text-gray-700">
                    Pilih Bidang Usaha <span class="text-red-500">*</span>
                </label>
                <select name="business_type" id="business_type" onchange="this.form.submit()"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    @foreach($businessTypes as $key => $name)
                        <option value="{{ $key }}" {{ $currentBusinessType === $key ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">Filter akun berdasarkan bidang usaha yang relevan</p>
            </div>
            <div class="flex items-end gap-3">
                <button type="submit"
                    class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                    <i class="fas fa-filter mr-1"></i>Terapkan Filter
                </button>
                <button type="button" id="resetFilter"
                    class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-100 transition">
                    <i class="fas fa-sync-alt mr-1"></i>Reset
                </button>
            </div>
        </form>
    </div>

    <!-- Chart of Accounts -->
    <div class="bg-white shadow rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-green-700 flex items-center gap-2">
                <i class="fas fa-list-alt"></i> Chart of Accounts
            </h2>
            <span class="text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                {{ $businessTypes[$currentBusinessType] ?? 'Semua Bidang Usaha' }}
            </span>
        </div>

        @if($accounts->isEmpty())
            <div class="text-center py-10">
                <i class="fas fa-exclamation-circle text-yellow-400 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium">Belum Ada Akun</h3>
                <p class="text-gray-500">Silakan pilih bidang usaha terlebih dahulu</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($accounts as $type => $typeAccounts)
                    <div class="bg-gray-50 border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                        <div class="bg-gray-100 px-4 py-3 font-semibold text-sm uppercase flex items-center gap-2">
                            @switch($type)
                                @case('asset')
                                    <i class="fas fa-coins text-indigo-600"></i> ASET
                                    @break
                                @case('liability')
                                    <i class="fas fa-file-invoice text-yellow-500"></i> LIABILITAS
                                    @break
                                @case('equity')
                                    <i class="fas fa-chart-pie text-cyan-500"></i> EKUITAS
                                    @break
                                @case('revenue')
                                    <i class="fas fa-arrow-up text-green-600"></i> PENDAPATAN
                                    @break
                                @case('expense')
                                    <i class="fas fa-arrow-down text-red-500"></i> BEBAN
                                    @break
                            @endswitch
                        </div>
                        <ul class="divide-y divide-gray-200 max-h-[300px] overflow-y-auto">
                            @foreach($typeAccounts as $account)
                                <li class="px-4 py-3 hover:bg-white transition">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-sm font-medium text-indigo-700">{{ $account->account_code }}</p>
                                            <p class="text-xs text-gray-600">{{ $account->name }}</p>
                                            @if($account->business_type !== 'all')
                                                <span class="text-[10px] text-gray-400 mt-1 inline-flex items-center gap-1">
                                                    <i class="fas fa-tag"></i> {{ $account->business_type }}
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-xs font-semibold px-2 py-1 rounded-full capitalize
                                            @switch($type)
                                                @case('asset') bg-indigo-100 text-indigo-700 @break
                                                @case('liability') bg-yellow-100 text-yellow-800 @break
                                                @case('equity') bg-cyan-100 text-cyan-700 @break
                                                @case('revenue') bg-green-100 text-green-700 @break
                                                @case('expense') bg-red-100 text-red-700 @break
                                            @endswitch">
                                            {{ $type }}
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('businessTypeForm');
    const resetBtn = document.getElementById('resetFilter');

    // Handle form submit
    form.addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent default submit
        const selectedBusiness = document.getElementById('business_type').value;
        if (selectedBusiness) {
            // Redirect with query string (or post via AJAX)
            window.location.href = `?business_type=${encodeURIComponent(selectedBusiness)}`;
        }
    });

    // Handle reset filter
    resetBtn.addEventListener('click', function () {
        document.getElementById('business_type').selectedIndex = 0;
        window.location.href = window.location.pathname; // reload without query
    });
});
</script>
@endpush
