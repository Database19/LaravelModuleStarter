<form method="GET" action="{{ $actionUrl }}" class="mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        {{-- Tanggal Mulai --}}
        <div>
            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
            <input
                type="date"
                name="start_date"
                id="accounting_report_start_date"
                value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
            >
        </div>

        {{-- Tanggal Selesai --}}
        <div>
            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
            <input
                type="date"
                name="end_date"
                id="accounting_report_end_date"
                value="{{ request('end_date', now()->endOfMonth()->format('Y-m-d')) }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
            >
        </div>

        {{-- Tombol --}}
        <div>
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm transition duration-200">
                Tampilkan
            </button>
        </div>
    </div>
</form>

<hr class="my-6 border-gray-300">
