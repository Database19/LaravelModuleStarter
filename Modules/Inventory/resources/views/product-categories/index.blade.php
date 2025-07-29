@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8" x-data="categoryManager()">
    {{-- Header --}}
    <div class="sm:flex sm:items-center sm:justify-between">
        <h1 class="text-xl font-semibold text-gray-900">Kategori Produk</h1>
        <button @click="openCreateModal()" type="button" class="inline-flex items-center ... bg-indigo-600 ...">
            Tambah Kategori
        </button>
    </div>

    {{-- Tabel --}}
    <div class="mt-8 flex flex-col">
        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3.5 pl-4 ...">Nama Kategori</th>
                            <th class="px-3 py-3.5 ...">Parent</th>
                            <th class="px-3 py-3.5 ...">Status</th>
                            <th class="relative py-3.5 ..."><span class="sr-only">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($categories as $category)
                        <tr>
                            <td class="py-4 pl-4 ...">{{ $category->name }}</td>
                            <td class="px-3 py-4 ...">{{ $category->parent->name ?? '-' }}</td>
                            <td class="px-3 py-4 ...">
                                @if($category->is_active)
                                    <span class="inline-flex rounded-full bg-green-100 ...">Aktif</span>
                                @else
                                    <span class="inline-flex rounded-full bg-red-100 ...">Non-Aktif</span>
                                @endif
                            </td>
                            <td class="relative py-4 ... text-right">
                                <button @click="openEditModal({{ $category->id }})" type="button" class="text-indigo-600 ...">Edit</button>
                                <x-delete-button :action="route('inventory.product-categories.destroy', $category->id)" />
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4">Tidak ada data kategori.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Komponen Modal --}}
    <x-modal name="category-form-modal" :title="modalTitle" max-width="lg">
        <form @submit.prevent="submitForm()" id="category-form" :action="formAction">
            @csrf
            <input type="hidden" name="_method" :value="formMethod">

            @include('inventory::product-categories._form', ['parentCategories' => $parentCategories])

            <div class="mt-6 flex justify-end gap-x-3 bg-gray-50 -mx-6 -mb-6 px-6 py-4 rounded-b-lg">
                <button @click.prevent="$dispatch('close-modal')" type="button" class="rounded-md bg-white ...">Batal</button>
                <button type="submit" class="rounded-md bg-indigo-600 ...">Simpan</button>
            </div>
        </form>
    </x-modal>
</div>
@endsection

@push('scripts')
<script>
function categoryManager() {
  return {
    modalTitle: '',
    formAction: '',
    formMethod: 'POST',
    formData: { name: '', description: '', parent_id: null, is_active: true },

    openCreateModal() {
      this.modalTitle = 'Tambah Kategori Baru';
      this.formAction = '{{ route("inventory.product-categories.store") }}';
      this.formMethod = 'POST';
      this.formData = { name: '', description: '', parent_id: null, is_active: true };
      this.$dispatch('open-modal', { name: 'category-form-modal', title: this.modalTitle });
    },

    async openEditModal(categoryId) {
      this.formAction = `/inventory/product-categories/${categoryId}`;
      this.formMethod = 'PUT';
      try {
        const response = await fetch(`/inventory/product-categories/${categoryId}/edit`);
        const data = await response.json();
        this.formData = data;
        this.modalTitle = 'Edit Kategori: ' + data.name;
        this.$dispatch('open-modal', { name: 'category-form-modal', title: this.modalTitle });
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
          body: new FormData(document.getElementById('category-form')),
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
