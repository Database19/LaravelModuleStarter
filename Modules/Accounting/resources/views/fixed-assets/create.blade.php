@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10">
    <div class="flex justify-center">
        <div class="w-full max-w-2xl">
            <div class="bg-white shadow-lg rounded-xl">
                <div class="p-8">
                    <h2 class="mb-6 text-center font-bold text-2xl text-blue-600">Create Fixed Asset</h2>
                    <form action="{{ route('accounting.fixed-assets.store') }}" method="POST" autocomplete="off">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="asset_name" class="block mb-2 font-medium text-gray-700">Asset Name</label>
                                <input type="text" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" id="asset_name" name="asset_name" required>
                            </div>
                            <div>
                                <label for="asset_code" class="block mb-2 font-medium text-gray-700">Asset Code</label>
                                <input type="text" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" id="asset_code" name="asset_code" required>
                            </div>
                            <div>
                                <label for="purchase_date" class="block mb-2 font-medium text-gray-700">Purchase Date</label>
                                <input type="date" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" id="purchase_date" name="purchase_date" required>
                            </div>
                            <div>
                                <label for="purchase_price" class="block mb-2 font-medium text-gray-700">Purchase Price</label>
                                <input type="number" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" id="purchase_price" name="purchase_price" required>
                            </div>
                            <div>
                                <label for="depreciation_rate" class="block mb-2 font-medium text-gray-700">Depreciation Rate (%)</label>
                                <input type="number" step="0.01" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" id="depreciation_rate" name="depreciation_rate" required>
                            </div>
                            <div>
                                <label for="location" class="block mb-2 font-medium text-gray-700">Location</label>
                                <input type="text" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" id="location" name="location">
                            </div>
                        </div>
                        <div class="flex justify-between mt-8">
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md font-semibold hover:bg-blue-700 transition">
                                <i class="bi bi-plus-circle mr-2"></i> Create
                            </button>
                            <a href="{{ route('accounting.fixed-assets.index') }}" class="bg-white border border-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-100 transition flex items-center">
                                <i class="bi bi-arrow-left mr-2"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
