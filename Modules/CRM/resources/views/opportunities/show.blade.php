@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 md:p-6">
    {{-- Header & Tombol Aksi --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $opportunity->name }}</h1>
            <p class="text-lg text-gray-600">{{ $opportunity->customer->name ?? 'N/A' }}</p>
        </div>
        <div class="flex items-center gap-2">
            @if(!in_array($opportunity->stage, ['won', 'lost']))
                <a href="{{ route('crm.opportunities.edit', $opportunity) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm font-medium">Edit</a>
                <form action="{{ route('crm.opportunities.markAsLost', $opportunity) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm font-medium">Mark as Lost</button>
                </form>
                <form action="{{ route('crm.opportunities.markAsWon', $opportunity) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm font-medium">Mark as Won</button>
                </form>
            @endif
        </div>
    </div>

    {{-- Detail Opportunity --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h3 class="text-sm font-medium text-gray-500">Perkiraan Nilai</h3>
                <p class="mt-1 text-lg font-semibold text-gray-900">Rp {{ number_format($opportunity->expected_value, 0, ',', '.') }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Tahapan</h3>
                <p class="mt-1 text-gray-900">{{ ucfirst($opportunity->stage) }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Perkiraan Closing</h3>
                <p class="mt-1 text-gray-900">{{ $opportunity->expected_closing_date ? $opportunity->expected_closing_date->format('d F Y') : '-' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Owner</h3>
                <p class="mt-1 text-gray-900">{{ $opportunity->owner->name ?? '-' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Berasal dari Lead</h3>
                <p class="mt-1 text-gray-900">{{ $opportunity->lead->name ?? 'Tidak ada' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
