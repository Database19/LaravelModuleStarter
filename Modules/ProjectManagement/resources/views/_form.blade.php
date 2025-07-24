<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nama Proyek*</label>
            <input type="text" name="name" id="name" value="{{ old('name', $project->name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>
        <div>
            <label for="customer_id" class="block text-sm font-medium text-gray-700">Pelanggan</label>
            <select name="customer_id" id="customer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">-- Proyek Internal --</option>
                @foreach($customers as $customer)
                <option value="{{ $customer->id }}" {{ (old('customer_id', $project->customer_id ?? '')) == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
        <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $project->description ?? '') }}</textarea>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai*</label>
            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', isset($project) && $project->start_date ? $project->start_date->format('Y-m-d') : now()->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>
        <div>
            <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
            <input type="date" name="end_date" id="end_date" value="{{ old('end_date', isset($project) && $project->end_date ? $project->end_date->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label for="manager_id" class="block text-sm font-medium text-gray-700">Manajer Proyek*</label>
            <select name="manager_id" id="manager_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                @foreach($users as $user)
                <option value="{{ $user->id }}" {{ (old('manager_id', $project->manager_id ?? auth()->id())) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="project_status_id" class="block text-sm font-medium text-gray-700">Status Proyek*</label>
            <select name="project_status_id" id="project_status_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                @foreach($statuses as $status)
                <option value="{{ $status->id }}" {{ (old('project_status_id', $project->project_status_id ?? '')) == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="priority" class="block text-sm font-medium text-gray-700">Prioritas*</label>
            <select name="priority" id="priority" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                <option value="low" {{ (old('priority', $project->priority ?? 'medium')) == 'low' ? 'selected' : '' }}>Rendah</option>
                <option value="medium" {{ (old('priority', $project->priority ?? 'medium')) == 'medium' ? 'selected' : '' }}>Sedang</option>
                <option value="high" {{ (old('priority', $project->priority ?? 'medium')) == 'high' ? 'selected' : '' }}>Tinggi</option>
                <option value="urgent" {{ (old('priority', $project->priority ?? 'medium')) == 'urgent' ? 'selected' : '' }}>Mendesak</option>
            </select>
        </div>
    </div>
    <div>
        <label for="budget" class="block text-sm font-medium text-gray-700">Anggaran (Budget)</label>
        <input type="number" name="budget" id="budget" value="{{ old('budget', $project->budget ?? 0) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" min="0" step="0.01">
    </div>
</div>
