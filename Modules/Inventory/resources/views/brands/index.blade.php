@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8" x-data="brandManager()">
    {{-- Header --}}
    <div class="sm:flex sm:items-center sm:justify-between">
        <h1 class="text-xl font-semibold text-gray-900">Manajemen Merek</h1>
        <button @click="openCreateModal()" type="button" class="inline-flex items-center ... bg-indigo-600 ...">
            Tambah Merek
        </button>
    </div>

    {{-- Tabel --}}
    <div class="mt-8 flex flex-col">
        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3.5 pl-4 ...">Logo</th>
                            <th class="px-3 py-3.5 ...">Nama Merek</th>
                            <th class="px-3 py-3.5 ...">Status</th>
                            <th class="relative py-3.5 ..."><span class="sr-only">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($brands as $brand)
                        <tr>
                            <td class="py-4 pl-4 ...">
                                @if($brand->logo_url)
                                    <img src="{{ asset('storage/' . $brand->logo_url) }}" alt="{{ $brand->name }}" class="h-10 w-10 object-contain rounded-md">
                                @else
                                    <span class="text-xs text-gray-400">No Logo</span>
                                @endif
                            </td>
                            <td class="px-3 py-4 ...">{{ $brand->name }}</td>
                            <td class="px-3 py-4 ...">
                                @if($brand->is_active)
                                    <span class="inline-flex rounded-full bg-green-100 ...">Aktif</span>
                                @else
                                    <span class="inline-flex rounded-full bg-red-100 ...">Non-Aktif</span>
                                @endif
                            </td>
                            <td class="relative py-4 ... text-right">
                                <button @click="openEditModal({{ $brand->id }})" type="button" class="text-indigo-600 ...">Edit</button>
                                <x-delete-button :action="route('brands.destroy', $brand->id)" />
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4">Tidak ada data merek.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Komponen Modal --}}
    <x-modal name="brand-form-modal" :title="modalTitle" max-width="lg">
        <form @submit.prevent="submitForm()" id="brand-form" :action="formAction" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" :value="formMethod">

            @include('inventory::brands._form')

            <div class="mt-6 flex justify-end gap-x-3 bg-gray-50 -mx-6 -mb-6 px-6 py-4 rounded-b-lg">
                <button @click.prevent="$dispatch('close-modal')" type="button" class="rounded-md bg-white ...">Batal</button>
                <button type="submit" class="rounded-md bg-indigo-600 ...">Simpan</button>
            </div>
        </form>
    </x-modal>
</div>
@endsection

@push('js')
<script>
function brandManager() {
  return {
    modalTitle: '',
    formAction: '',
    formMethod: 'POST',
    formData: { name: '', logo_url: null, is_active: true },

    openCreateModal() {
      this.modalTitle = 'Tambah Merek Baru';
      this.formAction = '{{ route("inventory.brands.store") }}';
      this.formMethod = 'POST';
      this.formData = { name: '', logo_url: null, is_active: true };
      document.getElementById('brand-form').reset(); // Reset file input
      this.$dispatch('open-modal', { name: 'brand-form-modal', title: this.modalTitle });
    },

    async openEditModal(brandId) {
      this.formAction = `/inventory/brands/${brandId}`;
      this.formMethod = 'POST'; // Selalu POST untuk FormData
      try {
        const response = await fetch(`/inventory/brands/${brandId}/edit`);
        const data = await response.json();
        this.formData = data;
        this.modalTitle = 'Edit Merek: ' + data.name;
        document.getElementById('brand-form').reset();
        this.$dispatch('open-modal', { name: 'brand-form-modal', title: this.modalTitle });
      } catch (error) {
        Swal.fire('Error!', 'Gagal memuat data.', 'error');
      }
    },

    async submitForm() {
      const form = document.getElementById('brand-form');
      const formData = new FormData(form);
      // Untuk update, kita harus menambahkan _method secara manual
      if (this.formMethod === 'PUT') {
          formData.append('_method', 'PUT');
      }

      try {
        const response = await fetch(this.formAction, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
          },
          body: formData,
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
  };
}
</script>
@endpush
