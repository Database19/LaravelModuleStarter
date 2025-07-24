{{-- File Partial untuk form Create dan Edit --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap*</label>
        <input type="text" name="name" id="name" value="{{ old('name', $lead->name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
    </div>
    <div>
        <label for="company_name" class="block text-sm font-medium text-gray-700">Nama Perusahaan</label>
        <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $lead->company_name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email', $lead->email ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label for="phone" class="block text-sm font-medium text-gray-700">Telepon</label>
        <input type="text" name="phone" id="phone" value="{{ old('phone', $lead->phone ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label for="source" class="block text-sm font-medium text-gray-700">Sumber Lead</label>
        <input type="text" name="source" id="source" value="{{ old('source', $lead->source ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="e.g., Website, Pameran">
    </div>
    <div>
        <label for="owner_id" class="block text-sm font-medium text-gray-700">Owner (Sales)*</label>
        <select name="owner_id" id="owner_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            @foreach($users as $user)
            <option value="{{ $user->id }}" {{ (old('owner_id', $lead->owner_id ?? auth()->id())) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="md:col-span-2">
        <label for="status" class="block text-sm font-medium text-gray-700">Status*</label>
        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            <option value="new" {{ (old('status', $lead->status ?? 'new')) == 'new' ? 'selected' : '' }}>New</option>
            <option value="contacted" {{ (old('status', $lead->status ?? '')) == 'contacted' ? 'selected' : '' }}>Contacted</option>
            <option value="qualified" {{ (old('status', $lead->status ?? '')) == 'qualified' ? 'selected' : '' }}>Qualified</option>
            <option value="lost" {{ (old('status', $lead->status ?? '')) == 'lost' ? 'selected' : '' }}>Lost</option>
        </select>
    </div>
    <div class="md:col-span-2">
        <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
        <textarea name="notes" id="notes" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('notes', $lead->notes ?? '') }}</textarea>
    </div>
</div>
