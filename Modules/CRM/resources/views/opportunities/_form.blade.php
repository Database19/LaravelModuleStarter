{{-- File Partial untuk form Create dan Edit Opportunity --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nama Opportunity*</label>
        <input type="text" name="name" id="name" value="{{ old('name', $opportunity->name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required placeholder="Contoh: Proyek Website PT Sejahtera">
    </div>
    <div>
        <label for="customer_id" class="block text-sm font-medium text-gray-700">Pelanggan*</label>
        <select name="customer_id" id="customer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            <option value="">Pilih Pelanggan</option>
            @foreach($customers as $customer)
            <option value="{{ $customer->id }}" {{ (old('customer_id', $opportunity->customer_id ?? '')) == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="expected_value" class="block text-sm font-medium text-gray-700">Perkiraan Nilai (Rp)</label>
        <input type="number" name="expected_value" id="expected_value" value="{{ old('expected_value', $opportunity->expected_value ?? 0) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" min="0">
    </div>
    <div>
        <label for="expected_closing_date" class="block text-sm font-medium text-gray-700">Perkiraan Tanggal Closing</label>
        {{-- Perbaikan: Cek apakah $opportunity ada sebelum mengakses propertinya --}}
        <input type="date" name="expected_closing_date" id="expected_closing_date" value="{{ old('expected_closing_date', isset($opportunity) && $opportunity->expected_closing_date ? $opportunity->expected_closing_date->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label for="owner_id" class="block text-sm font-medium text-gray-700">Owner (Sales)*</label>
        <select name="owner_id" id="owner_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            @foreach($users as $user)
            <option value="{{ $user->id }}" {{ (old('owner_id', $opportunity->owner_id ?? auth()->id())) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="stage" class="block text-sm font-medium text-gray-700">Tahapan*</label>
        <select name="stage" id="stage" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            <option value="prospecting" {{ (old('stage', $opportunity->stage ?? 'prospecting')) == 'prospecting' ? 'selected' : '' }}>Prospecting</option>
            <option value="proposal" {{ (old('stage', $opportunity->stage ?? '')) == 'proposal' ? 'selected' : '' }}>Proposal</option>
            <option value="negotiation" {{ (old('stage', $opportunity->stage ?? '')) == 'negotiation' ? 'selected' : '' }}>Negotiation</option>
            <option value="won" {{ (old('stage', $opportunity->stage ?? '')) == 'won' ? 'selected' : '' }}>Won</option>
            <option value="lost" {{ (old('stage', $opportunity->stage ?? '')) == 'lost' ? 'selected' : '' }}>Lost</option>
        </select>
    </div>
</div>
