@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 md:p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $lead->name }}</h1>
            <p class="text-lg text-gray-600">{{ $lead->company_name }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('crm.leads.edit', $lead) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm font-medium">Edit</a>
            @if($lead->status != 'qualified')
            <form action="{{ route('crm.leads.convert', $lead) }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm font-medium">Konversi ke Opportunity</button>
            </form>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h3 class="text-sm font-medium text-gray-500">Email</h3>
                <p class="mt-1 text-gray-900">{{ $lead->email ?? '-' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Telepon</h3>
                <p class="mt-1 text-gray-900">{{ $lead->phone ?? '-' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Status</h3>
                <p class="mt-1 text-gray-900">{{ ucfirst($lead->status) }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Sumber</h3>
                <p class="mt-1 text-gray-900">{{ $lead->source ?? '-' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Owner</h3>
                <p class="mt-1 text-gray-900">{{ $lead->owner->name ?? '-' }}</p>
            </div>
        </div>
        <div class="mt-6 border-t pt-6">
            <h3 class="text-sm font-medium text-gray-500">Catatan</h3>
            <p class="mt-1 text-gray-900 whitespace-pre-wrap">{{ $lead->notes ?? 'Tidak ada catatan.' }}</p>
        </div>
    </div>
</div>
@endsection
