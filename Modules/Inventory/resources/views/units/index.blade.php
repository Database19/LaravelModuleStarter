@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8" x-data="unitManager()">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Satuan</h1>
            <p class="mt-1 text-sm text-gray-600">Kelola semua satuan unit yang digunakan dalam sistem inventaris.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <button @click="openCreateModal()" type="button"
                class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 4v16m8-8H4" />
                </svg>
                Tambah Satuan
            </button>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-sm font-semibold text-gray-700 tracking-wider">
                        Nama Satuan
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-sm font-semibold text-gray-700 tracking-wider">
                        Kode Singkat
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-right text-sm font-semibold text-gray-700 tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($units as $unit)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $unit->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $unit->short_code }}</td>
                    <td class="px-6 py-4 text-sm text-right space-x-3">
                        <button @click="openEditModal({{ $unit->id }})"
                            class="text-indigo-600 hover:text-indigo-800 font-medium transition">Edit</button>
                        <x-delete-button :action="route('inventory.units.destroy', $unit->id)" />
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data satuan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal --}}
    <x-modal name="unit-form-modal" max-width="lg">
        <form @submit.prevent="submitForm()" id="unit-form" :action="formAction">
            @csrf
            <input type="hidden" name="_method" :value="formMethod">

            @include('inventory::units._form')

            <div class="mt-6 flex justify-end gap-2 bg-gray-50 px-6 py-4 rounded-b-lg">
                <button @click.prevent="$dispatch('close-modal')" type="button"
                    class="rounded-md bg-white px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 hover:bg-gray-100 transition">
                    Batal
                </button>
                <button type="submit"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-500 transition">
                    Simpan
                </button>
            </div>
        </form>
    </x-modal>
</div>
@endsection

@push('scripts')
<script>
function unitManager() {
    return {
        modalTitle: '',
        formAction: '',
        formMethod: 'POST',
        formData: { name: '', short_code: '' },

        openCreateModal() {
            this.modalTitle = 'Tambah Satuan Baru';
            this.formAction = '{{ route("inventory.units.store") }}';
            this.formMethod = 'POST';
            this.formData = { name: '', short_code: '' };
            this.$dispatch('open-modal', { name: 'unit-form-modal', title: this.modalTitle });
        },

        async openEditModal(unitId) {
            this.formAction = `/inventory/units/${unitId}`;
            this.formMethod = 'PUT';
            try {
                const response = await fetch(`/inventory/units/${unitId}/edit`);
                const data = await response.json();
                this.formData = data;
                this.modalTitle = 'Edit Satuan: ' + data.name;
                this.$dispatch('open-modal', { name: 'unit-form-modal', title: this.modalTitle });
            } catch (error) {
                Swal.fire('Error!', 'Gagal memuat data.', 'error');
            }
        },

        async submitForm() {
            try {
                const response = await fetch(this.formAction, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: new FormData(document.getElementById('unit-form')),
                });

                const result = await response.json();
                if (!response.ok) {
                    const errors = Object.values(result.errors).map(e => `<li>${e}</li>`).join('');
                    Swal.fire('Gagal!', `<ul class="text-left list-disc pl-5">${errors}</ul>`, 'error');
                    throw new Error('Validation failed');
                }

                this.$dispatch('close-modal');
                await Swal.fire('Berhasil!', result.message, 'success');
                window.location.reload();
            } catch (error) {
                if (error.message !== 'Validation failed') {
                    Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error');
                }
            }
        },
    }
}
</script>
@endpush
